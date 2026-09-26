<?php
if (empty($policy) || !is_array($policy)) return;
$eyebrow = $policy['eyebrow'] ?? '';
$title = $policy['title'] ?? '';
$excerpt = $policy['excerpt'] ?? '';
$sections = $policy['sections'] ?? [];
?>
<section class="policy">
    <div class="policy__container">
        <header class="policy__header">
            <?php if ($eyebrow): ?><span class="policy__eyebrow"><?= htmlspecialchars($eyebrow) ?></span><?php endif; ?>
            <h1 class="policy__title"><?= htmlspecialchars($title) ?></h1>
            <?php if ($excerpt): ?><p class="policy__excerpt"><?= htmlspecialchars($excerpt) ?></p><?php endif; ?>
        </header>
        <?php if ($sections): ?><div class="policy__content">
            <?php foreach ($sections as $section): ?><section class="policy__section">
                <?php if (!empty($section['title'])): ?><h2><?= htmlspecialchars($section['title']) ?></h2><?php endif; ?>
                <?php if (!empty($section['content'])): ?><div class="policy__text"><?= $section['content'] ?></div><?php endif; ?>
            </section><?php endforeach; ?>
        </div><?php endif; ?>
    </div>
</section>

<style>
.policy{padding:140px 24px 100px;background:#fff;color:#222}.policy__container{width:min(100%,900px);margin:0 auto}.policy__header{max-width:760px;margin-bottom:70px}.policy__eyebrow{display:block;margin-bottom:16px;font-family:var(--font-text);font-size:.75rem;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:var(--color-primary,#8f734a)}.policy__title{margin:0 0 22px;font-family:var(--font-brand);font-size:clamp(2.7rem,6vw,5rem);font-weight:400;line-height:1}.policy__excerpt{margin:0;font-family:var(--font-text);font-size:1.05rem;line-height:1.8;color:#666}.policy__content{display:flex;flex-direction:column;gap:48px}.policy__section{padding-bottom:48px;border-bottom:1px solid rgba(0,0,0,.1)}.policy__section:last-child{padding-bottom:0;border-bottom:0}.policy__section h2{margin:0 0 18px;font-family:var(--font-brand);font-size:clamp(1.7rem,3vw,2.4rem);font-weight:400;line-height:1.2}.policy__text{font-family:var(--font-text);font-size:.98rem;line-height:1.85;color:#555}.policy__text p{margin:0 0 18px}.policy__text p:last-child{margin-bottom:0}.policy__text ul{margin:18px 0;padding-left:22px}.policy__text li{margin-bottom:9px}.policy__text a{color:var(--color-primary,#8f734a);text-decoration:underline;text-underline-offset:3px}@media(max-width:768px){.policy{padding:100px 20px 70px}.policy__header{margin-bottom:50px}.policy__content{gap:36px}.policy__section{padding-bottom:36px}}
</style>
