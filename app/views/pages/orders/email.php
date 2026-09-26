<?php
$paddedOrderId = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
$orderStatus = ucfirst($order['status']);
$currentYear = date('Y');

// --- HEAD ---
$head_message = "
<table width='100%' cellpadding='0' cellspacing='0' border='0' style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
  <tr>
    <td align='center'>
      <table width='600' cellpadding='0' cellspacing='0' border='0' style='background-color: #ffffff; border-radius: 8px;'>
        <tr>
          <td align='center' style='padding: 30px 20px;'>
            <img src='https://maletachic.com/images/logo.png' alt='Logo' width='120' style='display: block; margin: 0 auto;' />
          </td>
        </tr>

        <tr>
          <td style='padding: 0 30px 10px 30px; text-align: center;'>
            <h2 style='color: #333;'>Order Summary</h2>
            <p style='color: #555;'>Order <strong>#{$paddedOrderId}</strong></p>
          </td>
        </tr>
";

// --- SHIPPING ADDRESS BLOCK (arriba, antes de la tabla de productos) ---
$shippingName = htmlspecialchars($order['customer_name'] ?? '');
$shippingAddress = htmlspecialchars($order['shipping_address'] ?? '');
$shippingCity  = htmlspecialchars($order['shipping_city'] ?? '');
$shippingState = htmlspecialchars($order['shipping_state'] ?? '');
$shippingZip = htmlspecialchars($order['shipping_zip'] ?? '');

$paymentIntentId = htmlspecialchars($order['payment_intent_id'] ?? 'N/A');
$paymentStatus   = strtoupper($order['status'] ?? 'PAID'); // PAID

$body_message = "
<tr>
  <td style='padding: 0 30px 25px 30px;'>
    <table width='100%' cellpadding='0' cellspacing='0' border='0'>
      <tr>

        <!-- LEFT: Shipping Info -->
        <td valign='top' width='60%' style='color:#555; font-size:14px;'>
          <h3 style='margin:0 0 8px 0; color:#333;'>Shipping Information</h3>
          <p style='margin:0;'>
            <strong>{$shippingName}</strong><br>
            {$shippingAddress}<br>
            {$shippingCity}, {$shippingState} {$shippingZip}<br>
            United States
          </p>
        </td>

        <!-- RIGHT: Payment Info -->
        <td valign='top' width='40%' style='color:#555; font-size:14px; text-align:right;'>
          <h3 style='margin:0 0 8px 0; color:#333;'>Payment</h3>
          <p style='margin:0;'>
            <strong>Status:</strong> {$paymentStatus}<br>
            <strong>Payment ID:</strong><br>
            <span style='font-size:12px; word-break:break-all; color:#777;'>
              {$paymentIntentId}
            </span>
          </p>
        </td>

      </tr>
    </table>
  </td>
</tr>
";

// --- TRACKING NUMBER BLOCK ---
$trackingNumber = $trackingNumber ?? null; // asegúrate que llega desde el controlador

if ($trackingNumber):
$body_message .= "
<tr>
  <td style='padding: 0 30px 15px 30px; text-align: center;'>
    <p style='font-size:14px; color:#555; margin:0 0 5px 0;'>
      Tu pedido ha sido enviado y puedes rastrearlo con este número:
    </p>
    <p style='font-size:16px; color:#333; margin:0;'>
      <strong>
        <a href='https://tools.usps.com/go/TrackConfirmAction?tLabels={$trackingNumber}' target='_blank' style='color:#333; text-decoration:none;'>
          {$trackingNumber}
        </a>
      </strong>
    </p>
  </td>
</tr>
";
endif;



// --- TABLE OF PRODUCTS ---
$body_message .= "
        <tr>
          <td style='padding: 0 30px 20px 30px;'>
            <table width='100%' cellpadding='10' cellspacing='0' border='0' style='border-collapse: collapse; font-size: 14px;'>
              <tr style='background-color: #767c1a; color: #fff;'>
                <th align='left'>Product</th>
                <th align='center'>Quantity</th>
                <th align='center'>Unit Price</th>
                <th align='right'>Subtotal</th>
              </tr>
";

// --- FOREACH PRODUCTS ---
foreach ($orderDetails as $detail) {
    $productName = htmlspecialchars($detail['product_name']);
    $quantity = $detail['quantity'];
    $price = number_format($detail['price'], 2);
    $subtotal = number_format(($quantity * $detail['price']), 2);

    $body_message .= "
              <tr style='background-color: #fdfdfd;'>
                <td>{$productName}</td>
                <td align='center'>{$quantity}</td>
                <td align='center'>{$price}</td>
                <td align='right'>{$subtotal}</td>
              </tr>
    ";
}

// --- SHIPPING, DISCOUNTS AND TOTAL AS ITEMS ---
$formattedShipping = number_format($order['shipping_cost'], 2);
$formattedTotal = number_format($order['total'], 2);
$discountText = $order['discount_code']
    ? htmlspecialchars($order['discount_code']) . " (-" . number_format($order['discount_amount'], 2) . ")"
    : 'N/A';

$body_message .= "
              <tr style='background-color: #f9f9f9;'>
                <td colspan='3' align='right'><strong>Shipping:</strong></td>
                <td align='right'>{$formattedShipping}</td>
              </tr>
              <tr style='background-color: #f9f9f9;'>
                <td colspan='3' align='right'><strong>Discount:</strong></td>
                <td align='right'>{$discountText}</td>
              </tr>
              <tr style='background-color: #f9f9f9;'>
                <td colspan='3' align='right'><strong>Total:</strong></td>
                <td align='right'>{$formattedTotal}</td>
              </tr>
            </table>
          </td>
        </tr>
";

// --- FOOTER ---
$foot_message = "
        <tr>
          <td style='padding: 30px; text-align: center;'>
            <p style='margin-bottom: 10px; color: #999;'>Follow us on social media:</p>
            <a href='https://facebook.com/maletachic' style='margin: 0 10px;'><img src='https://maletachic.com/images/rrss/facebook.png' alt='maletachic' title='maletachic' style='width:20px;'></a>
            <a href='https://instagram.com/maletachic' style='margin: 0 10px;'><img src='https://maletachic.com/images/rrss/instagram.png' alt='maletachic' title='maletachic' style='width:20px;'></a>
          </td>
        </tr>

        <tr>
          <td style='padding: 10px 30px; text-align: center; font-size: 12px; color: #aaa;'>
            &copy; {$currentYear} Maleta Chic. All rights reserved.
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
";

// --- COMBINE HEAD + BODY + FOOT ---
$body = $head_message . $body_message . $foot_message;
echo $body;
?>
