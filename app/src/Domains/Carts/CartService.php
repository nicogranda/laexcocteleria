<?php
session_start();
header('Content-Type: application/json');

// Validar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);
    exit;
}

// Incluir modelos
require_once __DIR__ . '/../src/Domains/Carts/Cart.php';
require_once __DIR__ . '/../src/Domains/Products/Product.php';

$productId = intval($_POST['product_id'] ?? 0);
$variantId = intval($_POST['variant_id'] ?? 0);
$quantity  = intval($_POST['quantity'] ?? 1);

$productModel = new Product();
$product = $productModel->getProductWithVariants($productId);

if (!$product) {
    echo json_encode(['status'=>'error','message'=>'Product not found']);
    exit;
}

// Agregar al carrito
require_once __DIR__ . '/../src/Domains/Carts/Cart.php';
$result = Cart::add($productId, $quantity, $variantId);

// Obtener dropdown actualizado
ob_start();
require __DIR__ . '/../views/components/MiniCartDropdown.php';
$dropdownHtml = ob_get_clean();

echo json_encode([
    'status'=>'ok',
    'message'=>$result['message'] ?? 'Added to cart',
    'cartCount'=>Cart::count(),
    'dropdownHtml'=>$dropdownHtml
]);
exit;