<?php
namespace App\Domains\Checkouts;

// Domains
require_once __DIR__ . "/../Carts/CartController.php";
require_once __DIR__ . "/../Categories/CategoriesController.php";

// Services
require_once __DIR__ . '/../../Services/UspsService.php';


use App\Domains\Carts\CartController;
use App\Domains\Categories\CategoriesController;
use App\Services\UspsService;

class CheckoutController
{
    private CartController $cart;
    private CategoriesController $categories;

    public function __construct(\mysqli $mysqli)
    {
        $this->cart = new CartController($mysqli); 
        $this->categories = new CategoriesController($mysqli);
    }

    /**
     * Muestra la página de checkout con los items del carrito y resumen de dimensiones.
     */
    public function show()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Obtener items del carrito usando el modelo Cart
        // $cartItems = $this->cart->cart->getItems() ?? [];
        $cartItems = $this->cart->getCartModel()->getItems() ?? [];
    
        // Resumen de dimensiones usando el modelo Cart
        $summary = $this->cart->getCartModel()->getDimensionsSummary() ?? [
            'total_weight' => 0,
            'total_length' => 0,
            'max_width'   => 0,
            'max_height'  => 0,
        ];

        $_SESSION['cart_summary'] = $summary;
    
        // Obtener categorías para footer
        $categories = $this->categories->getCategories() ?? [];
    
        // Renderizar la vista
       include dirname(__DIR__, 3) . '/views/pages/checkout.php';
    }

    /**
     * Checkout para tráfico Meta/Instagram
     */
    public function meta() 
    {
        require __DIR__ . "/../../Views/checkout_meta.php";
    }

    /**
     * Ejemplo de cálculo de tarifa USPS desde el carrito
     */
    public function rate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');

        $cartSummary = $_SESSION['cart_summary'] ?? null;
        if (!$cartSummary) {
            echo json_encode(['error' => true, 'message' => 'No hay items en el carrito']);
            exit;
        }

        $destinationZip = trim($_POST['zip'] ?? '');
        if (!$destinationZip) {
            echo json_encode(['error' => true, 'message' => 'ZIP destino requerido']);
            exit;
        }

        $oauthToken = UspsService::getOAuthToken();
        $rateResponse = UspsService::getRate(
            $oauthToken,
            $_ENV['SHIP_FROM_ZIP'],
            $destinationZip,
            $cartSummary['total_weight'],
            $cartSummary['total_length'],
            $cartSummary['max_width'],
            $cartSummary['max_height']
        );

        $price = $rateResponse['rates'][0]['price'] ?? $rateResponse['totalBasePrice'] ?? 0;
        echo json_encode([
            'price' => $price,
            'delivery_estimate' => UspsService::getDeliveryEstimate($oauthToken, $_ENV['SHIP_FROM_ZIP'], $destinationZip)
        ], JSON_PRETTY_PRINT);
    }
}