<!-- SHIPPING CALC -->
<div id="shipping-calc" style="display: none; margin-top: 15px;">
  <table style="width:100%; border-top: 1px solid #ddd; margin-top: 10px;">
    <tbody>
      <tr class="cart-row">
        <td style="text-align:left;">Shipping Price</td>
        <td colspan="3"></td>
        <td id="shipping-price" style="text-align:right;">0.00</td>
      </tr>
      <tr class="cart-row">
        <td style="text-align:left;">Estimated Delivery</td>
        <td colspan="3"></td>
        <td id="delivery-time" style="text-align:right;">N/A</td>
      </tr>
      <tr class="cart-row" style="font-weight:bold;display:none;">
        <td style="text-align:left;">Total</td>
        <td colspan="3"></td>
        <td id="cart-total" style="text-align:right;">0.00</td>
      </tr>
    </tbody>
  </table>
</div>
<!-- Inputs ocultos para enviar al checkout -->
<input type="hidden" id="coupon_code_input" name="coupon_code">
<input type="hidden" id="discount_amount_input" name="discount_amount">
<input type="hidden" id="shipping_price_input" name="shipping_price">


<script>
/* ===================================================
   ShippingCalc Component
   =================================================== */
class ShippingCalc {
  constructor(containerId) {
    this.el = document.getElementById(containerId);
    this.priceEl = this.el.querySelector("#shipping-price");
    this.deliveryEl = this.el.querySelector("#delivery-time");
    this.totalEl = this.el.querySelector("#cart-total");
    this.locationInfo = document.getElementById("location-info");
    this.currentShippingPrice = 0;
  }

  show() {
    this.el.style.display = "block";
  }

  update({ price = 0, delivery = "N/A", city = "", state = "" }) {
    this.priceEl.textContent = price.toFixed(2);
    this.deliveryEl.textContent = delivery;
    this.show();

    if (city && state) {
        this.locationInfo.textContent = `${city} | ${state}`;
        this.locationInfo.style.display = "block";
    }

    const currentTotal = parseFloat(this.totalEl.textContent) || 0;
    this.totalEl.textContent = (
        currentTotal - this.currentShippingPrice + price
    ).toFixed(2);

    this.currentShippingPrice = price;

    // --- NUEVO: llenar input oculto para checkout ---
    const shippingInputHidden = document.getElementById('shipping_price_input');
    if(shippingInputHidden) shippingInputHidden.value = price.toFixed(2);

    // 🧮 Actualizar total global
    if (typeof recalcGlobalTotal === "function") {
        recalcGlobalTotal();
    }
}

}

/* ===================================================
   Inicialización de estilos
   =================================================== */
document.addEventListener("DOMContentLoaded", () => {
  const deliveryRow = document.querySelector("#shipping-calc tr.cart-row:nth-child(2)");
  if (deliveryRow) {
    deliveryRow.style.background = "transparent";
    deliveryRow.style.fontSize = "12px";
  }
});

/* ===================================================
   Función global de recalculo total
   =================================================== */
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
        document
          .getElementById("discount-amount")
          ?.textContent.replace("-", "")
      ) || 0;
    finalTotal = baseTotal - discountAmount + shipping;
  }

  // Actualiza el componente CartTotal si existe
  if (window.cartTotalComponent && typeof window.cartTotalComponent.update === "function") {
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
