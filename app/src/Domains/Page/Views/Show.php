<?php
/** @var array{title:string,description:string,components:list<string>} $page */
/** @var list<array{type:string,images:list<array{src:string,alt:string}>}> $components */
?>
<main class="page-gallery">
    <header class="page-gallery__header">
        <h1><?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= htmlspecialchars($page['description'], ENT_QUOTES, 'UTF-8') ?></p>
    </header>

    <?php foreach ($components as $component): ?>
        <?php if ($component['type'] === 'Gallery'): ?>
            <?php $images = $component['images']; require __DIR__ . '/Gallery.php'; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</main>
<style>
.page-gallery{max-width:1200px;margin:0 auto;padding:140px 24px 90px}.page-gallery__header{margin-bottom:40px}.page-gallery__header h1{font-family:var(--font-brand);font-size:clamp(2.5rem,6vw,5rem);margin:0 0 16px}.page-gallery__header p{font-family:var(--font-text);line-height:1.7;max-width:680px;margin:0;color:#555}@media(max-width:768px){.page-gallery{padding:100px 20px 70px}}
</style>
