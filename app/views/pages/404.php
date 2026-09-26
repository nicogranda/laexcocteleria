<main>
<div class="error-page">
    <div class="content">
        <h1>404</h1>
        <p>¡Ups! Aquí no hay contenido... pero donde otros ven un vacío, nosotros vemos una oportunidad para crear algo increíble.</p>
        <a href="/" class="btn-primary">Volver al inicio</a>
    </div>
</div>
</main>
<style>
    .error-page {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 100vh;
        background-color: rgba(241, 90, 36, 0.1); /* Fondo suave de --color-primary */
        font-family: var(--font-primary, Roboto), sans-serif;
    }

    .error-page .content {
        max-width: 600px;
        padding: 20px;
    }

    .error-page h1 {
        font-size: 6rem;
        color: var(--color-primary, #F15A24);
        margin-bottom: 10px;
    }

    .error-page p {
        font-size: 1.2rem;
        color: var(--color-primary, #8DC63F);
        margin-bottom: 20px;
    }

    .btn-primary {
        display: inline-block;
        padding: 10px 20px;
        font-size: 1rem;
        color: #fff;
        background-color: var(--color-primary, #F15A24);
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: darken(var(--color-primary, #F15A24), 10%);
    }
</style>
