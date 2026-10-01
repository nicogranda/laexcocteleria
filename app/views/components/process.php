<?php
/**
 * Process
 * Así de fácil — pasos del proceso
 */

$process = [
    'eyebrow' => 'Así de fácil',
    'steps'   => [
        [
            'number' => 1,
            'title'  => 'Nos cuentas tu evento',
            'text'   => 'Fecha, número de invitados y tipo de celebración.',
        ],
        [
            'number' => 2,
            'title'  => 'Diseñamos tu propuesta',
            'text'   => 'Creamos un presupuesto y una carta de cócteles adaptada.',
        ],
        [
            'number' => 3,
            'title'  => 'Disfruta del evento',
            'text'   => 'Nos encargamos de todo para que tú solo tengas que disfrutar.',
        ],
    ],
];
?>

<section class="process">
    <span class="process__eyebrow"><?= htmlspecialchars($process['eyebrow']) ?></span>

    <div class="process__track">
        <?php foreach ($process['steps'] as $index => $step): ?>
            <div class="process__step">
                <span class="process__number"><?= (int) $step['number'] ?></span>
                <h3 class="process__title"><?= htmlspecialchars($step['title']) ?></h3>
                <p class="process__text"><?= htmlspecialchars($step['text']) ?></p>
            </div>

            <?php if ($index < count($process['steps']) - 1): ?>
                <div class="process__dots" aria-hidden="true"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<style>
.process {
    text-align: center;
    padding: 3rem 1.5rem;
}

.process__eyebrow {
    display: block;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--color-brand);
    margin-bottom: 2rem;
}

.process__track {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    max-width: 900px;
    margin: 0 auto;
}

.process__step {
    flex: 0 1 220px;
    min-width: 0;
    width: 220px;
}

.process__number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background-color: var(--color-brand);
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.process__title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.process__text {
    font-size: 0.85rem;
    line-height: 1.5;
    color: #666;
}

.process__dots {
    flex: 1 1 auto;
    align-self: flex-start;
    height: 2.5rem;
    margin-top: 1.2rem;
    border-top: 3px dotted var(--color-brand);
}

@media (max-width: 700px) {
    .process__track {
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }
    .process__dots {
        display: none;
    }
}
</style>
