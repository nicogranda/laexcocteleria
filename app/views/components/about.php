<?php
/**
 * About
 * Sobre Nosotros — Conoce a La Ex Coctelería
 */

$about = [
    'image'   => 'assets/img/about/equipo-bartenders.jpg',
    'eyebrow' => 'Sobre Nosotros',
    'title'   => 'Conoce a La Ex Coctelería',

    'paragraphs' => [
        'Somos Álex y Axel, además de nuestro equipo de bartenders profesionales.',
        'Nacimos con una idea sencilla: demostrar que una barra de cócteles puede convertirse en uno de los grandes protagonistas de cualquier evento.',
        'Trabajamos con pasión, cuidando cada detalle, desde la selección de ingredientes hasta la presentación de cada bebida, ofreciendo un servicio elegante, cercano y completamente personalizado.',
    ],

    'highlight' => 'Nuestro objetivo es que tus invitados recuerden la experiencia mucho después de terminar el evento.',
];

/*
|--------------------------------------------------------------------------
| URL DE LA IMAGEN
|--------------------------------------------------------------------------
|
| $baseUrl viene de APP_URL en .env
|
| Producción:
| https://laexcocteleria.com
|
| Local:
| http://localhost:8888/laexcocteleria/public_html
|
*/

$baseUrl = rtrim($baseUrl ?? '', '/');

$imageUrl = $baseUrl . '/' . ltrim($about['image'], '/');
?>

<section class="about" id="about">

    <div
        class="about__image"
        style="background-image: url('<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>');"
    ></div>

    <div class="about__content">

        <span class="about__eyebrow">
            <?= htmlspecialchars($about['eyebrow'], ENT_QUOTES, 'UTF-8') ?>
        </span>

        <h2 class="about__title">
            <?= htmlspecialchars($about['title'], ENT_QUOTES, 'UTF-8') ?>
        </h2>

        <?php foreach ($about['paragraphs'] as $paragraph): ?>

            <p class="about__text">
                <?= htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8') ?>
            </p>

        <?php endforeach; ?>

        <p class="about__highlight">
            <?= htmlspecialchars($about['highlight'], ENT_QUOTES, 'UTF-8') ?>
        </p>

    </div>

</section>

<style>

.about {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;

    gap: 3rem;

    width: 100%;
    max-width: 1200px;

    margin: 0 auto;
    padding: 3.5rem 1.5rem;
}

.about__image {
    width: 100%;
    min-height: 400px;

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    border-radius: 6px;
}

.about__content {
    width: 100%;
}

.about__eyebrow {
    display: block;

    margin-bottom: 0.5rem;

    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 1px;

    text-transform: uppercase;

    color: var(--color-brand);
}

.about__title {
    margin: 0 0 1.25rem;

    font-size: 2rem;
    font-weight: 800;

    color: #1a1a1a;
}

.about__text {
    margin: 0 0 1rem;

    font-size: 0.9rem;
    line-height: 1.6;

    color: #555;
}

.about__highlight {
    margin: 1.25rem 0 0;

    font-size: 0.9rem;
    line-height: 1.6;
    font-weight: 700;

    color: #1a1a1a;
}

@media (max-width: 900px) {

    .about {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .about__image {
        min-height: 320px;
    }

}

@media (max-width: 600px) {

    .about {
        padding: 2.5rem 1.5rem;
    }

    .about__image {
        min-height: 280px;
    }

    .about__title {
        font-size: 1.75rem;
    }

}

</style>