<?php
$headerContent = [

    'ES' => [
        'nav' => [
            ['text' => 'Inicio',    'url' => '/',          'page' => 'home'],
            ['text' => 'Servicios', 'url' => '/#services', 'page' => 'services'],
            ['text' => 'Nosotros',  'url' => '/#about',    'page' => 'about'],
            ['text' => 'Galería',   'url' => '/#gallery',  'page' => 'gallery'],
            ['text' => 'FAQ',       'url' => '/#faq',      'page' => 'faq'],
            ['text' => 'Contacto',  'url' => '/es/contacto',  'page' => 'contact'],
        ],
        'cta' => [
            'text' => 'Solicitar Presupuesto',
            'url'  => '/es/contacto',
        ],
    ],

    'EN' => [
        'nav' => [
            ['text' => 'Home',     'url' => '/',          'page' => 'home'],
            ['text' => 'Services', 'url' => '/#services', 'page' => 'services'],
            ['text' => 'About',    'url' => '/#about',    'page' => 'about'],
            ['text' => 'Gallery',  'url' => '/#gallery',  'page' => 'gallery'],
            ['text' => 'FAQ',      'url' => '/#faq',      'page' => 'faq'],
            ['text' => 'Contact',  'url' => '/es/contact',  'page' => 'contact'],
        ],
        'cta' => [
            'text' => 'Request a Quote',
            'url'  => '/es/contact',
        ],
    ],

];

// Selecciona el idioma actual (con fallback a ES si no existe la traducción)
$headerText = $headerContent[$lang] ?? $headerContent['ES'];
?>
<header class="header">

    <div class="container header__inner">

        <a href="/" class="header__logo">
            <img
                src="<?= htmlspecialchars($baseUrl) ?>/assets/img/logo/laex-cocteleria-logo.png"
                alt="La Ex Coctelería"
                title="La Ex Coctelería"
                >
        </a>

        <nav class="header__nav">

            <?php foreach ($headerText['nav'] as $item): ?>

                
                   <a href="<?= htmlspecialchars($item['url']) ?>"
                    class="header__link<?= ($page === $item['page']) ? ' header__link--active' : '' ?>">

                    <?= htmlspecialchars($item['text']) ?>

                </a>

            <?php endforeach; ?>

        </nav>

        <a href="<?= htmlspecialchars($headerText['cta']['url']) ?>" class="header__cta">
            <?= htmlspecialchars($headerText['cta']['text']) ?>
        </a>

        <button class="header__burger" aria-label="Menú" type="button">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>

</header>

<style>
.header {
    background: #fff;
    border-bottom: 1px solid #eee;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.header__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 80px;
}

.header__logo img {
    display: block;
    width: 175px;

    object-fit: contain;
}

.header__nav {
    display: flex;
    align-items: center;
    gap: 32px;
}

.header__link {
    color: #1a1a1a;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    transition: color 0.2s ease;
}

.header__link:hover {
    color: var(--color-brand);
}

.header__link--active {
    color: var(--color-brand);
}

.header__cta {
    background-color: var(--color-brand);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-decoration: none;
    padding: 0.85rem 1.5rem;
    border-radius: 4px;
    white-space: nowrap;
    transition: opacity 0.2s ease;
}

.header__cta:hover {
    opacity: 0.85;
}

.header__burger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.header__burger span {
    width: 24px;
    height: 2px;
    background-color: #1a1a1a;
}

@media (max-width: 992px) {
    .header__nav,
    .header__cta {
        display: none;
    }
    .header__burger {
        display: flex;
    }
}

@media (max-width: 992px) {
    .header__nav {
        position: fixed;
        top: 80px;
        left: 0;
        right: 0;
        bottom: 0;
        background: #fff;
        flex-direction: column;
        align-items: flex-start;
        gap: 0;
        padding: 1.5rem;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        overflow-y: auto;
        z-index: 99;
    }

    .header__nav .header__link {
        display: block;
        width: 100%;
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }

    .header--open .header__nav {
        display: flex;
        transform: translateX(0);
    }

    .header--open .header__cta {
        display: inline-block;
        margin: 1rem 1.5rem 0;
    }

    .header__burger {
        z-index: 100;
    }

    .header--open .header__burger span:nth-child(1) {
        transform: translateY(7px) rotate(45deg);
    }
    .header--open .header__burger span:nth-child(2) {
        opacity: 0;
    }
    .header--open .header__burger span:nth-child(3) {
        transform: translateY(-7px) rotate(-45deg);
    }

    .header__burger span {
        transition: all 0.25s ease;
    }
}
</style>
