<?php
/**
 * Assets globales
 * La Ex Coctelería
 *
 * $baseUrl:
 * Producción → https://laexcocteleria.com
 * Local      → http://localhost:8888/laexcocteleria/public_html
 */

$baseUrl = rtrim($baseUrl ?? '', '/');

$faviconUrl = $baseUrl . '/assets/img/favicon.png?v=6';
$fontAwesomeUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css';
$fontsCssUrl = $baseUrl . '/assets/css/fonts.css';
$styleCssUrl = $baseUrl . '/assets/css/style.css';
?>

<!-- Favicon -->
<link rel="icon" type="image/png" href="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>">

<!-- Font Awesome -->
<link rel="stylesheet" href="<?= htmlspecialchars($fontAwesomeUrl, ENT_QUOTES, 'UTF-8') ?>">

<!-- Fonts -->
<link rel="stylesheet" href="<?= htmlspecialchars($fontsCssUrl, ENT_QUOTES, 'UTF-8') ?>">

<!-- CSS Global -->
<link rel="stylesheet" href="<?= htmlspecialchars($styleCssUrl, ENT_QUOTES, 'UTF-8') ?>">
