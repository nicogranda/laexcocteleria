<?php
/**
 * Services
 * Nuestros Servicios — Coctelería para cada ocasión
 */

$services = [
    [
        'image' => 'assets/img/services/bodas.png',
        'slug' => 'cocteleria-para-bodas',
        'icon'  => 'fa-ring',
        'title' => 'Bodas',
        'text'  => 'Haz que el día más importante sea aún más especial con una barra de cócteles elegante y personalizada.',
    ],
    [
        'image' => 'assets/img/services/eventos-corporativos.png',
        'slug' => 'cocteleria-para-eventos-corporativos',
        'icon'  => 'fa-briefcase',
        'title' => 'Eventos Corporativos',
        'text'  => 'Presentaciones, inauguraciones, networking y fiestas de empresa con una imagen profesional.',
    ],
    [
        'image' => 'assets/img/services/cumpleanos.png',
        'slug' => 'cocteleria-para-cumpleanos',
        'icon'  => 'fa-cake-candles',
        'title' => 'Cumpleaños',
        'text'  => 'Celebra con una carta de cócteles diseñada para sorprender a todos tus invitados.',
    ],
    [
        'image' => 'assets/img/services/despedidas.png',
        'slug' => 'cocteleria-para-despedidas',
        'icon'  => 'fa-champagne-glasses',
        'title' => 'Despedidas',
        'text'  => 'Diversión, espectáculo y cócteles exclusivos para una noche inolvidable.',
    ],
    [
        'image' => 'assets/img/services/graduaciones.png',
        'slug' => 'cocteleria-para-graduaciones',
        'icon'  => 'fa-graduation-cap',
        'title' => 'Graduaciones',
        'text'  => 'Celebra el final de una etapa con una experiencia premium.',
    ],
    [
        'image' => 'assets/img/services/eventos-privados.png',
        'slug' => 'cocteleria-para-eventos-privados',
        'icon'  => 'fa-people-group',
        'title' => 'Eventos Privados',
        'text'  => 'Comuniones, aniversarios, fiestas familiares y cualquier otra celebración especial.',
    ],
];

$baseUrl = rtrim($baseUrl ?? '', '/');
?>

<section class="services" id="service">
    <span id="services" aria-hidden="true"></span>
    <span id="servicios" aria-hidden="true"></span>

    <div class="services__header">
        <span class="services__eyebrow">
            Nuestros Servicios
        </span>

        <h2 class="services__title">
            Coctelería para cada ocasión
        </h2>
    </div>

    <div class="services__grid">

        <?php foreach ($services as $service): ?>

            <?php
            $imageUrl = rtrim($assetBasePath ?? '', '/') . '/' . ltrim($service['image'], '/');
            $serviceUrl = rtrim($assetBasePath ?? '', '/') . '/' . $service['slug'];
            ?>

            <article class="services__card">
                <a class="services__card-link" href="<?= htmlspecialchars($serviceUrl, ENT_QUOTES, 'UTF-8') ?>">

                <div
                    class="services__card-image"
                    style="background-image: url('<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>');"
                >
                    <span class="services__card-icon">
                        <i
                            class="fa-solid <?= htmlspecialchars($service['icon'], ENT_QUOTES, 'UTF-8') ?>"
                            aria-hidden="true"
                        ></i>
                    </span>
                </div>

                <div class="services__card-body">

                    <h3 class="services__card-title">
                        <?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?>
                    </h3>

                    <p class="services__card-text">
                        <?= htmlspecialchars($service['text'], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <span class="services__card-more">Descubrir servicio <span aria-hidden="true">→</span></span>
                </div>
                </a>
            </article>

        <?php endforeach; ?>

    </div>

</section>

<style>

.services {
    padding: 3.5rem 1.5rem;
    text-align: center;
}

.services__header {
    max-width: 700px;
    margin: 0 auto 2.5rem;
}

.services__eyebrow {
    display: block;

    margin-bottom: 0.5rem;

    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 1px;

    text-transform: uppercase;

    color: var(--color-brand);
}

.services__title {
    margin: 0;

    font-size: 1.75rem;
    font-weight: 800;

    color: #1a1a1a;
}

.services__grid {
    display: grid;

    grid-template-columns: repeat(6, minmax(0, 1fr));

    gap: 1.25rem;

    width: 100%;
    max-width: 1280px;

    margin: 0 auto;
}

.services__card {
    min-width: 0;
    background: #fff;

    border: 1px solid #eee;
    border-radius: 8px;

    overflow: hidden;

    text-align: center;

    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}

.services__card-link{display:block;height:100%;color:inherit;text-decoration:none}.services__card-link:focus-visible{outline:3px solid #A10926;outline-offset:-4px}.services__card-more{display:block;color:#A10926;font-weight:700;font-size:.8rem;margin-top:20px}.services__card:hover{border-color:#A10926}

.services__card-image {
    position: relative;

    width: 100%;
    height: 180px;

    margin-bottom: 2.25rem;

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.services__card-icon {
    position: absolute;

    bottom: -1.75rem;
    left: 50%;

    transform: translateX(-50%);

    width: 3.5rem;
    height: 3.5rem;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;
    border: 3px solid #fff;

    background-color: var(--color-brand);
    color: #fff;

    font-size: 1.35rem;
}

.services__card-body {
    padding: 0 1.25rem 1.5rem;
}

.services__card-title {
    margin: 0 0 0.6rem;

    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.5px;

    text-transform: uppercase;

    color: #1a1a1a;
}

.services__card-text {
    margin: 0;

    font-size: 0.85rem;
    line-height: 1.5;

    color: #666;
}

@media (max-width: 1100px) {

    .services__grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

}

@media (max-width: 700px) {

    .services__grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 480px) {

    .services__grid {
        grid-template-columns: 1fr;
    }

    .services__card-image {
        height: 240px;
    }

}

</style>

