<?php
declare(strict_types=1);

// Ruta al .env (en la raíz del proyecto)
$envFile = __DIR__ . '/../../../../.env';

// Comprobar si existe y cargar variables
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (str_starts_with(trim($line), '#')) continue;

        [$key, $value] = array_map('trim', explode('=', $line, 2) + [null, null]);
        if ($key !== null && $value !== null) {
            $_ENV[$key] = $value;
        }
    }
}

// Valores por defecto
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? '';
$db_pass = $_ENV['DB_PASS'] ?? '';
$db_name = $_ENV['DB_NAME'] ?? '';

// Definir constantes (opcional)
define('DB_HOST',  $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);

// Crear conexión
$mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_name);

// Charset
$mysqli->set_charset('utf8mb4');

// Verificar conexión
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error de conexión a la base de datos',
        'errno' => $mysqli->connect_errno,
        'message' => $mysqli->connect_error
    ]);
    exit();
}