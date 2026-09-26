<style>
.contact-form {
  max-width: 420px;
  margin: 0 auto;
}

.contact-form .form-group {
  margin-bottom: 18px;
}

.contact-form label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
}

.contact-form input,
.contact-form textarea {
  width: 100%;
  padding: 11px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
  box-sizing: border-box;
  font-family: inherit;
  outline: none;
  transition: border-color .2s ease;
}

.contact-form input:focus,
.contact-form textarea:focus {
  border-color: var(--color-primary);
}

.contact-form textarea {
  resize: vertical;
}

.error-message {
  display: none;
  color: #c00;
  font-size: 13px;
  margin-top: 5px;
}

.recaptcha-wrapper {
  margin: 20px 0;
  text-align: center;
}

.privacy-group {
  margin: 20px 0;
}

.privacy-label {
  display: flex !important;
  align-items: flex-start;
  gap: 9px;
  font-weight: 400 !important;
  line-height: 1.5;
  cursor: pointer;
}

.privacy-label input {
  width: auto;
  margin: 4px 0 0;
  flex: 0 0 auto;
}

.privacy-link {
  padding: 0;
  border: 0;
  background: transparent;
  color: var(--color-primary);
  font: inherit;
  text-decoration: underline;
  cursor: pointer;
}

.btn-submit {
  width: 100%;
  padding: 13px 20px;
  border: 1px solid var(--color-primary);
  border-radius: 6px;
  background: var(--color-primary);
  color: #fff;
  cursor: pointer;
  font-size: 15px;
  font-family: inherit;
  transition: background .2s ease, color .2s ease, border-color .2s ease;
}

.btn-submit:hover,
.btn-submit:focus {
  background: #fff !important;
  color: var(--color-primary) !important;
  border-color: var(--color-primary);
  outline: none;
}

.form-success {
  margin-top: 15px;
  color: green;
}

.form-error {
  margin-top: 15px;
  color: #c00;
}
</style>

<section class="hero"></section>

<section class="container">
  <h1 class="principal">Contacto</h1>

  <form class="contact-form" action="/es/contacto/enviar" method="post" onsubmit="return validateContactForm();">

    <div class="form-group">
      <label for="name">Nombre</label>
      <input type="text" id="name" name="name" value="<?= htmlspecialchars($_SESSION['contact_old']['name'] ?? '') ?>">
      <span id="nameError" class="error-message">Este campo es obligatorio.</span>
    </div>

    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['contact_old']['email'] ?? '') ?>">
      <span id="emailError" class="error-message">Introduce un correo válido.</span>
    </div>

    <div class="form-group">
      <label for="mobile">Móvil</label>
      <input type="tel" id="mobile" name="mobile" value="<?= htmlspecialchars($_SESSION['contact_old']['mobile'] ?? '') ?>">
      <span id="mobileError" class="error-message">Introduce tu número de móvil.</span>
    </div>

    <div class="form-group">
      <label for="message">Mensaje</label>
      <textarea id="message" name="message" rows="4"><?= htmlspecialchars($_SESSION['contact_old']['message'] ?? '') ?></textarea>
      <span id="messageError" class="error-message">Este campo es obligatorio.</span>
    </div>

    <div class="privacy-group">
      <label class="privacy-label" for="privacy">
        <input type="checkbox" id="privacy" name="privacy" value="1">
        <span>Acepto la <button type="button" class="privacy-link" onclick="openPrivacyModal()">política de protección de datos</button>.</span>
      </label>
      <span id="privacyError" class="error-message">Debes aceptar la política de protección de datos.</span>
    </div>

    <div class="recaptcha-wrapper">
      <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY'] ?? '') ?>"></div>
      <span id="captchaError" class="error-message">Confirma que no eres un robot.</span>
    </div>

    <button type="submit" class="btn-submit">Enviar</button>

    <?php if (!empty($_SESSION['contact_error'])): ?>
      <p class="form-error"><?= htmlspecialchars($_SESSION['contact_error']) ?></p>
      <?php unset($_SESSION['contact_error']); ?>
    <?php endif; ?>

  </form>
</section>

<script>
function validateContactForm() {
    let isValid = true;

    document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const mobile = document.getElementById('mobile').value.trim();
    const message = document.getElementById('message').value.trim();
    const privacy = document.getElementById('privacy').checked;
    const emailRegex = /^\S+@\S+\.\S+$/;

    if (name === '') {
        document.getElementById('nameError').style.display = 'block';
        isValid = false;
    }

    if (email === '' || !emailRegex.test(email)) {
        document.getElementById('emailError').style.display = 'block';
        isValid = false;
    }

    if (mobile === '') {
        document.getElementById('mobileError').style.display = 'block';
        isValid = false;
    }

    if (message === '') {
        document.getElementById('messageError').style.display = 'block';
        isValid = false;
    }

    if (!privacy) {
        document.getElementById('privacyError').style.display = 'block';
        isValid = false;
    }

    if (typeof grecaptcha !== 'undefined' && grecaptcha.getResponse().length === 0) {
        document.getElementById('captchaError').style.display = 'block';
        isValid = false;
    }

    return isValid;
}
</script>