<?php
//PHPMailer
//Datos del Servidor SMTP (GoDaddy)
require_once __DIR__ . '/../../vendor/autoload.php';  // Autoload de Composer
require_once __DIR__ . '/env.php'; // Carga las variables de .env

$host = $_ENV['MAIL_HOST'] ?? 'smtp.example.com'; // Servidor SMTP de GoDaddy or Set the SMTP server to send through
$SMTPAuth   = true;                   //Enable SMTP authentication
$Username   = $_ENV['MAIL_USERNAME'] ?? '';         //SMTP username
$Password   = $_ENV['MAIL_PASSWORD'] ?? ''; //SMTP password
$SMTPSecure = 'ssl';                  //SSL o Enable implicit TLS encryption
$Port       = $_ENV['MAIL_PORT'] ?? 587;'465';  
    
    $messageSent ='Mensaje Enviado';
    $messageError='Mensaje no Enviado';

    $mailerToToo = 'ikusa.ads@gmail.com';

//message 
$font_family = "roboto";
?>

