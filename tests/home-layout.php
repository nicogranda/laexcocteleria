<?php
// Página de prueba sin conexión, credenciales ni scripts de terceros.
$baseUrl = '/public_html';
$assetBasePath = '/public_html';
$lang = 'ES';
$page = 'home';
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="/public_html/assets/css/style.css"><link rel="stylesheet" href="/public_html/assets/css/fonts.css"></head><body>
<?php
require __DIR__ . '/../app/views/partials/header.php';
if (isset($_GET['slug'])) {
    require __DIR__ . '/../app/src/Domains/Page/PageController.php';
    (new App\Domains\Page\PageController())->show((string) $_GET['slug'], $assetBasePath);
} else {
    require __DIR__ . '/../app/views/pages/home.php';
}
require __DIR__ . '/../app/views/partials/footer.php';
require __DIR__ . '/../app/src/Domains/Cookies/CookieConsent.php';
?>
</body></html>
