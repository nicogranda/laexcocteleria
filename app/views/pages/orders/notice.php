<?php
// app/views/orders/notice.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tomar los datos de la session
$noticeData = $_SESSION['notice_data'] ?? [];

// Variables esperadas (todas opcionales, nunca asumir)
$orderId        = $noticeData['orderId']        ?? null;
$trackingNumber = $noticeData['trackingNumber'] ?? null;
$uspsPayload    = $noticeData['uspsPayload']    ?? [];
$orderDetails   = $noticeData['orderDetails']   ?? [];
$oauthToken     = $noticeData['oauthToken']     ?? null;
$paymentToken   = $noticeData['paymentToken']   ?? null;
$to             = $noticeData['email']          ?? '';

// Opcional: limpiar la session para que no se repitan los datos al refrescar
unset($_SESSION['notice_data']);
?>

<section class="hero-portfolio">
    <img src="/images/heros/portfolio_desk.png" alt="Notice">
</section>

<h1 class="principal">Notice</h1>

<main class="container">

    <p>
        We have received your order and payment.<br>
        Confirmation sent to: <strong><?= htmlspecialchars($to) ?></strong>
    </p>

    <h2>Shipping Label Status</h2>
    <?php if ($trackingNumber): ?>
        <p>
            ✅ Label generated<br>
            <strong>Tracking:</strong> <?= htmlspecialchars($trackingNumber) ?>
        </p>
    <?php else: ?>
        <p>Shipping Label Not Generated</p>
    <?php endif; ?>

    <a href="/index.php">Return to Home</a>
</main>
<?php if ($orderId && !empty($orderDetails)): ?>

<script>
window.dataLayer = window.dataLayer || [];

dataLayer.push({
  event: "purchase",
  ecommerce: {
    transaction_id: "<?= htmlspecialchars($orderId) ?>",
    value: <?= array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $orderDetails)) ?>,
    currency: "USD",
    items: [
      <?php foreach ($orderDetails as $item): ?>
      {
        item_name: "<?= htmlspecialchars($item['product_name'] ?? 'Product') ?>",
        quantity: <?= (int) ($item['qty'] ?? 1) ?>,
        price: <?= (float) ($item['price'] ?? 0) ?>
      },
      <?php endforeach; ?>
    ]
  }
});
</script>

<?php endif; ?>