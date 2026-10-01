<?php
declare(strict_types=1);
require __DIR__ . '/../app/src/Domains/Page/PageController.php';
function check(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}
$controller = new App\Domains\Page\PageController();
$slugs = ['cocteleria-eventos-donostia', 'catering-cocteleria-gipuzkoa', 'bartender-para-eventos', 'cocteleria-para-bodas', 'cocteleria-para-eventos-corporativos', 'cocteleria-para-cumpleanos', 'cocteleria-para-despedidas', 'cocteleria-para-graduaciones', 'cocteleria-para-eventos-privados', 'galeria'];
foreach ($slugs as $slug) {
    $data = $controller->prepare($slug);
    check($data !== null, 'No se encuentra ' . $slug);
    check($data['page']['id'] === $data['translation']['page_id'], 'Relación incorrecta');
    $seo = $controller->seo($data, 'https://laexcocteleria.com');
    check($seo['canonical'] === 'https://laexcocteleria.com/' . $slug, 'Canonical incorrecta');
    ob_start();
    $controller->render($data, '/laex');
    $html = ob_get_clean();
    check(substr_count($html, '<h1>') === 1, 'La vista necesita un solo H1');
    if ($slug !== 'galeria') {
        check(str_contains($html, 'href="/laex/contacto"'), 'CTA incorrecto en subcarpeta');
        check(!str_contains($html, 'page-gallery__grid'), 'Galería no solicitada');
        check(substr_count($html, '<details>') === 3, 'Faltan preguntas frecuentes');
        check(str_contains($html, '/laex/' . $data['translation']['hero_image']), 'Imagen incorrecta en subcarpeta');
        check(is_file(__DIR__ . '/../public_html/' . $data['translation']['hero_image']), 'Imagen inexistente');
    } else {
        check(!str_contains($html, 'landing-cta"'), 'La galería debe mostrar solo su contenido');
        check(str_contains($html, 'page-gallery__grid') || str_contains($html, 'Aún no hay fotografías'), 'Falta el componente Gallery');
    }
}
check($controller->prepare('no-existe') === null, 'Slug desconocido aceptado');
check(http_response_code() === 404, 'No se establece 404');
$index = file_get_contents(__DIR__ . '/../public_html/index.php');
check(strpos($index, '$pageController->prepare') < strpos($index, "include '../app/views/partials/head.php'"), 'SEO preparado después del head');
// Las seis tarjetas deben apuntar a páginas del catálogo, también desde una subcarpeta.
$assetBasePath = '/laex';
ob_start();
require __DIR__ . '/../app/views/components/services.php';
$cards = ob_get_clean();
foreach (['bodas','eventos-corporativos','cumpleanos','despedidas','graduaciones','eventos-privados'] as $key) {
    check(str_contains($cards, 'href="/laex/cocteleria-para-' . $key . '"'), 'Tarjeta sin landing: ' . $key);
}
echo "Páginas, galería, SEO, enlaces de subcarpeta y 404: OK\n";


// Navegación en castellano incluso si queda una sesión inglesa anterior.
$lang = 'EN';
$page = 'home';
ob_start();
require __DIR__ . '/../app/views/partials/header.php';
$header = ob_get_clean();
check(str_contains($header, 'Preguntas frecuentes'), 'El menú no está en castellano');
check(!str_contains($header, 'Request a Quote'), 'El menú usa el idioma anterior');
check(str_contains($header, 'href="/laex/galeria"'), 'La galería debe abrir su slug');
check(str_contains($header, 'href="/laex/contacto"'), 'Contacto necesita su slug');
