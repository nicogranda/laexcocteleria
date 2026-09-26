<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$cartItems = $_SESSION['cart_items'] ?? [];
$total = 0;

foreach ($cartItems as $item) {
    $price = !empty($item['variants'][0]['price']) 
        ? (float)$item['variants'][0]['price'] 
        : (float)($item['price'] ?? 0);
    $total += $price * $item['quantity'];
}
?>

<?php if (!empty($cartItems)): ?>
    <table class="cart-table">
        <thead>
            <tr class='cart-header'>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $id => $item): ?>
                <tr data-key="<?= htmlspecialchars($id, ENT_QUOTES) ?>"> 
                    <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['price'] ?? 0, 2) ?></td>
                    <td>$<?= number_format(($item['price'] ?? 0) * $item['quantity'], 2) ?></td>
                    <td>
                        <button
                          type="button"
                          class="btn-delete"
                          onclick="removeFromCart('<?= htmlspecialchars($id, ENT_QUOTES) ?>')">
                          x
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="cart-empty">Your cart is empty.</p>
<?php endif; ?>