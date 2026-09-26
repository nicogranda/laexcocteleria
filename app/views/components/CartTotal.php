<!-- CartTotal -->
<div id="new-total-row" class="cart-row" style="display:none;">
  <div>Total</div>
  <div id="new-total" style="text-align:right; color: green;">0.00</div>
</div>

<script>
class CartTotal {
  constructor() {
    this.row = document.getElementById("new-total-row");
    this.valueEl = document.getElementById("new-total");
    this.currentValue = 0;
  }

  update(total) {
    if (isNaN(total)) total = 0;
    this.currentValue = total;
    this.valueEl.textContent = total.toFixed(2);
    this.row.style.display = "flex";
  }

  get value() {
    return this.currentValue;
  }
}

// Instancia global
window.cartTotalComponent = new CartTotal();
</script>
