<?php
namespace App\Domains\Contact;

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/ContactService.php';

class ContactController
{
    public function index(): void
    {
        include __DIR__ . '/../../../views/pages/contact.php';
    }

    public function mail(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /es/contacto');
            exit;
        }

        $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

        if ($captchaResponse === '') {
            $_SESSION['contact_error'] = 'Confirma que no eres un robot.';
            $_SESSION['contact_old'] = $_POST;
            header('Location: /es/contacto');
            exit;
        }

        if (!$this->verifyCaptcha($captchaResponse)) {
            $_SESSION['contact_error'] = 'No se pudo validar reCAPTCHA. Inténtalo nuevamente.';
            $_SESSION['contact_old'] = $_POST;
            header('Location: /es/contacto');
            exit;
        }

        $service = new ContactService();
        $result = $service->send($_POST);

        if ($result['success']) {
            unset($_SESSION['contact_old']);
            $_SESSION['contact_conversion'] = true;
        
            header('Location: /es/confirmacion');
            exit;
        }

        $_SESSION['contact_error'] = $result['message'];
        $_SESSION['contact_old'] = $_POST;

        header('Location: /es/contacto');
        exit;
    }

    private function verifyCaptcha(string $captchaResponse): bool
    {
        $secret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';

        if ($secret === '') return false;

        $data = http_build_query([
            'secret'   => $secret,
            'response' => $captchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ]);

        $context = stream_context_create([
            'http' => [
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => $data,
                'timeout' => 10
            ]
        ]);

        $result = @file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify',
            false,
            $context
        );

        if ($result === false) return false;

        $response = json_decode($result, true);

        return !empty($response['success']);
    }
    
    public function thanks(): void
    {
        include __DIR__ . '/../../../views/pages/sent.php';
    }
}