<section class="container">
    <h1 class="principal">Mensaje enviado</h1>
    <p>Gracias por contactar con La Ex Coctelería. Hemos recibido tu mensaje correctamente.</p>
    <a href="/es/">Volver al inicio</a>
</section>

<?php if (!empty($_SESSION['contact_conversion'])): ?>
<script>
if (typeof fbq === 'function') {
    fbq('track', 'Lead');
}
</script>
<?php unset($_SESSION['contact_conversion']); endif; ?>