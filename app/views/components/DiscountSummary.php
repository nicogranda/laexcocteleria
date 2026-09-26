<div id="discount-summary">
  <div id="discount-display"></div>

  <div id="discount-row" class="cart-row" style="display:none;">
    <div>Coupon discount</div>
    <div id="discount-amount" style="text-align:right; color: green;">-0.00</div>
  </div>
</div>

<!-- Inputs ocultos para enviar al checkout -->
<input type="hidden" id="coupon_code_input" name="coupon_code">
<input type="hidden" id="discount_amount_input" name="discount_amount">


<script>
class DiscountSummary {
  constructor() {
    this.row = document.getElementById("discount-row");
    this.amountEl = document.getElementById("discount-amount");
    this.newTotalRow = document.getElementById("new-total-row");
    this.newTotalEl = document.getElementById("new-total");
    this.cartTotalEl = document.getElementById("cart-total");
  }

  apply(discount, totalNew) {
    if (!this.cartTotalEl) return;

    this.amountEl.textContent = `-${discount.toFixed(2)}`;
    this.newTotalEl.textContent = `${totalNew.toFixed(2)}`;

    this.row.style.display = "flex";
    this.newTotalRow.style.display = "flex";
  }

  reset() {
    this.row.style.display = "none";
    this.newTotalRow.style.display = "none";
  }
}
</script>

<style>
#discount-summary {
  margin-top: 10px;
  width: 100%;
}

#discount-summary .cart-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-top: 1px solid #eee;
}

#discount-summary .cart-row div:first-child {
  font-weight: 500;
}
</style>
