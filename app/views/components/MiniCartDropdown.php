<div class="cart-container">

    <?php include "../app/views/components/CartItemsSnippet.php"; ?>

    <?php if (!empty($cartItems)): ?>
        <button class="mini-cart-btn-submit" onclick="goToCheckout()">Checkout</button>
    <?php endif; ?>

</div>

<script>
function goToCheckout() {
    window.location.href = '<?= $baseUrl ?>/index.php?page=checkout&action=show';
}

</script>

<style>
/* Contenedor del carrito */
.cart-container {
    width: fit-content;       /* ajusta al contenido, que será la tabla */
    margin: 0 auto;           /* centra horizontalmente */
    padding: 20px;            /* un poco de espacio interno */
    background-color: #f9f9f9; /* o el color que quieras detrás del contenido */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    text-align: center;       /* centra el botón dentro */
}

/* Botón Checkout centrado */
.mini-cart-btn-submit {
    margin: 20px auto 0 auto; /* arriba y abajo un poco de espacio, centrado */
    display: inline-block;
}

/* Ajuste tabla dentro del contenedor */
.cart-table {
    width: 100%; 
    /*max-width: 600px; */
    margin: 0 auto 20px auto; 
}

/* Tabla del carrito */
/*.cart-table {*/
/*    width: 100%;*/
/*    border-collapse: collapse;*/
/*    margin: 0 auto;*/
/*}*/

.cart-table th,
.cart-table td {
    padding: 12px 15px;
    text-align: center;
}

.cart-table th {
    background-color: var(--color-primary);
    color: #fff;
    font-size: 16px;
    text-transform: uppercase;
}

.cart-table tr {
    background: #fff;
}

/*.cart-table tr:nth-child(even) {*/
/*    background-color: #f9f9f9;*/
/*}*/

.cart-table tr:hover {
    background-color: var(--color-secondary);
    color: #fff;
    transition: all 0.3s ease;
}


.cart-table td {
    border-bottom: 1px solid #ddd;
}

/* Botón eliminar */
.btn-delete {
    background-color: var(--color-secondary);
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s ease;
}

.btn-delete:hover {
    background-color: var(--color-primary);
}

/* Mensaje carrito vacío */
.cart-empty {
    text-align: center;
    font-size: 18px;
    color: #555;
    margin: 20px 0;
}

/* Total del carrito */
.cart-total {
    font-weight: bold;
    font-size: 18px;
    text-align: right;
    margin-top: 15px;
}

/* Responsive: en móviles que sea scroll horizontal */
@media (max-width: 768px) {
    .cart-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .cart-table th,
    .cart-table td {
        padding: 10px 8px;
        font-size: 14px;
    }

    .cart-container {
        padding: 15px;
    }
}

/* Botón mini-cart */
.mini-cart-btn-submit {
    background-color: var(--color-primary);
    color: #fff;
    font-family: 'grotta-trialmedium', Helvetica, sans-serif;
    font-size: 16px;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    display: inline-block;
    margin: 0 auto;
    padding: 10px;
}

/* Hover / efecto al pasar el mouse */
.mini-cart-btn-submit:hover {
    background-color: var(--color-secondary);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

/* Responsive: tamaño más pequeño en móviles */
@media (max-width: 768px) {
    .mini-cart-btn-submit {
        font-size: 14px;
        padding: 8px 16px;
    }
}

    
</style>
