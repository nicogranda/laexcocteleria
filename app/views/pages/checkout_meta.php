<?php
namespace App\Views;

use App\Models\Product;
use App\Models\Cart;

// ------------------------
// 1️⃣ Detectar tráfico Meta
// ------------------------
$cartOrigin = $_GET['cart_origin'] ?? null;
$fromMeta = $cartOrigin && strpos($cartOrigin, 'meta') !== false;
$fbclid = $_GET['fbclid'] ?? null;

// ------------------------
// 2️⃣ Parsear productos de URL
// ------------------------
$productsParam = $_GET['products'] ?? '';
$coupon = $_GET['coupon'] ?? null;

$productItems = [];
if ($productsParam) {
    foreach (explode(',', $productsParam) as $entry) {
        list($slug, $qty) = explode(':', $entry) + [null, 1];
        $qty = max(1, (int)$qty);

        // Buscar producto en DB
        $productModel = new Product();
        $product = $productModel->getProductBySlug($slug);

        if ($product) {
            // Tomar la primera variante si no hay una específica
            $variant = $product['variants'][0] ?? null;
            if (!$variant) continue;

            $productItems[] = [
                'slug'       => $slug,
                'product_id' => $product['id'],
                'variant_id' => $variant['id'],
                'name'       => $product['name'],
                'price'      => $variant['price'],
                'stock'      => $variant['stock'] ?? 0,
                'quantity'   => min($qty, $variant['stock'] ?? $qty) // no pasar stock
            ];

            // ------------------------
            // 3️⃣ Agregar al carrito usando tu método existente
            // ------------------------
            if (session_status() === PHP_SESSION_NONE) session_start();
            $result = Cart::add($product['id'], min($qty, $variant['stock'] ?? $qty), $variant['id']);

            // Actualizar dropdown
            $_SESSION['cart_dropdown'] = Cart::getItems();

            // Guardar mensaje modal
            $_SESSION['item_message'][] = [
                'message' => $result['message'] ?? 'Producto agregado al carrito',
                'product' => [
                    'name'    => $product['name'],
                    'variant' => $variant['attributes'] ?? [],
                    'price'   => $variant['price'],
                    'qty'     => min($qty, $variant['stock'] ?? $qty)
                ]
            ];
        }
    }
}

// ------------------------
// 4️⃣ Calcular total
// ------------------------
$totalValue = array_reduce($productItems, function($sum, $item){
    return $sum + ($item['price'] * $item['quantity']);
}, 0);

?>

<!-- ===========================
     CHECKOUT HTML
=========================== -->
<section class="hero-portfolio">
    <img src="/images/heros/portfolio_desk.png">
</section>

<h1 class="principal">Checkout</h1>

<section id="cart-page">
  <div class="cart-container">
    <!-- Customer Info -->
    <div class="cart-col customer-info">
      <?php include "app/views/components/CustomerInfoSnippet.php"; ?>
    </div>

    <!-- Cart Items -->
    <div class="cart-col cart-list">
      <?php foreach($productItems as $item): ?>
      <div class="cart-item" 
           data-product-id="<?= $item['product_id'] ?>" 
           data-variant-id="<?= $item['variant_id'] ?>" 
           data-product-name="<?= htmlspecialchars($item['name']) ?>" 
           data-product-price="<?= $item['price'] ?>" 
           data-quantity="<?= $item['quantity'] ?>">
           <?= $item['name'] ?> x <?= $item['quantity'] ?> - $<?= number_format($item['price'],2) ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Summary -->
    <div class="cart-col summary">
      <?php include "app/views/components/CartSummary.php"; ?>
      <?php include "app/views/components/ShippingCalc.php"; ?>
      <?php include "app/views/components/DiscountSummary.php"; ?>
      <?php include "app/views/components/CartTotal.php"; ?>
      <?php include "app/views/components/PaymentButton.php"; ?>
    </div>
  </div>
</section>

<!-- ===========================
     PIXEL META DINÁMICO
=========================== -->
<script>
fbq('init', 'TU_PIXEL_ID');
fbq('track', 'PageView');

const fromMeta = <?= $fromMeta ? 'true' : 'false'; ?>;
const totalValue = <?= $totalValue ?>;

if(fromMeta && totalValue > 0){
    const productElements = document.querySelectorAll('.cart-item');
    const contentIds = Array.from(productElements).map(el => el.dataset.productId);

    fbq('track', 'ViewContent', {
        content_ids: contentIds,
        content_type: 'product',
        value: totalValue,
        currency: 'USD'
    });

    fbq('track', 'InitiateCheckout', {
        content_ids: contentIds,
        content_type: 'product',
        value: totalValue,
        currency: 'USD'
    });
}

// AddToCart dinámico
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const form = btn.closest('form');
        const pid = btn.dataset.productId;
        const pname = btn.dataset.productName;
        const price = parseFloat(btn.dataset.productPrice) || 0;

        fbq('track', 'AddToCart', {
            content_ids: [pid],
            content_name: pname,
            content_type: 'product',
            value: price,
            currency: 'USD',
            quantity: 1
        });

        setTimeout(() => form.submit(), 100);
    });
});
</script>
