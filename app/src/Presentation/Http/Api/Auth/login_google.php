<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
header('Cross-Origin-Opener-Policy: same-origin');
header('Cross-Origin-Embedder-Policy: unsafe-none');

if (ob_get_length()) ob_end_clean();

// Autoload de Composer y conexión DB
require_once dirname(__DIR__, 6) . '/vendor/autoload.php';
require_once dirname(__DIR__, 4) . '/Shared/config/connection.php';


// Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 6));
$dotenv->load();

// Google Client
$client = new Google_Client(['client_id' => $_ENV['GOOGLE_CLIENT_ID']]);

// Leer POST JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['credential'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Token no recibido']);
    exit;
}

$credential = $input['credential'];
$payload = $client->verifyIdToken($credential);

// file_put_contents(__DIR__.'/debug_google.log', "Verificación token ejecutada\n", FILE_APPEND);
// file_put_contents(__DIR__.'/debug_google.log', "Payload: " . print_r($payload, true) . "\n", FILE_APPEND);


if (!$payload) {
    http_response_code(401);
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

// Datos del usuario
$google_id = $payload['sub'];
$email     = $payload['email'];
$name      = $payload['name'];
$provider  = "Google";

// file_put_contents(__DIR__.'/debug_google.log', "Datos extraídos: google_id=$google_id, email=$email, name=$name\n", FILE_APPEND);

$email_esc = $mysqli->real_escape_string($email);
$name_esc  = $mysqli->real_escape_string($name);

// Verificar si el usuario existe
$query = $mysqli->query("SELECT id, role FROM users WHERE email = '$email_esc'");

if ($query->num_rows === 0) {
    $default_role = 'user';
    $mysqli->query("INSERT INTO users (username, name, lastname, email, password, cell_phone, provider, provider_id, role) 
VALUES ('', '$name_esc', '', '$email_esc', '', '', '$provider', '$google_id', '$default_role')");
    $user_id   = $mysqli->insert_id;
    $user_role = $default_role;
} else {
    $row       = $query->fetch_assoc();
    $user_id   = $row['id'];
    $user_role = $row['role'];
}

// Iniciar sesión
$_SESSION['user_id'] = $user_id;
$_SESSION['user']    = [
    'id'    => $user_id,
    'email' => $email,
    'name'  => $name,
    'role'  => $user_role
];

file_put_contents(__DIR__.'/debug_session.log', "Session actual:\n" . print_r($_SESSION, true) . "\n", FILE_APPEND);

// Responder con JSON
echo json_encode([
    'success' => true,
    'user'    => $_SESSION['user']
]);