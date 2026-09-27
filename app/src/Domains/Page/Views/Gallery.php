<?php /** @var list<array{src:string,alt:string}> $images */ ?>
<?php if ($images === []): ?>
    <p>Aún no hay fotografías de eventos en la galería.</p>
<?php else: ?>
    <div class="page-gallery__grid">
        <?php foreach ($images as $index => $image): ?>
            <a class="page-gallery__photo" href="<?= htmlspecialchars($image['src'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="Ver fotografía: <?= htmlspecialchars($image['alt'], ENT_QUOTES, 'UTF-8') ?>">
                <img src="<?= htmlspecialchars($image['src'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($image['alt'], ENT_QUOTES, 'UTF-8') ?>" <?= $index > 2 ? 'loading="lazy"' : '' ?> decoding="async">
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<style>
.page-gallery__grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,280px),1fr));gap:16px}.page-gallery__photo{display:block;aspect-ratio:4/3;overflow:hidden;border-radius:8px;background:#eee}.page-gallery__photo img{display:block;width:100%;height:100%;object-fit:cover;transition:transform .2s}.page-gallery__photo:hover img{transform:scale(1.04)}.page-gallery__photo:focus-visible{outline:3px solid var(--color-brand,#8f734a);outline-offset:3px}@media(prefers-reduced-motion:reduce){.page-gallery__photo img{transition:none}}
</style>
