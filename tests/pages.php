<?php
declare(strict_types=1);
require __DIR__ . '/../app/src/Domains/Page/PageController.php';
function check(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}
$controller = new App\Domains\Page\PageController();
$slugs = ['cocteleria-eventos-donostia', 'catering-cocteleria-gipuzkoa', 'bartender-para-eventos', 'galeria'];
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
    check(str_contains($html, 'href="/laex/es/contacto"'), 'CTA incorrecto en subcarpeta');
    if ($slug !== 'galeria') {
        check(!str_contains($html, 'page-gallery__grid'), 'Galería no solicitada');
        check(substr_count($html, '<details>') === 3, 'Faltan preguntas frecuentes');
    } else {
        check(str_contains($html, 'page-gallery__grid') || str_contains($html, 'Aún no hay fotografías'), 'Falta el componente Gallery');
    }
}
check($controller->prepare('no-existe') === null, 'Slug desconocido aceptado');
check(http_response_code() === 404, 'No se establece 404');
$index = file_get_contents(__DIR__ . '/../public_html/index.php');
check(strpos($index, '$pageController->prepare') < strpos($index, "include '../app/views/partials/head.php'"), 'SEO preparado después del head');
echo "Páginas, galería, SEO, enlaces de subcarpeta y 404: OK\n";
