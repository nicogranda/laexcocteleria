<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$items = $_SESSION['cart_items'] ?? [];
$cartCount = array_sum(array_column($items, 'quantity'));
?>
<div class="mini-cart">
    <button class="mini-cart-btn" aria-label="Shopping bag">
        <i class="fa-solid fa-bag-shopping nav-icon"></i>
        <!-- Siempre renderizar, pero ocultar si es 0 -->
        <span class="mini-cart-badge <?= $cartCount === 0 ? 'hidden' : '' ?>"><?= $cartCount ?></span>
    </button>
    <div class="mini-cart-dropdown">
        <?php include "../app/views/components/MiniCartDropdown.php"; ?>
    </div>
</div>
<style>
/* CONTENEDOR */
.mini-cart {
    position: relative;
    margin-left: 20px;
}
/* BOT�0�7N */
.mini-cart-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    position: relative;
}
/* ICONO */
.mini-cart-btn i {
    font-size: 1.4rem;
    color: #fff;
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.mini-cart-btn:hover i {
    opacity: 0.7;
    transform: scale(1.05);
}
/* BADGE */
.mini-cart-badge {
    position: absolute;
    top: -6px;
    right: -10px;
    background: green;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 999px;
    line-height: 1;
    transition: opacity 0.2s ease;
}
/* Ocultar badge cuando est�� vac��o */
.mini-cart-badge.hidden {
    opacity: 0;
    visibility: hidden;
}
/* DROPDOWN */
.mini-cart-dropdown {
    position: absolute;
    top: 140%;
    right: 0;
    width: 320px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 15px 40px rgba(0,0,0,.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.25s ease;
    z-index: 9999;
}
/* SHOW ON HOVER */
.mini-cart:hover .mini-cart-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
</style>