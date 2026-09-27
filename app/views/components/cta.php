<?php
/**
 * CTA
 * ¿Preparado para sorprender a tus invitados?
 */

$cta = [
    'logo'  => '/assets/img/logo/laex-blanco.svg',
    'title' => '¿Preparado para sorprender a tus invitados?',
    'text'  => 'Solicita un presupuesto sin compromiso y descubre cómo podemos convertir tu celebración en una experiencia inolvidable.',
    'image' => '/assets/img/cta/coctel-cereza.jpg',
    'button' => [
        'text' => 'Solicitar Presupuesto',
        'url'  => '/es/contacto',
    ],
];
?>

<section class="cta">
    <div class="cta__logo">
        <img src="<?= htmlspecialchars($cta['logo']) ?>" alt="Logo">
    </div>

    <div class="cta__content">
        <h2 class="cta__title"><?= htmlspecialchars($cta['title']) ?></h2>
        <p class="cta__text"><?= htmlspecialchars($cta['text']) ?></p>
    </div>

    <a href="<?= htmlspecialchars($cta['button']['url']) ?>" class="cta__btn">
        <?= htmlspecialchars($cta['button']['text']) ?>
    </a>

    <div class="cta__image" style="background-image: url('<?= htmlspecialchars($cta['image']) ?>');"></div>
</section>

<style>
.cta {
    position: relative;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background-color: var(--color-brand);
    padding: 1.5rem 2.5rem;
    overflow: hidden;
}

.cta__logo {
    flex: 0 0 auto;
    width: 100px;
    
}

.cta__logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.cta__content {
    flex: 1 1 auto;
    color: #fff;
    z-index: 1;
}

.cta__title {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 0.4rem;
}

.cta__text {
    font-size: 0.85rem;
    line-height: 1.5;
    color: #f0dcdc;
    max-width: 560px;
}

.cta__btn {
    flex: 0 0 auto;
    background-color: #fff;
    color: var(--color-brand);
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-decoration: none;
    padding: 0.9rem 1.75rem;
    border-radius: 4px;
    white-space: nowrap;
    z-index: 1;
    transition: opacity 0.2s ease;
}

.cta__btn:hover {
    opacity: 0.9;
}

.cta__image {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 220px;
    background-size: cover;
    background-position: center;
    -webkit-mask-image: linear-gradient(to right, transparent, #000 40%);
    mask-image: linear-gradient(to right, transparent, #000 40%);
}

@media (max-width: 900px) {
    .cta__image {
        display: none;
    }
}

@media (max-width: 700px) {
    .cta {
        flex-wrap: wrap;
        text-align: center;
        justify-content: center;
    }
    .cta__logo {
        display: none;
    }
    .cta__btn {
        width: 100%;
        text-align: center;
    }
}
</style>
