<?php

namespace App\Domains\Carts;

require_once __DIR__ . "/../../Shared/Model.php"; 
require_once __DIR__ . "/../Products/Product.php";
require_once __DIR__ . "/../Carts/Cart.php";
require_once __DIR__ . '/../../Services/UspsService.php';
require_once __DIR__ . '/../../Shared/config/env.php';

use App\Services\UspsService;
use App\Models\Product;
use App\Models\Cart;

class CartController
{
    protected \mysqli $mysqli;
    protected Cart $cart; // ya sabemos que Cart viene de App\Models

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->cart = new Cart($this->mysqli); // instancia centralizada

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
  
public function add() {
    // Limpiar cualquier output previo
    if (ob_get_level()) ob_clean();
    
    header('Content-Type: application/json');
    
    $variantId = intval($_POST['variant_id'] ?? 0);
    $quantity  = intval($_POST['quantity'] ?? 1);

    try {
        // Debug log
        error_log("ADD CART - variant_id: $variantId, quantity: $quantity");
        
        $result = $this->cart->add($variantId, $quantity);
        $items = $this->cart->getItems();
        $_SESSION['cart_items'] = $items;
        
        // Debug log
        error_log("CART ITEMS: " . print_r($items, true));
        
        ob_start();
        include __DIR__ . '/../../../views/components/MiniCartDropdown.php';
        $dropdownHtml = ob_get_clean();
        
        $response = [
            'status'       => 'ok',
            'cartCount'    => array_sum(array_column($items, 'quantity')),
            'dropdownHtml' => $dropdownHtml
        ];
        
        error_log("RESPONSE: " . json_encode($response));
        
        echo json_encode($response);
        
    } catch (\Exception $e) {
        error_log("CART ERROR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    
    exit;
}

    // Remove a product from the cart
    public function remove(?string $id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');

        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);

            // Recalculate dropdown session
            $_SESSION['cart_dropdown'] = Cart::getItems(); 

            echo json_encode([
                'status' => 'ok',
                'newCount' => Cart::count()
            ]);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }

    // Clear the entire cart
    public function clear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        Cart::clear();

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Show the cart dropdown
    public function showDropdown()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $cartItems = Cart::getItems(); // items include name, price, qty, variants
        require "app/views/components/cartDropdown.php";
    }

    // Show full cart (checkout page)
    public function show()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $cartItems = Cart::getItems(); // includes name, price, qty, width, height, weight

        // Get dimension summary
        $summary = Cart::getDimensionsSummary();

        // Save summary to session for USPS or other services
        $_SESSION['cart_summary'] = [
            'total_weight' => $summary['total_weight'],
            'total_length' => $summary['total_length'],
            'max_width'    => $summary['max_width'],
            'max_height'   => $summary['max_height'],
        ];

        require "app/views/checkout.php";
    }

    // Return total number of items in the cart
    public function count()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return Cart::count();
    }

    // USPS rate calculation
    public function rate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');

        try {
            $rawData = file_get_contents('php://input');
            $data = json_decode($rawData, true);

            $destinationZip = trim($data['zip'] ?? '');
            if (!$destinationZip) {
                http_response_code(400);
                throw new \InvalidArgumentException('Destination ZIP required');
            }

            $summary = Cart::getDimensionsSummary();

            $weight = (float)$summary['total_weight'];
            $length = (float)$summary['total_length'];
            $width  = (float)$summary['max_width'];
            $height = (float)$summary['max_height'];

            $fromZip = $_ENV['SHIP_FROM_ZIP'];

            // Get USPS OAuth token
            $oauthToken = UspsService::getOAuthToken();
            if (!$oauthToken) throw new \RuntimeException('Could not get USPS token');

            // Get real rate
            $rateResponse = UspsService::getRate($oauthToken, $fromZip, $destinationZip, $weight, $length, $width, $height);
            $price = $rateResponse['rates'][0]['price'] ?? $rateResponse['totalBasePrice'] ?? 0;

            // Get delivery estimate
            $deliveryResponse = UspsService::getDeliveryEstimate($oauthToken, $fromZip, $destinationZip);
            $deliveryTime = $deliveryResponse['deliveryTimeEstimates'][0]['estimatedDeliveryDate'] ?? 'N/A';

            // Get city/state info
            $originInfo = UspsService::getCityStateUsps($fromZip, $oauthToken);
            $destInfo   = UspsService::getCityStateUsps($destinationZip, $oauthToken);

            echo json_encode([
                'price' => $price,
                'delivery_time' => $deliveryTime,
                'weight' => $weight,
                'origin' => [
                    'zipCode' => $fromZip,
                    'city'    => $originInfo['city'] ?? null,
                    'state'   => $originInfo['state'] ?? null
                ],
                'destination' => [
                    'zipCode' => $destinationZip,
                    'city'    => $destInfo['city'] ?? null,
                    'state'   => $destInfo['state'] ?? null
                ]
            ], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
        }
    }
    
    public function getCartModel(): \App\Models\Cart
    {
        return $this->cart;
    }
    
}