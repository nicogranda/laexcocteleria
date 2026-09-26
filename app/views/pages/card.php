<section class="hero-product">
    <img src="/images/heros/portfolio_desk.png" alt="Hero">
</section>

<?php
// Asegurarse de que $baseUrl esté definido
if (!isset($baseUrl)) {
    $baseUrl = rtrim($_ENV['APP_URL']);
}

// Producto y variantes
$product = $productCard[0] ?? null;
$variants = $productCard ?: [];
$category = $category ?? ['name' => 'Producto', 'slug' => 'product'];

// Determinar si hay stock en la primera variante

$hasStock = isset($variants[0]['stock']) && (int)$variants[0]['stock'] > 0;
?>

<h1 class="principal"><?= htmlspecialchars($product['name'] ?? 'Producto') ?></h1>

<div class="container-product">
    <div class="card" data-variant-id="<?= $variants[0]['variant_id'] ?? 0 ?>">
        <div class="product-grid">

            <!-- Columna izquierda: imágenes -->
            <div class="image-section">
                <div class="main-image zoom-container">
                    <img id="mainProductImage"
                         src="<?= $baseUrl ?>/products/<?= htmlspecialchars($product['image_path'] ?? 'placeholder.png') ?>"
                         alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="zoom-lens"></div>
                </div>

                <div class="thumbnail-row">
                    <?php foreach ($variants as $v): ?>
                        <?php if (!empty($v['image_path'])): ?>
                        <img class="thumbnail"
                             src="<?= $baseUrl ?>/products/<?= htmlspecialchars($v['image_path']) ?>"
                             alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna derecha: info y acciones -->
            <div class="info-section">
                <div class="product_id" data-product-id="<?= $variants[0]['product_id'] ?? 0 ?>">
                    <p style='font-size:12px; color:gray'><?= 'ID: 000000' . ($variants[0]['product_id'] ?? 0) ?></p>
                </div>

                <!-- Descripción corta -->
                <p class="short-description"><?= htmlspecialchars($product['short_description'] ?? '') ?></p>

                <!-- Descripción larga -->
                <p class="long-description"><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p>

                <!-- Color -->
                <?php
                $colorVariants = array_filter($variants, fn($v) => !empty($v['color']));
                $colors = array_unique(array_map(fn($v) => $v['color'], $colorVariants));
                ?>
                <?php if (count($colors) > 1): ?>
                    <label for="variantColor">Color:</label>
                    <select id="variantColor" name="variantColor">
                        <?php foreach ($colors as $color): ?>
                            <option value="<?= htmlspecialchars($color) ?>"><?= htmlspecialchars($color) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif (count($colors) === 1): ?>
                    <p><strong>Color:</strong> <?= htmlspecialchars($colors[0]) ?></p>
                <?php endif; ?>

                <!-- Stock -->
                <div class="stock" data-stock="<?= (int)($variants[0]['stock'] ?? 0) ?>">
                    <?php if ($hasStock): ?>
                        <p style="font-size:12px; color:green"><?= (int)$variants[0]['stock'] ?> disponibles</p>
                    <?php else: ?>
                        <p style="font-size:12px; color:red">Sin stock</p>
                    <?php endif; ?>
                </div>

                <!-- Precio -->
                <?php if (!empty($variants[0]['price'])): ?>
                    <div class="price" data-price="<?= $variants[0]['price'] ?>">
                        <?= '$' . number_format($variants[0]['price'], 2); ?>
                    </div>
                <?php endif; ?>

                <!-- Botón de carrito: solo si hay stock -->
                <?php 
                if ($hasStock) {
                    $cart_file = __DIR__ . '/../components/CartButton.php';
                    if (file_exists($cart_file)) include $cart_file;
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

<!-- JS: cambio de imagen principal -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('mouseover', () => {
            mainImage.src = thumbnail.src;
        });
    });
});
</script>

<!--Vie item ->
<?php if (!empty($product) && !empty($variants[0]['price'])): ?>
<script>
gtag('event', 'view_item', {
  currency: 'USD',
  value: <?= (float)$variants[0]['price'] ?>,
  items: [{
    item_id: '<?= $variants[0]['product_id'] ?>',
    item_name: '<?= addslashes($product['name']) ?>',
    price: <?= (float)$variants[0]['price'] ?>
  }]
});
</script>
<?php endif; ?>
<style>
/* Hero */
.hero-product {
  height: 40vh;
  width: 100%;
  overflow: hidden;
  position: relative;
}
.hero-product img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
}

/* Layout general */
.product-grid {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.image-section { flex: 1 1 45%; }
.info-section { flex: 1 1 45%; display: flex; flex-direction: column; }

/* Thumbnails */
.thumbnail-row { display: flex; gap: 10px; margin-top: 10px; }
.thumbnail { width: 60px; height: 60px; object-fit: cover; cursor: pointer; border:1px solid #ccc; border-radius:5px; }

/* Nombre y precio */
.product-name { font-size: 24px; margin-bottom: 10px; }
.price { font-size: 20px; font-weight: bold; margin: 15px 0; color: black; }

/* MOBILE FIRST */
@media (max-width: 768px) {
    .product-grid { flex-direction: column; gap: 15px; }
    .image-section, .info-section { width: 100%; }
    .main-image img { width: 100%; height: auto; }
    .thumbnail-row { overflow-x: auto; }
}
</style>