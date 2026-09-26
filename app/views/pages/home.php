<?php

/**
 * Home
 * La Ex Coctelería
 */

$faqs = [
    [
        'question' => '¿Qué incluye el servicio de coctelería para eventos?',
        'answer' => 'Nuestro servicio puede incluir bartenders profesionales, montaje de la barra, cristalería, utensilios de coctelería, ingredientes y todo lo necesario para preparar y servir los cócteles durante el evento. Adaptamos cada propuesta al tipo de celebración y número de invitados.'
    ],
    [
        'question' => '¿Para qué tipo de eventos ofrecéis servicio de coctelería?',
        'answer' => 'Trabajamos en bodas, eventos corporativos, cumpleaños, fiestas privadas, graduaciones, despedidas y otros eventos en los que quieras incorporar una experiencia de coctelería profesional.'
    ],
    [
        'question' => '¿Os desplazáis a cualquier lugar de Gipuzkoa?',
        'answer' => 'Sí. Ofrecemos nuestro servicio de coctelería para eventos en Donostia-San Sebastián y diferentes localidades de Gipuzkoa. Al solicitar presupuesto indícanos dónde se celebrará el evento para preparar una propuesta adecuada.'
    ],
    [
        'question' => '¿Podemos elegir los cócteles que se servirán en el evento?',
        'answer' => 'Sí. Podemos crear una carta adaptada al evento, combinando cócteles clásicos con propuestas personalizadas. También podemos adaptar la selección al estilo de la celebración y a las preferencias de los invitados.'
    ],
    [
        'question' => '¿Preparáis cócteles sin alcohol?',
        'answer' => 'Sí. Podemos incorporar mocktails y otras opciones sin alcohol para que todos los invitados puedan disfrutar de la experiencia de coctelería.'
    ],
    [
        'question' => '¿Cuántos bartenders necesitamos para nuestro evento?',
        'answer' => 'Depende principalmente del número de invitados, la duración del servicio y el tipo de carta seleccionada. Al preparar el presupuesto calculamos el equipo necesario para mantener un servicio ágil durante todo el evento.'
    ],
    [
        'question' => '¿Lleváis vuestra propia barra y material de coctelería?',
        'answer' => 'Podemos encargarnos del montaje y del material necesario para prestar el servicio. La propuesta se adapta al espacio disponible y a las características del evento.'
    ],
    [
        'question' => '¿Con cuánta antelación debemos reservar?',
        'answer' => 'Recomendamos reservar con la mayor antelación posible, especialmente para bodas y eventos en fechas de alta demanda. No obstante, puedes consultarnos disponibilidad incluso para eventos próximos.'
    ],
    [
        'question' => '¿Cómo se calcula el precio del servicio de coctelería?',
        'answer' => 'El presupuesto depende de factores como el número de invitados, duración del servicio, ubicación, selección de cócteles, personal necesario y características del montaje. Por eso preparamos cada presupuesto de manera personalizada.'
    ],
    [
        'question' => '¿Cómo puedo solicitar presupuesto para mi evento?',
        'answer' => 'Solo tienes que indicarnos la fecha, lugar del evento, número aproximado de invitados y tipo de celebración. Con esa información podremos preparar una propuesta de coctelería adaptada a tu evento.'
    ],
];

?>

<main class="home-page">

    <?php require __DIR__ . '/../components/hero.php'; ?>

    <?php require __DIR__ . '/../components/experiences.php'; ?>

    <?php require __DIR__ . '/../components/services.php'; ?>

    <?php require __DIR__ . '/../components/gallery.php'; ?>

    <?php require __DIR__ . '/../components/about.php'; ?>

    <?php require __DIR__ . '/../components/process.php'; ?>

    <?php require __DIR__ . '/../components/reviews.php'; ?>

    <?php require __DIR__ . '/../components/faq.php'; ?>

    <?php require __DIR__ . '/../components/cta.php'; ?>

</main>

