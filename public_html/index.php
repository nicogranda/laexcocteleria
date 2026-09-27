<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) session_start();

// Carga variables de entorno
require __DIR__ . '/../app/src/Shared/config/env.php';

$appName = $_ENV['APP_NAME'];
$baseUrl = $_ENV['APP_URL'];
// Ruta pública de los assets: admite MAMP en una subcarpeta y el dominio en raíz.
$assetBasePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/.');

$brand   = $_ENV['APP_NAME'];
$fbPixel = $_ENV['FACEBOOK_PIXEL_ID'] ?? '';
$gaId    = $_ENV['GOOGLE_ANALYTICS_ID'] ?? '';
$ahrefKey= $_ENV['AHREF_KEY'] ?? '';

/* =============================
   CAMBIO DE IDIOMA (POST)
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'lang') {
    $newLang = strtoupper($_POST['lang'] ?? 'EN');
    if (in_array($newLang, ['EN','ES'])) {
        $_SESSION['lang'] = $newLang;
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

$page = $_GET['page'] ?? 'home';
$lang = $_SESSION['lang'] ?? 'ES';

// Assets y conexion
require __DIR__ . '/../app/src/Shared/config/assets.php';
// require __DIR__ . '/../app/src/Shared/config/connection.php';

// Controllers
require_once "../app/src/Domains/Categories/CategoriesController.php";
require_once "../app/src/Domains/Portfolios/PortfolioController.php";
require_once "../app/src/Domains/Home/HomeController.php";
require_once "../app/src/Domains/Card/CardController.php";
require_once "../app/src/Domains/Carts/CartController.php";
require_once "../app/src/Domains/Checkouts/CheckoutController.php";
require_once "../app/src/Domains/Payments/PaymentsController.php";
require_once "../app/src/Domains/Orders/OrderController.php";
// require_once "../app/src/Domains/Deliveries/DeliveriesController.php";
require_once "../app/src/Domains/Contact/ContactController.php";
require_once __DIR__ . '/../app/src/Domains/Policies/Policy.php';
require_once __DIR__ . '/../app/src/Domains/Policies/PolicyData.php';

require_once "../app/src/Domains/Admin/AdminAuthController.php";
require_once "../app/src/Domains/Admin/ProductsController.php";


use App\Domains\Categories\CategoriesController;
use App\Domains\Portfolios\PortfolioController;
use App\Domains\Home\HomeController;
use App\Domains\Card\CardController;
use App\Domains\Carts\CartController;
use App\Domains\Checkouts\CheckoutController;
use App\Domains\Payments\PaymentsController;
use App\Domains\Orders\OrderController;
// use App\Domains\Deliveries\DeliveriesController;
use App\Domains\Contact\ContactController;

// use App\Domains\Legal\LegalController;

use App\Domains\Admin\AdminAuthController;
// Categories globales
// $categoriesController = new CategoriesController($mysqli, $lang);
// $categories = $categoriesController->getCategories();


/* =============================
   AJAX ROUTER (DEBE ESTAR AQUÍ ARRIBA)
============================= */
// $page   = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

$isApiRequest = $page === 'auth';

if ($page === 'cart' && $action === 'add') {
    // NO cargar head, header, ni nada de HTML
    require_once "../app/src/Domains/Carts/CartController.php";
    $controller = new \App\Domains\Carts\CartController($mysqli);
    $controller->add();
    exit; // CRÍTICO: salir aquí
}

// Instanciar SEOController y generar datos SEO
require_once "../app/src/Shared/SEOController.php";
//$seoController = new \App\Shared\SEOController($mysqli);
// $seoController = new SEOController($mysqli);

// Para productos/cards, pasar el slug si existe
$productSlug = $_GET['slug'] ?? null;
//$seoData = $seoController->generate($page, $productSlug);


// Head

if (!$isApiRequest && $page !== "admin" && $page !== "products") {
   include '../app/views/partials/head.php';
}

// Header
if (!$isApiRequest && $page !== "admin" && $page !== "products") {
    include '../app/views/partials/header.php';
}


// Ruteo principal
switch ($page) {

    case 'home':
        // $controller = new HomeController($mysqli);

        // $search   = $_GET['search'] ?? '';
        // $category = $_GET['category'] ?? '';
        // $action   = $_GET['action'] ?? 'index';

        // if ($action === 'show' && !empty($category)) {
        //     $controller->show($lang, $category);
        // } elseif (!empty($search)) {
        //     $controller->index($lang, $search);
        // } else {
        //     $controller->index($lang);
        // }
          require_once "../app/views/pages/home.php";
        break;

    case 'contact':
        $controller = new ContactController();
        $action = $_GET['action'] ?? 'index';
    
        if ($action === 'mail') {
            $controller->mail();
        } elseif ($action === 'thanks') {
            $controller->thanks();
        } else {
            $controller->index();
        }
        break;
    
    
    case 'policy':
        $slug = $_GET['slug'] ?? '';
        $lang = strtoupper($_GET['lang'] ?? 'ES');
    
        $policyData = new \App\Domains\Policies\PolicyData();
        $policy = $policyData->get($slug, $lang);
    
        if (!$policy) {
            http_response_code(404);
            require __DIR__ . '/../app/views/pages/404.php';
            break;
        }
    
        require __DIR__ . '/../app/src/Domains/Policies/Views/Show.php';
        break;
    
    
    
    break;
        
        
}

// Footer
if (!$isApiRequest && $page !== "admin") {
    //include 'app/views/components/CartTracker.php';
   // include __DIR__ . '/../app/views/partials/footer.php';
}

// Footer + Cookies
if (!$isApiRequest && $page !== 'admin' && $page !== 'products') {
    include __DIR__ . '/../app/views/partials/footer.php';
    require_once __DIR__ . '/../app/src/Domains/Cookies/CookieConsent.php';
}
?>

</body>
</html>

<?php ob_end_flush(); ?>