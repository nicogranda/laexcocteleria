<?php
namespace App\Services;

require_once __DIR__ . '/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function sendMail(
        string $to,
        bool $debug = false,
        string $subject = '',
        string $body = '',
        string $replyTo = '',
        string $replyName = ''
    ): bool {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->Port       = (int) $_ENV['MAIL_PORT'];
            $mail->SMTPSecure = $_ENV['MAIL_SMTP_SECURE'];

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ]
            ];

            if ($debug) {
                $mail->SMTPDebug = 2;
                $mail->Debugoutput = 'error_log';
            }

            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);

            if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                $mail->addReplyTo($replyTo, $replyName);
            } else {
                $mail->addReplyTo($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            }

            $mail->CharSet = 'UTF-8';

            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();

            return true;

        } catch (Exception $e) {
            error_log("Mailer Error ({$to}): " . $mail->ErrorInfo);
            return false;
        }
    }
}