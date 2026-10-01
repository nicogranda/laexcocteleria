<?php
/** @var array $page */
/** @var array $translation */
/** @var array $faqs */
/** @var array $relatedPages */
$escape = static fn (?string $value): string => htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<main class="landing-page">
    <header class="landing-hero">
        <p class="landing-eyebrow"><?= $escape($translation['hero_eyebrow']) ?></p>
        <h1><?= $escape($translation['h1']) ?></h1>
        <p><?= $escape($translation['excerpt']) ?></p>
        <?php if ($translation['hero_cta_text'] !== null): ?>
        <a class="btn btn-primary" href="<?= $escape(rtrim($assetBasePath, '/') . $translation['hero_cta_url']) ?>"><?= $escape($translation['hero_cta_text']) ?></a>
        <?php endif; ?>
    </header>

    <div class="landing-content">
        <?php // HTML editorial de confianza escrito en el modelo. Al migrar, sanitizar al guardar; nunca aceptar HTML de visitantes. ?>
        <?= $translation['content'] ?>
    </div>

    <?php if ($faqs !== []): ?>
        <section class="landing-faq" aria-labelledby="landing-faq-heading">
            <h2 id="landing-faq-heading">Preguntas frecuentes</h2>
            <?php foreach ($faqs as $faq): ?>
                <details>
                    <summary><?= $escape($faq['question']) ?></summary>
                    <p><?= $escape($faq['answer']) ?></p>
                </details>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php foreach ($components as $component): ?>
        <?php if ($component['type'] === 'Gallery'): ?>
            <?php $images = $component['images']; require __DIR__ . '/Gallery.php'; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($page['type'] === 'landing'): ?>
    <section class="landing-cta">
        <h2>Cuéntanos cómo será tu evento</h2>
        <p>Envíanos la fecha, el lugar, el número aproximado de invitados y el horario previsto. Prepararemos una propuesta según tus necesidades y nuestra disponibilidad.</p>
        <a class="btn btn-primary" href="<?= $escape(rtrim($assetBasePath, '/') . $translation['hero_cta_url']) ?>"><?= $escape($translation['hero_cta_text']) ?></a>
    </section>

    <nav class="landing-related" aria-label="Otros servicios de coctelería">
        <h2>Más información sobre nuestros servicios</h2>
        <ul>
            <?php foreach ($relatedPages as $related): ?>
                <li><a href="<?= $escape(rtrim($assetBasePath, '/') . '/' . $related['slug']) ?>"><?= $escape($related['h1']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php endif; ?>
</main>

<style>
.landing-page{max-width:1200px;margin:auto;padding:140px 24px 90px;font-family:var(--font-text)}
.landing-hero{max-width:850px;margin-bottom:48px}.landing-hero h1{font-family:var(--font-brand);font-size:clamp(2.2rem,5vw,4rem);line-height:1.15;margin:12px 0 24px}
.landing-page p{line-height:1.8;max-width:850px}.landing-eyebrow{color:#980c28}.landing-section,.landing-faq,.landing-cta,.landing-related{margin-top:48px}
.landing-page h2{font-size:clamp(1.4rem,3vw,2rem);line-height:1.3}.landing-page details{border-bottom:1px solid #ddd;padding:18px 0}.landing-page summary{cursor:pointer;font-weight:600}
.landing-page .btn{display:inline-block;background:#980c28;color:#fff;padding:14px 24px;text-decoration:none;border-radius:4px;margin-top:12px}.landing-page a:focus-visible,.landing-page summary:focus-visible{outline:3px solid #980c28;outline-offset:4px}
.landing-related li{margin:12px 0}.landing-related a{color:#980c28}@media(max-width:768px){.landing-page{padding:100px 20px 70px}}
</style>
