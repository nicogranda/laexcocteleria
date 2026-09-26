<!-- CheckoutButton -->
<?php if (!empty($cartItems)): ?>
  <div class="cart-actions">
    <button id="checkoutBtn" class="openCouponBtn">Proceed to Payment</button>
  </div>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const checkoutBtn = document.getElementById("checkoutBtn");

  // Instancia del snippet si no está creada
  const customerInfo = window.customerInfoSnippet || new CustomerInfoSnippet({
    onValidChange: (valid) => {
      checkoutBtn.disabled = !valid;
    }
  });

  checkoutBtn.addEventListener("click", (e) => {
    e.preventDefault();

    // Validar todo primero
    if (!customerInfo.validateAll()) return;

    // Validaciones extra: si shipping > 0, zipcode requerido
    const shippingPrice = parseFloat(document.getElementById('shipping_price_input')?.value || 0);
    const zip = customerInfo.zipInput.value.trim();
    let zipWarning = document.getElementById("zip-warning");
    if (!zipWarning) {
      zipWarning = document.createElement("div");
      zipWarning.id = "zip-warning";
      zipWarning.className = "input-error";
      zipWarning.style.color = "red";
      document.getElementById("customer-info-snippet").appendChild(zipWarning);
    }

    if (shippingPrice > 0 && zip === "") {
      zipWarning.textContent = "Zip code is required for delivery.";
      customerInfo.zipInput.focus();
      return;
    } else {
      zipWarning.textContent = "";
    }

    // Chequear que total exista y sea mayor a 0
    const totalRow = document.getElementById("new-total-row");
    const total = parseFloat(document.getElementById("new-total")?.textContent || 0);
    if (!totalRow || totalRow.style.display === "none" || total <= 0) {
      alert("Total is not calculated yet. Cannot proceed to checkout.");
      return;
    }


    // Crear formulario oculto
    const form = document.createElement("form");
    form.method = "POST";
    // form.action = "api/Stripe/checkout.php";
    form.action = "index.php?page=payment&action=create";

    const data = customerInfo.getData();

    console.log("Datos que se van a enviar al backend:", data);
    data.amount = total;
    data.shipping_price = shippingPrice;
    data.coupon_code = document.getElementById("coupon_code_input")?.value.trim() || "";
    data.discount_amount = document.getElementById("discount_amount_input")?.value.trim() || 0;

    for (const [key, value] of Object.entries(data)) {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = key;
      input.value = value;
      form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
  });
});
</script>
