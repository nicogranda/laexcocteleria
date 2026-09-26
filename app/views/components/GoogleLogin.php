<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Cross-Origin-Opener-Policy: same-origin-allow-popups');
header('Cross-Origin-Embedder-Policy: unsafe-none');
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../../src/Shared/config/env.php';
?>
<main>
  <div id="g_id_onload"
       data-client_id="<?= $_ENV['GOOGLE_CLIENT_ID'] ?>"
       data-callback="handleGoogleCredentialResponse"
       data-auto_prompt="false">
  </div>
  <div class="g_id_signin"
       data-type="standard"
       data-size="large"
       data-theme="outline"
       data-text="sign_in_with"
       data-shape="rectangular"
       data-logo_alignment="left">
  </div>
</main>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
window.handleGoogleCredentialResponse = function(response) {
    console.log("Google Credential recibido:", response.credential);
    if (!response.credential) {
        alert("No se recibi¨® token de Google. Revisa Client ID y origen en Google Cloud Console.");
        return;
    }
    const baseUrl = "<?= $baseUrl ?>"; 
    fetch(`${baseUrl}/index.php?page=auth&action=google`, {
    // fetch(`/index.php/api/Google/login_google.php`, {    
        
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ credential: response.credential })
    })

    .then(res => res.json())
    .then(data => {
        console.log("Respuesta del backend:", data);
        if (data.success) {
            localStorage.setItem('user', JSON.stringify(data.user));
            window.dispatchEvent(new Event('userLogin'));
            if (data.user.role === 'admin') {
                setTimeout(() => { 
                    window.location.assign(`${baseUrl}/index.php?page=admin&action=dashboard`);}, 300);
            } else {
                alert("No tienes permisos para acceder al panel de administraci¨®n.");
                window.location.assign('/');
            }
        } else {
            alert('Login fallido: ' + (data.error || 'error desconocido'));
        }
    })
    .catch(err => {
        console.error("Error fetch backend:", err);
        alert('Error al autenticar con Google.');
    });
};
</script>