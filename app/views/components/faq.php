<?php

/**
 * FAQ Component
 */

if (!isset($faqs) || !is_array($faqs) || empty($faqs)) {
    return;
}

/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN
|--------------------------------------------------------------------------
*/

$appName = $_ENV['APP_NAME'] ?? getenv('APP_NAME') ?: '';

// Estos textos pueden sobrescribirse desde cualquier página
$faqEyebrow = $faqEyebrow ?? 'Resolvemos tus dudas';

$faqTitle = $faqTitle ?? 'Preguntas frecuentes';

$faqIntro = $faqIntro ?? (
    $appName
        ? 'Encuentra respuestas a las preguntas más frecuentes sobre los servicios de ' . $appName . '.'
        : 'Encuentra respuestas a las preguntas más frecuentes sobre nuestros servicios.'
);

$faqId = 'faq_' . uniqid();

/*
|--------------------------------------------------------------------------
| SCHEMA FAQPAGE
|--------------------------------------------------------------------------
*/

$schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'FAQPage',
    'mainEntity' => array_map(function ($faq) {
        return [
            '@type' => 'Question',
            'name'  => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => strip_tags($faq['answer']),
            ],
        ];
    }, $faqs),
];

?>

<script type="application/ld+json">
<?= json_encode(
    $schema,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES |
    JSON_HEX_TAG |
    JSON_HEX_AMP |
    JSON_HEX_APOS |
    JSON_HEX_QUOT
); ?>
</script>


<section
    class="faq-section" id="faq"
    aria-labelledby="<?= htmlspecialchars($faqId . '_title', ENT_QUOTES, 'UTF-8') ?>"
>

    <div class="faq-header">

        <span class="faq-eyebrow">
            <?= htmlspecialchars($faqEyebrow, ENT_QUOTES, 'UTF-8') ?>
        </span>

        <h2
            class="faq-heading"
            id="<?= htmlspecialchars($faqId . '_title', ENT_QUOTES, 'UTF-8') ?>"
        >
            <?= htmlspecialchars($faqTitle, ENT_QUOTES, 'UTF-8') ?>
        </h2>

        <p class="faq-intro">
            <?= htmlspecialchars($faqIntro, ENT_QUOTES, 'UTF-8') ?>
        </p>

    </div>


    <div
        class="faq"
        id="<?= htmlspecialchars($faqId, ENT_QUOTES, 'UTF-8') ?>"
    >

        <?php foreach ($faqs as $index => $faq): ?>

            <?php

            if (
                !isset($faq['question'], $faq['answer']) ||
                trim($faq['question']) === '' ||
                trim($faq['answer']) === ''
            ) {
                continue;
            }

            $questionId = $faqId . '_question_' . $index;
            $answerId   = $faqId . '_answer_' . $index;

            ?>

            <div class="faq-item">

                <button
                    type="button"
                    class="faq-question"
                    id="<?= htmlspecialchars($questionId, ENT_QUOTES, 'UTF-8') ?>"
                    aria-expanded="false"
                    aria-controls="<?= htmlspecialchars($answerId, ENT_QUOTES, 'UTF-8') ?>"
                >
                    <span>
                        <?= htmlspecialchars(
                            $faq['question'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>
                </button>

                <div
                    class="faq-answer"
                    id="<?= htmlspecialchars($answerId, ENT_QUOTES, 'UTF-8') ?>"
                    role="region"
                    aria-labelledby="<?= htmlspecialchars($questionId, ENT_QUOTES, 'UTF-8') ?>"
                >
                    <?= $faq['answer'] ?>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const root = document.getElementById(
        '<?= addslashes($faqId) ?>'
    );

    if (!root) {
        return;
    }

    const items = root.querySelectorAll('.faq-item');

    items.forEach(function (item) {

        const button = item.querySelector('.faq-question');

        if (!button) {
            return;
        }

        button.addEventListener('click', function () {

            const isOpen = item.classList.contains('open');

            /*
             * Cerrar todas las preguntas
             */

            items.forEach(function (otherItem) {

                otherItem.classList.remove('open');

                const otherButton =
                    otherItem.querySelector('.faq-question');

                if (otherButton) {
                    otherButton.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }

            });

            /*
             * Abrir la seleccionada
             */

            if (!isOpen) {

                item.classList.add('open');

                button.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }

        });

    });

});
</script>


<style>

/* ============================================
   FAQ
============================================ */

.faq-section {
    width: 100%;
    padding: 4.5rem 1.5rem;
}


/* ============================================
   HEADER
============================================ */

.faq-header {
    max-width: 760px;
    margin: 0 auto 3rem;
    text-align: center;
}

.faq-eyebrow {
    display: block;

    margin-bottom: 0.75rem;

    font-family: var(--font-family-secondary);
    font-size: 0.75rem;
    font-weight: 600;

    letter-spacing: 2px;
    text-transform: uppercase;

    color: var(--color-brand);
}

.faq-heading {
    margin: 0 0 1rem;

    font-family: var(--font-family-primary);
    font-size: 2.2rem;
    font-weight: normal;
    line-height: 1.2;

    color: var(--color-primary);
}

.faq-intro {
    max-width: 650px;
    margin: 0 auto;

    font-family: var(--font-family-secondary);
    font-size: 0.95rem;
    line-height: 1.7;

    color: var(--color-primary);
}


/* ============================================
   FAQ GRID
============================================ */

.faq {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    column-gap: 3rem;

    width: 100%;
    max-width: 1100px;

    margin: 0 auto;
}


/* ============================================
   ITEM
============================================ */

.faq-item {
    align-self: start;

    border-bottom:
        1px solid var(--color-brand);
}


/* ============================================
   QUESTION
============================================ */

.faq-question {
    width: 100%;

    padding: 1.4rem 0;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 1rem;

    border: 0;
    background: transparent;

    text-align: left;

    font-family: var(--font-family-secondary);
    font-size: 0.92rem;
    font-weight: 600;
    line-height: 1.5;

    color: var(--color-primary);

    cursor: pointer;

    appearance: none;

    transition:
        color 0.25s ease;
}

.faq-question:hover {
    color: var(--color-brand);
}


/* ============================================
   ICONO + / -
============================================ */

.faq-question::after {
    content: '+';

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    width: 26px;
    height: 26px;

    font-family: var(--font-family-secondary);
    font-size: 1.35rem;
    font-weight: 300;
    line-height: 1;

    color: var(--color-brand);
}

.faq-item.open .faq-question::after {
    content: '−';
}


/* ============================================
   ANSWER
============================================ */

.faq-answer {
    display: none;

    padding:
        0
        2.5rem
        1.4rem
        0;

    font-family: var(--font-family-secondary);
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.7;

    color: var(--color-primary);

    text-align: left;
}

.faq-answer p {
    margin: 0;
}

.faq-item.open .faq-answer {
    display: block;
}


/* ============================================
   ACCESSIBILITY
============================================ */

.faq-question:focus-visible {
    outline:
        2px solid var(--color-brand);

    outline-offset: 4px;
}


/* ============================================
   TABLET
============================================ */

@media (max-width: 850px) {

    .faq-section {
        padding: 3.5rem 1.5rem;
    }

    .faq {
        grid-template-columns: 1fr;
        max-width: 720px;
    }

}


/* ============================================
   MOBILE
============================================ */

@media (max-width: 600px) {

    .faq-section {
        padding: 3rem 1rem;
    }

    .faq-header {
        margin-bottom: 2.25rem;
    }

    .faq-heading {
        font-size: 1.9rem;
    }

    .faq-intro {
        font-size: 0.9rem;
    }

    .faq-question {
        padding: 1.15rem 0;

        font-size: 0.9rem;
    }

    .faq-answer {
        padding:
            0
            2rem
            1.2rem
            0;

        font-size: 0.88rem;
    }

}

</style>