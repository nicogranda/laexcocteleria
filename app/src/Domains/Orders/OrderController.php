<?php
namespace App\Domains\Orders;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

require_once __DIR__ . "/../../Shared/Model.php";
require_once __DIR__ . "/../Categories/CategoriesController.php";
require_once __DIR__ . "/../Customers/Customer.php"; // Customer DDD
require_once __DIR__ . "/Order.php";                  // Order DDD
require_once __DIR__ . "/../../Services/UspsService.php";
require_once __DIR__ . "/../../Services/MailService.php";
require_once __DIR__ . '/../../Shared/config/env.php';

use App\Domains\Categories\CategoriesController;
use App\Domains\Customers\Customer;
use App\Domains\Orders\Order;
use App\Services\UspsService;
use App\Services\MailService;


class OrderController
{
    protected CategoriesController $categories;
    protected UspsService $uspsService;
    protected MailService $mailService;
    protected Order $orderModel;
    protected $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
        $lang = $_SESSION['lang'] ?? 'EN';
        $this->categories  = new CategoriesController($this->mysqli, $lang);
        $this->uspsService = new UspsService();
        $this->mailService = new MailService();
        $this->orderModel  = new Order($this->mysqli);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=cart");
            exit;
        }

        // Tomar carrito de POST (JSON) o fallback a $_SESSION
        $cartJson = $_POST['cart'] ?? '[]';
        $cart = json_decode($cartJson, true) ?: ($_SESSION['cart_items'] ?? []);

        if (!is_array($cart) || empty($cart)) {
            die("Error: carrito vacío.");
        }

        $paymentIntentId = $_POST['payment_intent_id'] ?? null;
        if (!$paymentIntentId) {
            die("Error: pago inválido.");
        }

        $customerData = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'secondary_address' => trim($_POST['secondary_address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'zip' => trim($_POST['zip'] ?? ''),
            'country' => trim($_POST['country'] ?? '')
        ];

        if (empty($customerData['name']) || empty($customerData['email'])) {
            die("Error: nombre y email son obligatorios.");
        }

        $amountCents = (int) ($_POST['amount'] ?? 0);
        $userId = $_SESSION['user_id'] ?? null;

        try {
            $this->mysqli->begin_transaction();

            // Crear o actualizar cliente DDD
            $customerModel = new Customer($this->mysqli);
            $customerId = $customerModel->findOrCreate($customerData);
            if (!$customerId) {
                throw new \Exception("No se pudo crear o encontrar el cliente.");
            }

            // Crear orden DDD
            $orderModel = new Order($this->mysqli);
            $orderData = [
                'user_id' => $userId,
                'customer_id' => $customerId,
                'customer_name' => $customerData['name'],
                'total' => $amountCents / 100,
                'shipping_address' => $customerData['address'],
                'shipping_secondary_address' => $customerData['secondary_address'],
                'shipping_city' => $customerData['city'],
                'shipping_state' => $customerData['state'],
                'shipping_zip' => $customerData['zip'],
                'shipping_cost' => $_POST['shipping_cost'] ?? 0,
                'discount_code' => $_POST['discount_code'] ?? null,
                'discount_amount' => $_POST['discount_amount'] ?? 0,
                'status' => 'paid',
                'payment_intent_id' => $paymentIntentId
            ];

            $itemsData = array_map(fn($item) => [
                'variant_id' => (int) ($item['variant_id'] ?? 0),
                'qty'        => (int) ($item['qty'] ?? 1),
                'price'      => (float) ($item['price'] ?? 0.0)
            ], $cart);

            $orderId = $orderModel->createWithItems($orderData, $itemsData);
            $this->mysqli->commit();

            // Obtener orden y detalles
            $order = $orderModel->getById($orderId);
            $orderDetails = $orderModel->getDetails($orderId);

            // Generar etiqueta USPS
            try {
                $labelData = $this->generateShippingLabel($orderId);
            } catch (\Throwable $e) {
                error_log("[USPS DEBUG] " . $e->getMessage());
                $labelData = [
                    'trackingNumber' => null,
                    'payload' => null,
                    'oauthToken' => null,
                    'paymentToken' => null,
                    'orderDetails' => $orderDetails
                ];
            }

            // Limpiar carrito legacy
            unset($_SESSION['cart'], $_SESSION['cart_items']);

            // Enviar email de confirmación
            $this->sendConfirmationEmail(
                $order,
                $orderDetails,
                $customerData['email'],
                $customerData,
                $labelData['trackingNumber'] ?? null
            );

            // Redirigir a página de notice
            $this->notice(
                $orderId,
                $customerData['email'],
                $labelData['payload'] ?? null,
                $labelData['oauthToken'] ?? null,
                $labelData['paymentToken'] ?? null,
                $labelData['orderDetails'] ?? $orderDetails,
                $labelData['trackingNumber'] ?? null
            );

        } catch (\Throwable $e) {
            $this->mysqli->rollback();
            error_log("[ORDER CONTROLLER ERROR] " . $e->getMessage());
            die("Error general procesando el pedido.");
        }
    }
    
    private function sendConfirmationEmail(array $order, array $orderDetails, string $to, array $customerData, ?string $trackingNumber = null): void
    {
        $shippingName = htmlspecialchars($customerData['name'] ?? '');
        $shippingAddress = htmlspecialchars($customerData['address'] ?? '');
        $shippingsecondaryAddress = htmlspecialchars($customerData['secondary_address'] ?? '');
        $shippingCity = htmlspecialchars($customerData['city'] ?? '');
        $shippingState = htmlspecialchars($customerData['state'] ?? '');
        $shippingZip = htmlspecialchars($customerData['zip'] ?? '');

        ob_start();
        include __DIR__ . '/../../../views/pages/orders/email.php';
        $body = ob_get_clean();

        $subject = "Order #" . str_pad($order['id'], 6, '0', STR_PAD_LEFT);

        try {
            $sent = $this->mailService->sendMail($to, false, $subject, $body);
            if (!$sent) {
                error_log("Email no enviado a {$to} para order #{$order['id']}");
            }
        } catch (\Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
        }
    }

    public function notice($orderId, $email, $uspsPayload = null, $oauthToken = null, $paymentToken = null, $orderDetails = [], $trackingNumber = null) 
    {
        $_SESSION['notice_data'] = [
            'orderId' => $orderId,
            'email' => $email,
            'trackingNumber' => $trackingNumber,
            'orderDetails' => $orderDetails,
            'uspsPayload' => $uspsPayload,
            'oauthToken' => $oauthToken,
            'paymentToken' => $paymentToken
        ];

        header("Location: index.php?page=notice");
        exit;
    }

private function generateShippingLabel(int $orderId): array
{
    $debugDir = __DIR__ . '/../../storage/usps_debug';
    if (!is_dir($debugDir)) mkdir($debugDir, 0755, true);
    
    if (!isset($_ENV['SHIP_FROM_FIRSTNAME'])) {
        require_once __DIR__ . '/../../Shared/config/env.php';
    }

    // ✅ DEBUG: Verificar orden
    $order = $this->orderModel->getById($orderId) ?? [];
    error_log("[USPS DEBUG] Order data: " . json_encode($order));
    
    $orderDetails = $this->orderModel->getDetails($orderId) ?? [];
    error_log("[USPS DEBUG] Order details: " . json_encode($orderDetails));

    $nameParts = explode(' ', $order['customer_name'] ?? '', 2);
    $firstName = $nameParts[0] ?? '';
    $lastName  = $nameParts[1] ?? '';
    $secondaryAddress = trim($order['shipping_secondary_address'] ?? '');

    $toAddress = [
        "firstName" => $firstName,
        "lastName"  => $lastName,
        "streetAddress" => $order['shipping_address'] ?? '',
        "secondaryAddress" => $secondaryAddress,
        "city" => strtoupper($order['shipping_city'] ?? ''),
        "state" => strtoupper($order['shipping_state'] ?? ''),
        "ZIPCode" => $order['shipping_zip'] ?? ''
    ];

    $fromAddress = [
        "firstName" => $_ENV['SHIP_FROM_FIRSTNAME'] ?? 'Yusmira',
        "lastName"  => $_ENV['SHIP_FROM_LASTNAME'] ?? 'Granda',
        "streetAddress" => $_ENV['SHIP_FROM_ADDRESS'] ?? '4322 Wilsford Oak Way',
        "secondaryAddress" => $_ENV['SHIP_FROM_SECONDARY'] ?? '',
        "city" => $_ENV['SHIP_FROM_CITY'] ?? 'FULSHEAR',
        "state" => $_ENV['SHIP_FROM_STATE'] ?? 'TX',
        "ZIPCode" => $_ENV['SHIP_FROM_ZIP'] ?? '77441'
    ];

    $senderAddress = $fromAddress;
    $returnAddress = $fromAddress;

    $totalWeight = array_sum(array_map(fn($item) => ($item['weight'] ?? 1) * ($item['quantity'] ?? 1), $orderDetails));
    error_log("[USPS DEBUG] Total weight calculated: " . $totalWeight);

    $packageDescription = [
        "mailClass" => "USPS_GROUND_ADVANTAGE",
        "rateIndicator" => "SP",
        "weightUOM" => "lb",
        "weight" => $totalWeight ?: 1,
        "dimensionsUOM" => "in",
        "length" => 5,
        "width" => 5,
        "height" => 5,
        "processingCategory" => "NONSTANDARD",
        "mailingDate" => (new \DateTime('now', new \DateTimeZone('America/New_York')))
                            ->modify('+1 day')
                            ->format('Y-m-d'),
        "extraServices" => [],
        "destinationEntryFacilityType" => "NONE"
    ];

    $imageInfo = [
        "imageType" => "PDF",
        "labelSize" => "4X6LABEL"
    ];

    $payload = [
        "imageInfo" => $imageInfo,
        "toAddress" => $toAddress,
        "fromAddress" => $fromAddress,
        "senderAddress" => $senderAddress,
        "returnAddress" => $returnAddress,
        "packageDescription" => $packageDescription
    ];

    // ✅ DEBUG: Guardar payload ANTES de enviarlo
    file_put_contents(
        $debugDir . '/payload_' . $orderId . '_' . time() . '.json',
        json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
    
    error_log("[USPS DEBUG] Payload: " . json_encode($payload));

    $oauthToken = UspsService::getOAuthToken();
    error_log("[USPS DEBUG] OAuth Token: " . ($oauthToken ? 'OK' : 'FAILED'));
    
    $paymentToken = $oauthToken ? UspsService::getPaymentAuthorization($oauthToken) : null;
    error_log("[USPS DEBUG] Payment Token: " . ($paymentToken ? 'OK' : 'FAILED'));
    
    $debugLabel = UspsService::createLabelWithDebug($oauthToken, $paymentToken, $payload);
    
    // ✅ DEBUG: Guardar respuesta SIEMPRE (con error o sin error)
    file_put_contents(
        $debugDir . '/response_' . $orderId . '_' . time() . '.json',
        json_encode($debugLabel, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
    
    error_log("[USPS DEBUG] Response HTTP Code: " . ($debugLabel['httpCode'] ?? 'N/A'));
    error_log("[USPS DEBUG] Response: " . json_encode($debugLabel));

    if (($debugLabel['httpCode'] ?? 0) !== 200) {
        // ✅ Mejor manejo de errores
        $errorMsg = $debugLabel['jsonResponse']['error']['message'] ?? 
                   $debugLabel['jsonResponse']['message'] ?? 
                   $debugLabel['rawResponse'] ?? 
                   'Unknown USPS error';
        throw new \Exception("USPS Error: " . $errorMsg);
    }

    $savedLabel = $this->uspsService->saveMultipartLabel($debugLabel);
    $labelPath = $savedLabel['path'];
    $trackingNumber = $savedLabel['tracking'];

    $this->orderModel->updateTrackingAndLabel($orderId, $trackingNumber, $labelPath);

    return [
        'orderId' => $orderId,
        'order' => $order,
        'orderDetails' => $orderDetails,
        'payload' => $payload,
        'oauthToken' => $oauthToken,
        'paymentToken' => $paymentToken,
        'debugLabel' => $debugLabel,
        'trackingNumber' => $trackingNumber
    ];
}
    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            exit;
        }

        $trackingNumber = $_POST['tracking'] ?? null;
        $imb = $_POST['imb'] ?? null;

        if (!$trackingNumber && !$imb) {
            http_response_code(400);
            echo "Tracking o IMB requerido";
            exit;
        }

        $orderId = $this->orderModel->getOrderIdByTracking($trackingNumber);

        if (!$orderId) {
            http_response_code(400);
            echo "order_id requerido";
            exit;
        }

        try {
            $oauthToken = UspsService::getOAuthToken();
            $paymentToken = UspsService::getPaymentAuthorization($oauthToken);

            $result = UspsService::cancelLabel($oauthToken, $paymentToken, $trackingNumber ?? $imb);

            require_once __DIR__ . '/../models/Label.php';
            $labelModel = new \App\Models\Label();

            if ($result['httpCode'] === 200) {
                if ($labelModel->getByOrderId($orderId)) {
                    $labelModel->confirmCancel($orderId);
                } else {
                    $labelModel->upsertByOrderId(
                        $orderId,
                        $trackingNumber ?? '',
                        \App\Models\Label::STATUS_CANCELLED,
                        $imb
                    );
                }
                echo "Label cancelada / reembolso solicitado";
                require_once $_SERVER['DOCUMENT_ROOT'] . '/api/USPS/delete.php';
            } else {
                $labelModel->requestCancel($orderId, $trackingNumber ?? '', $imb);
                http_response_code(400);
                echo "❌ No se pudo cancelar: " . ($result['error']['message'] ?? 'Error desconocido');
            }

        } catch (\Throwable $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }
}
