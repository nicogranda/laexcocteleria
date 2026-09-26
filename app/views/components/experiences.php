<?php
/**
 * Experiences
 * La diferencia está en la experiencia
 */


$features = [
    [
        'icon'  => 'fa-solid fa-martini-glass',
        'title' => '100% Personalizado',
        'text'  => 'Diseñamos un menú de cócteles adaptado a tu evento y a tus invitados.',
    ],
    [
        'icon'  => 'fa-solid fa-user-tie',
        'title' => 'Bartenders Profesionales',
        'text'  => 'Experiencia en bodas, empresas y eventos privados.',
    ],
    [
        'icon'  => 'fa-solid fa-leaf',
        'title' => 'Ingredientes Premium',
        'text'  => 'Destilados de calidad, frutas frescas y elaboración al momento.',
    ],
    [
        'icon'  => 'fa-solid fa-clock',
        'title' => 'Puntualidad Garantizada',
        'text'  => 'Llegamos con todo el material necesario para que no te preocupes por nada.',
    ],
];
?>


<section class="features-experiencia">
    <div class="features-experiencia__header">
        <h2 class="features-experiencia__title">La diferencia está en la experiencia</h2>
        <p class="features-experiencia__subtitle">
            No solo servimos cócteles. Creamos momentos memorables donde cada bebida
            se convierte en parte del espectáculo.
        </p>
    </div>

    <div class="features-experiencia__grid">
        <?php foreach ($features as $feature): ?>
            <div class="features-experiencia__item">
                <span class="features-experiencia__icon">
                    <i class="fa-regular <?= htmlspecialchars($feature['icon']) ?>" aria-hidden="true"></i>
                </span>
                <h3 class="features-experiencia__item-title">
                    <?= htmlspecialchars($feature['title']) ?>
                </h3>
                <p class="features-experiencia__item-text">
                    <?= htmlspecialchars($feature['text']) ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<style>
.features-experiencia {
    text-align: center;
    padding: 3rem 1.5rem;
}

.features-experiencia__title {
    color: var(--color-brand);
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}

.features-experiencia__subtitle {
    max-width: 640px;
    margin: 0 auto 2.5rem;
    color: #555;
}

.features-experiencia__grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    align-items: start;
}

.features-experiencia__item {
    position: relative;
    padding: 0 1.5rem;
}

/* Línea divisoria vertical real, una por cada item excepto el primero */
.features-experiencia__item:not(:first-child)::before {
    content: '';
    position: absolute;
    top: 0.25rem;
    left: 0;
    width: 1px;
    height: 3.5rem;
    background-color: #ddd;
}

.features-experiencia__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: var(--color-brand);
    margin-bottom: 0.75rem;
}

.features-experiencia__item-title {
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.features-experiencia__item-text {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
}

@media (max-width: 900px) {
    .features-experiencia__grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2.5rem 0;
    }

    .features-experiencia__item:nth-child(2n+1)::before {
        display: none;
    }
}

@media (max-width: 500px) {
    .features-experiencia__grid {
        grid-template-columns: 1fr;
        gap: 2.5rem 0;
    }

    .features-experiencia__item::before {
        display: none;
    }
}
</style>