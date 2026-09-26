<!-- app/views/components/CustomerInfoSnippet.php -->
<div id="customer-info-snippet" class="customer-info-snippet">
  <h3>Customer Info</h3>


  <!-- Country select -->
  <div class="form-group">
    <label for="country">Country</label>
    <select id="country" name="country" disabled>
      <option value="US">United States</option>
    </select>
  </div>

  <!-- Email -->
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" placeholder="Email" required>
    <div id="email-error" class="input-error"></div>
  </div>

  <!-- Full Name -->
  <div class="form-group">
    <label for="username">Full Name</label>
    <input type="text" id="username" name="name" placeholder="Full Name" required>
    <div id="username-error" class="input-error"></div>
  </div>

  <!-- Button for Delivery/Pickup -->
  <?php include "app/views/components/OrderTypeButtonGroup.php"; ?>

  <!-- ZIP Code Snippet -->
  <?php include "app/views/components/ZipCodeSnippet.php"; ?>

  <!-- Address -->
  <div class="form-group">
    <label for="useraddress">Address</label>
    <input type="text" id="useraddress" placeholder="Street Address" required>
    <input type="text" id="usersecondary_address" name="secondary_address" placeholder="Apt, Suite, Unit, etc.">
    <div id="address-error" class="input-error"></div>
  </div>

  <!-- Hidden fields para enviar city y state al backend -->
  <input type="hidden" id="city" name="city">
  <input type="hidden" id="state" name="state">
  <input type="hidden" id="zipcode" name="zipcode">

  <!-- Coupon modal (si aplica) -->
  <?php include "app/views/components/CouponButton.php"; ?>
</div>


<script>
class CustomerInfoSnippet {
  constructor({ onValidChange } = {}) {
    this.emailInput = document.getElementById("email");
    this.usernameInput = document.getElementById("username");
    this.addressInput = document.getElementById("useraddress");
    this.secondaryaddressInput = document.getElementById("usersecondary_address");
 
    this.zipInput = document.getElementById("zip_code"); // coincide con el HTML

    this.orderTypeInputs = document.querySelectorAll('input[name="orderType"]'); // Delivery/Pickup
    this.calculationInput = document.getElementById("calculation"); // si tienes un input oculto para calculo

    this.emailError = document.getElementById("email-error");
    this.usernameError = document.getElementById("username-error");
    this.addressError = document.getElementById("address-error");

    this.onValidChange = onValidChange || (() => {});

    this.restoreFromSession();
    this.bindEvents();
    this.validateAll();
  }

  bindEvents() {
    [this.emailInput, this.usernameInput, this.addressInput, this.zipInput].forEach((input) => {
      input.addEventListener("input", () => {
        this.saveToSession();
        this.validateAll();
      });
    });

    // Guardar cuando cambia el tipo de orden
    this.orderTypeInputs.forEach((input) => {
      input.addEventListener("change", () => this.saveToSession());
    });

    // Guardar cuando cambia el cálculo (si es dinámico)
    if(this.calculationInput){
      this.calculationInput.addEventListener("input", () => this.saveToSession());
    }
  }

  saveToSession() {
    const data = {
      email: this.emailInput.value.trim(),
      name: this.usernameInput.value.trim(),
      address: this.addressInput.value.trim(),
      secondary_address: this.secondaryaddressInput.value.trim(),
      zip: this.zipInput.value.trim(),
      orderType: this.getSelectedOrderType(),
      calculation: this.calculationInput ? this.calculationInput.value : null
    };
    sessionStorage.setItem("customerInfo", JSON.stringify(data));
  }

    restoreFromSession() {
      const saved = sessionStorage.getItem("customerInfo");
      if (saved) {
        const data = JSON.parse(saved);
        if (data.email) this.emailInput.value = data.email;
        if (data.name) this.usernameInput.value = data.name;
        if (data.address) this.addressInput.value = data.address;
        if (data.secondary_address) this.secondaryaddressInput.value = data.secondary_address;
        if (data.zip) this.zipInput.value = data.zip;
        if (data.orderType) this.setOrderType(data.orderType);
        if (data.calculation && this.calculationInput) this.calculationInput.value = data.calculation;
      }
    }

  validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  validateName(name) {
    return name.trim().length >= 3;
  }

  validateAddress(addr) {
    return addr.trim().length >= 5;
  }

  validateAll() {
    let valid = true;

    if (!this.validateEmail(this.emailInput.value)) {
      this.emailError.textContent = "Please enter a valid email.";
      valid = false;
    } else {
      this.emailError.textContent = "";
    }

    if (!this.validateName(this.usernameInput.value)) {
      this.usernameError.textContent = "Name must be at least 3 characters.";
      valid = false;
    } else {
      this.usernameError.textContent = "";
    }

    if (!this.validateAddress(this.addressInput.value)) {
      this.addressError.textContent = "Address too short.";
      valid = false;
    } else {
      this.addressError.textContent = "";
    }

    this.onValidChange(valid);
    return valid;
  }

  getSelectedOrderType() {
    const selected = Array.from(this.orderTypeInputs).find(i => i.checked);
    return selected ? selected.value : null;
  }

  setOrderType(type) {
    this.orderTypeInputs.forEach(i => i.checked = i.value === type);
  }

   getData() {
      return {
        email: this.emailInput.value.trim(),
        name: this.usernameInput.value.trim(),
        address: this.addressInput.value.trim(),
        secondary_address: this.secondaryaddressInput.value.trim(),
        zip: this.zipInput.value.trim(),
        city: document.getElementById('city')?.value || '',
        state: document.getElementById('state')?.value || '',
        country: "US",
        orderType: this.getSelectedOrderType(),
        calculation: this.calculationInput ? this.calculationInput.value : null
      };
    }

}


</script>

<style>
.customer-info-snippet {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.customer-info-snippet h3 {
  margin-bottom: 10px;
}

.customer-info-snippet .form-group {
  display: flex;
  flex-direction: column;
}

.customer-info-snippet input,
.customer-info-snippet select {
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.input-error {
  color: red;
  font-size: 12px;
  margin-top: 4px;
  height: 14px;
}
</style>
