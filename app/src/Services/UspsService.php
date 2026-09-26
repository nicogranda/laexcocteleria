<?php
namespace App\Services;
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

require_once __DIR__ . '/../Shared/config/env.php';

class UspsService {

    public static function getOAuthToken(): ?string {
        global $_ENV;
        $curl = curl_init($_ENV['USPS_BASE_URL'] . '/oauth2/v3/token');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'client_credentials',
                'client_id' => $_ENV['USPS_CLIENT_ID'],
                'client_secret' => $_ENV['USPS_CLIENT_SECRET']
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $data = json_decode($response, true);
        return ($status === 200 && !empty($data['access_token'])) ? $data['access_token'] : null;
    }

    public static function getPaymentAuthorization(string $token): ?string {
        global $_ENV;
    $roles = [
        [
            "roleName" => "PAYER",
            "CRID" => "50155474",
            "accountType" => "EPS",
            "accountNumber" => $_ENV['USPS_ACCOUNT_NUMBER']
        ],
        [
            "roleName" => "LABEL_OWNER",
            "CRID" => "50155474",
            "MID" => "903800760",
            "manifestMID" => "903800759"
        ]
    ];


        $curl = curl_init($_ENV['USPS_BASE_URL'] . '/payments/v3/payment-authorization');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['roles' => $roles]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]
        ]);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $data = json_decode($response, true);
        return ($status === 200 && isset($data['paymentAuthorizationToken'])) ? $data['paymentAuthorizationToken'] : null;
    }

    public static function createLabelWithDebug(string $oauthToken, string $paymentToken, array $payload): array {
    global $_ENV;
    $paymentToken = trim($paymentToken);
    
    $ch = curl_init(rtrim($_ENV['USPS_BASE_URL'], '/') . '/labels/v3/label');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . trim($oauthToken),
            'X-Payment-Authorization-Token: ' . trim($paymentToken)
        ]
    ]);
    
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        
    
        $data = json_decode($response, true);
    
        // Devuelve todo para depuración: HTTP code y respuesta
        return [
            'httpCode' => $status,
            'rawResponse' => $response,
            'jsonResponse' => $data
        ];
    }

    public function saveMultipartLabel(array $response): array
    {
        $raw = $response['rawResponse'] ?? '';
    
        if (empty($raw)) {
            throw new \Exception('No hay rawResponse para procesar');
        }
    
        if (!preg_match('/--([^\r\n]+)/', $raw, $m)) {
            throw new \Exception('Boundary no encontrado (posible Bad Request de USPS)');
        }
    
        $boundary = $m[1];
        $parts = preg_split('/--' . preg_quote($boundary, '/') . '/', $raw);
    
        $pdfBinary = null;
        $labelJson = null;
    
        foreach ($parts as $part) {
            // metadata
            if (strpos($part, 'name="labelMetadata"') !== false) {
                $bodyStart = strpos($part, "\r\n\r\n") + 4;    
                $jsonText = substr($part, $bodyStart);
                $labelJson = json_decode(trim($jsonText), true);
            }
    
            // PDF
            if (strpos($part, 'name="labelImage"') !== false) {
                $bodyStart = strpos($part, "\r\n\r\n") + 4;
                $pdfBinary = substr($part, $bodyStart);
    
                // ⚡ Capturar PDF completo desde JVBERi0
                if (preg_match('/JVBERi0.*$/s', $pdfBinary, $matches)) {
                    $pdfBinary = $matches[0];
                } else {
                    throw new \Exception('PDF inválido en multipart');
                }
            }
        }
    
        if (!$pdfBinary || !$labelJson) {
            throw new \Exception('No se pudo extraer PDF o metadata');
        }
    
        $tracking = $labelJson['trackingNumber'] ?? 'unknown';
    
        $dir = __DIR__ . '/../../storage/labels';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    
        $file = $dir . '/' . $tracking . '.pdf';
        file_put_contents($file, $pdfBinary);
    
        return [
            'path' => $file,
            'tracking' => $tracking
        ];
    }

    public function requestCarrierPickup($oauthToken, $pickupPayload)
    {
        $url = "https://apis.usps.com/pickup/v3/carrier-pickup";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$oauthToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pickupPayload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($httpCode !== 200) {
            throw new \Exception("USPS Pickup API error ({$httpCode}): {$response}");
        }
    
        return json_decode($response, true);
    }

    public static function cancelLabel(
        string $oauthToken,
        string $paymentToken,
        string $imb          // ← debe ser el IMB, no el tracking number genérico
    ): array {
        // ✅ El IMB va directo en el path de la URL
        $url = rtrim($_ENV['USPS_BASE_URL'], '/') . '/labels/v3/label/' . urlencode($imb);
    
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . trim($oauthToken),
                'X-Payment-Authorization-Token: ' . trim($paymentToken)
            ]
        ]);
    
        $response = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        return [
            'httpCode'     => $httpCode,
            'rawResponse'  => $response,
            'jsonResponse' => json_decode($response, true)
        ];
    }
}