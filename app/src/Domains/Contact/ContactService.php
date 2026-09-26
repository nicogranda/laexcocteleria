<?php
namespace App\Domains\Contact;

use App\Services\MailService;

require_once __DIR__ . '/../../Services/MailService.php';

class ContactService
{
    private string $recipient = 'contacto@laexcocteleria.com';

    public function send(array $data): array
    {
        $name    = trim($data['name'] ?? '');
        $email   = trim($data['email'] ?? '');
        $mobile  = trim($data['mobile'] ?? '');
        $message = trim($data['message'] ?? '');
        $privacy = !empty($data['privacy']);

        if ($name === '') return ['success' => false, 'message' => 'Introduce tu nombre.'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Introduce un correo electrónico válido.'];
        }

        if ($mobile === '') return ['success' => false, 'message' => 'Introduce tu número de móvil.'];

        if ($message === '') return ['success' => false, 'message' => 'Escribe un mensaje.'];

        if (!$privacy) {
            return ['success' => false, 'message' => 'Debes aceptar la política de protección de datos.'];
        }

        $subject = 'Nuevo contacto - La Ex Coctelería';

        $body = '
            <h2>Nuevo contacto desde la web</h2>

            <p><strong>Nombre:</strong> '.htmlspecialchars($name).'</p>

            <p><strong>Email:</strong> '.htmlspecialchars($email).'</p>

            <p><strong>Móvil:</strong> '.htmlspecialchars($mobile).'</p>

            <p><strong>Mensaje:</strong></p>

            <p>'.nl2br(htmlspecialchars($message)).'</p>

            <hr>

            <p style="font-size:12px;color:#777;">
                El usuario ha aceptado la política de protección de datos.
            </p>
        ';

        $mailer = new MailService();

        $sent = $mailer->sendMail(
            $this->recipient,
            false,
            $subject,
            $body,
            $email,
            $name
        );

        return $sent
            ? ['success' => true, 'message' => '']
            : ['success' => false, 'message' => 'No se pudo enviar el mensaje. Inténtalo nuevamente.'];
    }
}