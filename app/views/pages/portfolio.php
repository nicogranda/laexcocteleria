<section class="hero-portfolio" style="margin: 0 0 40px 0;">
    <img src="images/categories/<?= $category['image'] ?>" alt="<?= $category['image'] ?>">
</section>    

<h1 class="principal"><?= htmlspecialchars($category['name']) ?></h1>
<div class="container">
<section class="categories">
<?php if (!empty($products) && is_array($products)): ?>
    <?php foreach ($products as $product): ?>
        <div class="product-item">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <div class='items'>
                <div class='item-shot-description'>
                    <p class="short-description"><?= htmlspecialchars($product['short_description'] ?? '') ?></p>
                </div>
                <div class='item-price'>
                    <p><?= number_format($product['price'] ?? 0, 2) ?> €</p>
                </div>    
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No hay productos disponibles en esta categoría.</p>
<?php endif; ?>
</section>
</div>
<style>
    
.items {
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: space-between;
	align-items: center;
	align-content: stretch;
}

.item-shot-description {
    width: 75%;
}
.item-price {
    
}


.product-item {
    display: flex;
    flex-direction: column;
    padding: 12px;
}

.product-item h2 {
    font-size: 16px !important;
    text-align: left !important; /* override inline style */
    margin: 0;
}

.product-item .product-id {
    display: flex;
    align-items: right;
    gap: 12px;
}

/* Nombre y precio en la misma línea */
.product-item h2,
.product-item .product-id p {
    display: inline;
    margin: 0;
    padding: 0 !important;
}

.product-header {
    display: flex;
    align-items: baseline;
    gap: 12px;
    flex-wrap: wrap;
}

.product-item .short-description {
    margin-top: 6px;
    font-size: 0.9em;
    color: #666;
}
</style>

