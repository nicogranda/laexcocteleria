<?php
/**
 * Hero
 * Bartender Profesional para Eventos en San Sebastián
 */

$hero = [
    'bg_image' => 'assets/img/hero/bartender-evento-sansebastian.png',

    'title' => 'Bartender Profesional para Eventos en San Sebastián',

    'subtitle' => 'Llevamos la barra de cócteles a tu evento con un servicio premium, ingredientes de calidad y una experiencia que tus invitados no olvidarán.',

    'cta_primary' => [
        'text' => 'Solicita Presupuesto',
        'url'  => 'es/contacto',
    ],

    'cta_secondary' => [
        'text' => 'Ver Servicios',
        'url'  => '#service',
    ],
];

/*
|--------------------------------------------------------------------------
| URLS
|--------------------------------------------------------------------------
|
| $baseUrl viene de APP_URL en .env
|
| Producción:
| APP_URL=https://laexcocteleria.com
|
| Local:
| APP_URL=http://localhost:8888/laexcocteleria/public_html
|
*/

$baseUrl = rtrim($baseUrl ?? '', '/');

$bgImage = $baseUrl . '/' . ltrim($hero['bg_image'], '/');

$primaryUrl = str_starts_with($hero['cta_primary']['url'], '#')
    ? $hero['cta_primary']['url']
    : $baseUrl . '/' . ltrim($hero['cta_primary']['url'], '/');

$secondaryUrl = str_starts_with($hero['cta_secondary']['url'], '#')
    ? $hero['cta_secondary']['url']
    : $baseUrl . '/' . ltrim($hero['cta_secondary']['url'], '/');
?>

<section
    class="hero"
    style="background-image: url('<?= htmlspecialchars($bgImage, ENT_QUOTES, 'UTF-8') ?>');"
>
    <div class="hero__overlay"></div>

    <div class="hero__content">

        <h1 class="hero__title">
            <?= htmlspecialchars($hero['title'], ENT_QUOTES, 'UTF-8') ?>
        </h1>

        <p class="hero__subtitle">
            <?= htmlspecialchars($hero['subtitle'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <div class="hero__actions">

            <a
                href="<?= htmlspecialchars($primaryUrl, ENT_QUOTES, 'UTF-8') ?>"
                class="hero__btn hero__btn--primary"
            >
                <?= htmlspecialchars($hero['cta_primary']['text'], ENT_QUOTES, 'UTF-8') ?>
            </a>

            <a
                href="<?= htmlspecialchars($secondaryUrl, ENT_QUOTES, 'UTF-8') ?>"
                class="hero__btn hero__btn--secondary"
            >
                <?= htmlspecialchars($hero['cta_secondary']['text'], ENT_QUOTES, 'UTF-8') ?>
            </a>

        </div>

    </div>
</section>

<style>

.hero {
    position: relative;
    min-height: 620px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.hero__content {
    position: relative;
    z-index: 2;

    width: 48%;
    max-width: 620px;

    margin-left: auto;
    margin-right: 6%;

    padding: 3rem 2rem;

    color: #fff;
    text-align: left;
}

.hero__overlay {
    position: absolute;
    inset: 0;

    background: linear-gradient(
        90deg,
        rgba(0, 0, 0, 0.65) 0%,
        rgba(0, 0, 0, 0.35) 55%,
        rgba(0, 0, 0, 0.10) 100%
    );
}

.hero__title {
    margin: 0 0 1.25rem 0;

    font-size: 2.75rem;
    font-weight: 800;
    line-height: 1.15;

    color: #fff;
}

.hero__subtitle {
    margin: 0 0 2rem 0;

    font-size: 1rem;
    line-height: 1.6;

    color: #eee;
}

.hero__actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.hero__btn {
    display: inline-block;

    padding: 0.85rem 1.75rem;

    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.5px;

    text-transform: uppercase;
    text-decoration: none;

    border-radius: 4px;

    transition:
        background-color 0.2s ease,
        color 0.2s ease,
        border-color 0.2s ease;
}

.hero__btn--primary {
    background-color: var(--color-brand);
    color: #fff;

    border: 1px solid var(--color-brand);
}

.hero__btn--primary:hover {
    background-color: transparent;
    color: var(--color-brand);
}

.hero__btn--secondary {
    background-color: transparent;
    color: #fff;

    border: 1px solid #fff;
}

.hero__btn--secondary:hover {
    background-color: #fff;
    color: #1a1a1a;
}

@media (max-width: 768px) {

    .hero {
        min-height: 520px;
        background-position: center;
    }

    .hero__content {
        max-width: 100%;
        padding: 2rem 1.5rem;
    }

    .hero__title {
        font-size: 2rem;
    }

    .hero__subtitle {
        font-size: 0.95rem;
    }

}

</style>