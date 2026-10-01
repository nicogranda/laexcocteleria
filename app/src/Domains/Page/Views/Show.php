<?php
/** @var array $page */
/** @var array $translation */
/** @var array $faqs */
/** @var array $relatedPages */
$escape = static fn (?string $value): string => htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$publicUrl = static fn (string $path): string => rtrim($assetBasePath, '/') . '/' . ltrim($path, '/');
$isLanding = $page['type'] === 'landing';
?>
<link rel="stylesheet" href="<?= $escape($publicUrl('assets/css/landings.css')) ?>">
<main class="landing-page<?= $isLanding ? '' : ' landing-page--gallery' ?>">
    <?php if ($isLanding): ?>
    <section class="landing-hero">
        <div class="landing-hero__copy">
            <nav class="landing-breadcrumb" aria-label="Ruta de navegación"><a href="<?= $escape($publicUrl('')) ?>">Inicio</a><span aria-hidden="true">/</span><span>Coctelería para eventos</span></nav>
            <p class="landing-eyebrow">LA EX COCTELERÍA · DONOSTIA &amp; GIPUZKOA</p>
            <h1><?= $escape($translation['h1']) ?></h1>
            <p class="landing-hero__lead"><?= $escape($translation['excerpt']) ?></p>
            <div class="landing-actions">
                <a class="landing-button" href="<?= $escape($publicUrl($translation['hero_cta_url'])) ?>"><?= $escape($translation['hero_cta_text']) ?><span aria-hidden="true">↗</span></a>
                <a class="landing-link" href="#propuesta">Conoce la propuesta <span aria-hidden="true">↓</span></a>
            </div>
            <p class="landing-hero__note">Carta personalizada · Cócteles sin alcohol · Presupuesto a medida</p>
        </div>
        <?php if ($translation['hero_image'] !== null): ?>
        <figure class="landing-hero__visual">
            <img src="<?= $escape($publicUrl($translation['hero_image'])) ?>" alt="<?= $escape($translation['hero_image_alt']) ?>" width="900" height="1100" fetchpriority="high" decoding="async">
            <figcaption><span>UNA CELEBRACIÓN CON PERSONALIDAD</span><p><?= $escape($translation['hero_eyebrow']) ?></p></figcaption>
        </figure>
        <?php endif; ?>
    </section>
    <div class="landing-band" aria-label="Características del servicio"><span>Cócteles preparados al momento</span><span>Una carta para tu evento</span><span>Coordinación con tu espacio</span></div>
    <div class="landing-wrap">
        <section class="landing-intro" id="propuesta">
            <p class="landing-eyebrow">LA PROPUESTA</p>
            <h2><?= $escape($translation['hero_eyebrow']) ?></h2>
        </section>
        <div class="landing-content">
            <?php // HTML editorial controlado. Sanitizar al guardar si se incorpora edición desde un panel. ?>
            <?= $translation['content'] ?>
        </div>
        <?php foreach ($components as $component): ?>
            <?php if ($component['type'] === 'Gallery'): ?>
                <?php $images = $component['images']; require __DIR__ . '/Gallery.php'; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <section class="landing-process" aria-labelledby="landing-process-heading">
            <div><p class="landing-eyebrow">ASÍ LO PREPARAMOS</p><h2 id="landing-process-heading">De tu idea<br>a la primera copa.</h2><p>Definimos contigo los detalles para que el servicio encaje con tu celebración.</p></div>
            <ol>
                <li><span aria-hidden="true">01</span><div><h3>Cuéntanos el plan</h3><p>Fecha, lugar, invitados y horario. Empezamos por conocer tu evento.</p></div></li>
                <li><span aria-hidden="true">02</span><div><h3>Diseñamos la propuesta</h3><p>Acordamos carta, equipo y montaje según tus necesidades y el espacio.</p></div></li>
                <li><span aria-hidden="true">03</span><div><h3>Preparamos el servicio</h3><p>Coordinamos los detalles del servicio contratado antes de la celebración.</p></div></li>
            </ol>
        </section>
        <?php if ($faqs !== []): ?>
        <section class="landing-faq" aria-labelledby="landing-faq-heading">
            <div><p class="landing-eyebrow">ANTES DE RESERVAR</p><h2 id="landing-faq-heading">Resolvemos tus dudas.</h2></div>
            <div class="landing-faq__items">
            <?php foreach ($faqs as $faq): ?>
                <details><summary><?= $escape($faq['question']) ?></summary><p><?= $escape($faq['answer']) ?></p></details>
            <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        <section class="landing-cta">
            <div><p class="landing-eyebrow">TU PRÓXIMO EVENTO</p><h2>El siguiente brindis<br>empieza contigo.</h2><p>Cuéntanos cuándo, dónde y con quién. Consultaremos disponibilidad y prepararemos una propuesta para tu celebración.</p></div>
            <a class="landing-button landing-button--light" href="<?= $escape($publicUrl($translation['hero_cta_url'])) ?>"><?= $escape($translation['hero_cta_text']) ?><span aria-hidden="true">↗</span></a>
        </section>
        <nav class="landing-related" aria-label="Más servicios de coctelería">
            <div class="landing-related__heading"><p class="landing-eyebrow">OTRAS OCASIONES</p><h2>Cada encuentro tiene su carta.</h2></div>
            <div class="landing-related__grid">
            <?php foreach ($relatedPages as $related): if ($related['hero_image'] === null) continue; ?>
                <a class="landing-related__card" href="<?= $escape($publicUrl($related['slug'])) ?>">
                    <img src="<?= $escape($publicUrl($related['hero_image'])) ?>" alt="" width="600" height="400" loading="lazy" decoding="async">
                    <span><?= $escape($related['h1']) ?><span aria-hidden="true">↗</span></span>
                </a>
            <?php endforeach; ?>
            </div>
        </nav>
    </div>
    <?php else: ?>
    <div class="landing-wrap">
        <header class="landing-gallery__header"><h1><?= $escape($translation['h1']) ?></h1><p><?= $escape($translation['excerpt']) ?></p></header>
        <?php foreach ($components as $component): ?>
            <?php if ($component['type'] === 'Gallery'): ?>
                <?php $images = $component['images']; require __DIR__ . '/Gallery.php'; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>
