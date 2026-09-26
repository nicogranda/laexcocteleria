<section class="hero-portfolio">
    <img src="/images/heros/portfolio_desk.png">
</section>   

<h1 class="principal">Checkout</h1>

<section id="cart-page">
  <div class="cart-container">
    <!--Customer -->
    <div class="cart-col customer-info">
      <?php include "../app/views/components/CustomerInfoSnippet.php"; ?>
    </div>

    <!--Cart -->
    <div class="cart-col cart-list">
      <?php include "../app/views/components/CartItemsSnippet.php"; ?>
    </div>


    <!--Summary -->
    <div class="cart-col summary">
      <?php include "../app/views/components/CartSummary.php"; ?>
      <?php include "../app/views/components/ShippingCalc.php"; ?>
      <?php include "../app/views/components/DiscountSummary.php"; ?>
      <?php include "../app/views/components/CartTotal.php"; ?>
      <?php include "../app/views/components/PaymentButton.php"; ?>
    </div>
  </div>
</section>


<script>

/* ===================================================
   Integración CustomerInfo + ZipCodeSnippet + ShippingCalc
   =================================================== */

// 1️⃣ Instanciamos CustomerInfoSnippet
const customerInfo = new CustomerInfoSnippet();

// 2️⃣ Instanciamos ShippingCalc (gestiona totales)
const shippingComponent = new ShippingCalc("shipping-calc");

// 3️⃣ Instanciamos ZipCodeSnippet con callback
const zipSnippet = new ZipCodeSnippet({
  endpoint: "api/USPS/rate.php",
  onValidated: ({ zip, city, state, delivery_time, price }) => {
    shippingComponent.update({
      price,
      delivery: delivery_time,
      city,
      state
    });
    recalcGlobalTotal(); // recalcular total automáticamente
  }
});

// 4️⃣ Si CustomerInfo ya tiene ZIP, activamos el cálculo automático
const savedData = customerInfo.getData();
if (savedData.zip && savedData.zip.trim() !== "") {
  console.log("ZIP detectado desde CustomerInfo:", savedData.zip, "- activando cálculo de envío");
  if (typeof zipSnippet.validateZip === "function") {
    zipSnippet.validateZip(savedData.zip.trim());
  }
}

// 5️⃣ Función para recalcular total global (ya la tenías)
function recalcGlobalTotal() {
  const baseTotal =
    parseFloat(document.getElementById("cart-total")?.textContent) || 0;

  const shipping =
    parseFloat(document.getElementById("shipping-price")?.textContent) || 0;

  const discountRow = document.getElementById("discount-row");
  const discountActive = discountRow && discountRow.style.display !== "none";

  let finalTotal = baseTotal + shipping;

  if (discountActive) {
    const discountAmount =
      parseFloat(
        document.getElementById("discount-amount")?.textContent.replace("-", "")
      ) || 0;
    finalTotal = baseTotal - discountAmount + shipping;
  }

  if (window.cartTotalComponent instanceof CartTotal) {
    window.cartTotalComponent.update(finalTotal);
  } else {
    const newTotalEl = document.getElementById("new-total");
    const newTotalRow = document.getElementById("new-total-row");
    if (newTotalEl && newTotalRow) {
      newTotalEl.textContent = finalTotal.toFixed(2);
      newTotalRow.style.display = "flex";
    }
  }

  return finalTotal;
}
</script>

<!--begin_checkout-->
<script>
if (typeof gtag !== 'undefined') {
  gtag('event', 'begin_checkout', {
    currency: 'USD',
    value: total,
    items: [] // idealmente meter productos del carrito
  });
}
</script>

<style>
/* Estructura general */
#cart-page {
  width: 100%;
  display: flex;
  justify-content: center;
}

.cart-container {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  justify-content: space-between;
  align-items: flex-start;
  width: 100%;
  max-width: 1200px;
  gap: 20px;
  padding: 10px;
}

/* Columnas */
.cart-col {
  /* background: #fff; */
  border: 1px solid #e8e8e8;
  border-radius: 6px;
  padding: 20px;
  flex: 1;
  min-width: 280px;
}

/* Ajustes por tipo */
.customer-info {
  flex: 1;
}

.cart-list {
  flex: 2;
}

.summary {
  flex: 1;
}

/* Estilo global para todas las filas de resumen */
.cart-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-top: 1px solid #eee;
}

.cart-row div:first-child {
  font-weight: 500;
}

.cart-row div:last-child {
  text-align: right;
}


/* Responsive */
@media (max-width: 900px) {
  .cart-container {
    flex-wrap: wrap;
  }
  .cart-col {
    flex: 1 1 100%;
  }
}


</style>
