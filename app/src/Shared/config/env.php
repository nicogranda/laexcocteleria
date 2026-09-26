<?php
// app/src/Shared/config/env.php

// Incluye Composer autoload usando ruta relativa desde este archivo
// Este archivo está en: app/src/Shared/config/env.php
// Subimos 4 niveles para llegar a la raíz: ../../../../
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Dotenv\Dotenv;

// Ruta al directorio raíz del proyecto (donde está .env)
// Mismo nivel que vendor/
$envPath = __DIR__ . '/../../../../';

if (file_exists($envPath . '.env')) {
    $dotenv = Dotenv::createImmutable($envPath);
    $dotenv->load();
} else {
    die('❌ No se encontró el archivo .env en la raíz del proyecto: ' . realpath($envPath));
}
