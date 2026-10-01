<?php
/**
 * Gallery
 * Así vivimos cada evento — Momentos que se quedan en la memoria
 */

$gallery = [
    'eyebrow' => 'Así vivimos cada evento',

    'title' => 'Momentos que se quedan en la memoria',

    'text' => 'Cada celebración es diferente. Descubre algunos de los momentos que hemos compartido con nuestros clientes.',

    'cta' => [
        'text' => 'Ver Galería Completa',
        'url'  => 'galeria',
    ],

    'images' => [
        'assets/img/gallery/coctel-espuma.webp',
        'assets/img/gallery/old-fashioned.webp',
        'assets/img/gallery/bartender-preparando.webp',
        'assets/img/gallery/espresso-martini.webp',
    ],
];

$baseUrl = rtrim($baseUrl ?? '', '/');

$galleryUrl = str_starts_with($gallery['cta']['url'], '#')
    ? $gallery['cta']['url']
    : $baseUrl . '/' . ltrim($gallery['cta']['url'], '/');
?>

<section class="gallery" id="gallery">

    <div class="gallery__panel">

        <span class="gallery__eyebrow">
            <?= htmlspecialchars($gallery['eyebrow'], ENT_QUOTES, 'UTF-8') ?>
        </span>

        <h2 class="gallery__title">
            <?= htmlspecialchars($gallery['title'], ENT_QUOTES, 'UTF-8') ?>
        </h2>

        <p class="gallery__text">
            <?= htmlspecialchars($gallery['text'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <a
            href="<?= htmlspecialchars($galleryUrl, ENT_QUOTES, 'UTF-8') ?>"
            class="gallery__btn"
        >
            <?= htmlspecialchars($gallery['cta']['text'], ENT_QUOTES, 'UTF-8') ?>
        </a>

    </div>

    <?php foreach ($gallery['images'] as $image): ?>

        <?php
        $imageUrl = $baseUrl . '/' . ltrim($image, '/');
        ?>

        <div class="gallery__item">
            <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>"
                 alt="<?= htmlspecialchars(str_replace('-', ' ', pathinfo($image, PATHINFO_FILENAME)), ENT_QUOTES, 'UTF-8') ?>"
                 width="675" height="1200" loading="lazy" decoding="async">
        </div>

    <?php endforeach; ?>

</section>

<style>

.gallery {
    display: grid;

    grid-template-columns: 1.2fr repeat(4, 1fr);

    width: 100%;
    min-height: 340px;
}

.gallery__panel {
    display: flex;
    flex-direction: column;
    justify-content: center;

    padding: 2.5rem 2rem;

    background-color: #1a1a1a;
    color: #fff;
}

.gallery__eyebrow {
    margin-bottom: 0.75rem;

    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;

    text-transform: uppercase;

    color: var(--color-brand);
}

.gallery__title {
    margin: 0 0 1rem;

    font-size: 1.6rem;
    font-weight: 800;
    line-height: 1.25;

    color: #fff;
}

.gallery__text {
    margin: 0 0 1.75rem;

    font-size: 0.85rem;
    line-height: 1.5;

    color: #ccc;
}

.gallery__btn {
    display: inline-block;
    align-self: flex-start;

    padding: 0.8rem 1.5rem;

    background-color: var(--color-brand);
    color: #fff;

    border: 1px solid var(--color-brand);
    border-radius: 4px;

    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;

    text-transform: uppercase;
    text-decoration: none;

    transition:
        background-color 0.2s ease,
        color 0.2s ease;
}

.gallery__btn:hover {
    background-color: transparent;
    color: var(--color-brand);
}

.gallery__item {
    position: relative;
    width: 100%;

    min-height: 340px;

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.gallery__item img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media (max-width: 1024px) {

    .gallery {
        grid-template-columns: 1fr 1fr;
    }

    .gallery__panel {
        grid-column: 1 / -1;
    }

    .gallery__item {
        min-height: 420px;
    }

}

@media (max-width: 600px) {

    .gallery {
        display: flex;
        flex-direction: column;
    }

    .gallery__panel {
        padding: 2rem 1.5rem;
    }

    .gallery__item {
        width: 100%;
        min-height: 520px;

        background-size: cover;
        background-position: center;
    }

}

</style>