<?php
declare(strict_types=1);
namespace App\Domains\Page;
require_once __DIR__ . '/../../Shared/Model.php';

/** Catálogo temporal compatible con pages y page_translations. */
final class Page extends \App\Shared\Models\Model
{
    // Esta fuente en arrays no necesita conexión. Al migrar, usar el constructor del modelo base.
    public function __construct() {}
    private const PAGE = [
[
'id' => 1,
'parent_id' => null,
'type' => 'landing',
'reference' => 'cocteleria-eventos-donostia',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 2,
'parent_id' => null,
'type' => 'landing',
'reference' => 'catering-cocteleria-gipuzkoa',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 3,
'parent_id' => null,
'type' => 'landing',
'reference' => 'bartender-para-eventos',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 4,
'parent_id' => null,
'type' => 'page',
'reference' => 'galeria',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 5,
'parent_id' => null,
'type' => 'landing',
'reference' => 'cocteleria-para-bodas',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 6,
'parent_id' => null,
'type' => 'landing',
'reference' => 'cocteleria-para-eventos-corporativos',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 7,
'parent_id' => null,
'type' => 'landing',
'reference' => 'cocteleria-para-cumpleanos',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 8,
'parent_id' => null,
'type' => 'landing',
'reference' => 'cocteleria-para-despedidas',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 9,
'parent_id' => null,
'type' => 'landing',
'reference' => 'cocteleria-para-graduaciones',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 10,
'parent_id' => null,
'type' => 'landing',
'reference' => 'cocteleria-para-eventos-privados',
'status' => 'published',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
]
];
    private const PAGE_TRANSLATIONS = [
[
'id' => 1,
'page_id' => 1,
'language' => 'es',
'slug' => 'cocteleria-eventos-donostia',
'title' => 'Coctelería para Eventos en Donostia | La Ex',
'h1' => 'Coctelería para eventos en Donostia',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Una barra de cócteles para tu celebración en Donostia',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/hero/bartender-evento-sansebastian.png',
'hero_image_alt' => 'Bartender preparando cócteles en San Sebastián',
'hero_text_theme' => 'light',
'excerpt' => 'En La Ex Coctelería llevamos el servicio de cócteles a tu evento en Donostia-San Sebastián. Adaptamos la carta de bebidas al tipo de celebración y a los gustos de tus invitados.',
'components' => null,
'meta_description' => 'Servicio de coctelería para bodas, empresas y fiestas privadas en Donostia-San Sebastián. Carta de cócteles personalizada. Solicita presupuesto.',
'keywords' => null,
'og_title' => 'Coctelería para Eventos en Donostia | La Ex',
'og_description' => 'Servicio de coctelería para bodas, empresas y fiestas privadas en Donostia-San Sebastián. Carta de cócteles personalizada. Solicita presupuesto.',
'og_image' => null,
'twitter_title' => 'Coctelería para Eventos en Donostia | La Ex',
'twitter_description' => 'Servicio de coctelería para bodas, empresas y fiestas privadas en Donostia-San Sebastián. Carta de cócteles personalizada. Solicita presupuesto.',
'twitter_image' => null,
'canonical_url' => 'https://laexcocteleria.com/cocteleria-eventos-donostia',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Cócteles para bodas y celebraciones privadas</h2><p>Una boda, un cumpleaños o un aniversario tienen ritmos distintos. Cuéntanos cuándo quieres servir los cócteles y qué ambiente buscas para diseñar una propuesta acorde con tu celebración.</p></section>
<section class="landing-section"><h2>Coctelería para eventos de empresa en San Sebastián</h2><p>En presentaciones, inauguraciones y encuentros de empresa, coordinamos contigo el horario del servicio y la selección de cócteles. Podemos plantear una carta adaptada al estilo del encuentro.</p></section>
<section class="landing-section"><h2>Preparar el servicio en tu espacio</h2><p>Indícanos la ubicación, el número aproximado de invitados y las condiciones del espacio. Con esa información concretamos el montaje, el material necesario y el alcance del servicio en el presupuesto.</p></section>',
'faqs' => '[{"question": "¿Trabajáis en Donostia-San Sebastián?", "answer": "Sí. Ofrecemos coctelería para eventos en Donostia-San Sebastián. Indícanos el lugar y la fecha para consultar disponibilidad."}, {"question": "¿Se puede personalizar la carta?", "answer": "Diseñamos una propuesta de cócteles adaptada al evento. Coméntanos tus preferencias y si necesitas opciones sin alcohol."}, {"question": "¿Cómo solicito presupuesto?", "answer": "Envíanos la fecha, el lugar, el número de invitados y la duración aproximada del servicio."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 2,
'page_id' => 2,
'language' => 'es',
'slug' => 'catering-cocteleria-gipuzkoa',
'title' => 'Catering de Coctelería en Gipuzkoa | La Ex',
'h1' => 'Catering de coctelería en Gipuzkoa',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Coordinamos la parte de bebidas de tu evento',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/hero/bartender-evento-sansebastian.png',
'hero_image_alt' => 'Bartender preparando cócteles en San Sebastián',
'hero_text_theme' => 'light',
'excerpt' => 'Nuestro catering de coctelería se centra en las bebidas y su preparación durante el evento. Trabajamos en Donostia-San Sebastián y diferentes localidades de Gipuzkoa, con una propuesta ajustada a la ubicación y a las necesidades de cada celebración.',
'components' => null,
'meta_description' => 'Catering de coctelería para bodas y eventos en Gipuzkoa. Consulta desplazamiento, montaje y carta de cócteles con La Ex Coctelería.',
'keywords' => null,
'og_title' => 'Catering de Coctelería en Gipuzkoa | La Ex',
'og_description' => 'Catering de coctelería para bodas y eventos en Gipuzkoa. Consulta desplazamiento, montaje y carta de cócteles con La Ex Coctelería.',
'og_image' => null,
'twitter_title' => 'Catering de Coctelería en Gipuzkoa | La Ex',
'twitter_description' => 'Catering de coctelería para bodas y eventos en Gipuzkoa. Consulta desplazamiento, montaje y carta de cócteles con La Ex Coctelería.',
'twitter_image' => null,
'canonical_url' => 'https://laexcocteleria.com/catering-cocteleria-gipuzkoa',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Un catering especializado en cócteles</h2><p>Si ya tienes organizado el espacio y la comida, podemos estudiar cómo incorporar una barra de cócteles a la celebración. Este servicio está orientado a la coctelería; no sustituye al catering de comida.</p></section>
<section class="landing-section"><h2>Desplazamiento y montaje en Gipuzkoa</h2><p>La ubicación y el acceso al recinto influyen en la organización del servicio. Indícanos la localidad, las condiciones de acceso y si el evento será en interior o exterior para concretar la viabilidad y el montaje.</p></section>
<section class="landing-section"><h2>Qué debe quedar definido en el presupuesto</h2><p>Acordamos la carta, el horario, el equipo de bartenders y el material previsto. Solicita que la propuesta detalle bebidas, cristalería, hielo, montaje, desmontaje y desplazamiento para conocer el alcance contratado.</p></section>',
'faqs' => '[{"question": "¿Os desplazáis fuera de San Sebastián?", "answer": "Atendemos eventos en diferentes localidades de Gipuzkoa. Consulta tu ubicación y fecha para confirmar disponibilidad y condiciones de desplazamiento."}, {"question": "¿El catering incluye comida?", "answer": "La propuesta de La Ex está centrada en el servicio de coctelería. Si necesitas comida, coordina esa parte con el proveedor de catering de tu evento."}, {"question": "¿Qué información necesitáis del recinto?", "answer": "La dirección, los accesos, el espacio disponible para la barra y si se trata de una celebración en interior o exterior."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 3,
'page_id' => 3,
'language' => 'es',
'slug' => 'bartender-para-eventos',
'title' => 'Bartender para Eventos en San Sebastián | La Ex',
'h1' => 'Bartender para eventos',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Profesionales al frente de la barra de tu evento',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/hero/bartender-evento-sansebastian.png',
'hero_image_alt' => 'Bartender preparando cócteles en San Sebastián',
'hero_text_theme' => 'light',
'excerpt' => 'Un bartender prepara y sirve cócteles, organiza el trabajo de la barra y atiende a los invitados. En La Ex Coctelería planteamos el servicio según la carta, el número de asistentes y el ritmo de tu evento en San Sebastián o Gipuzkoa.',
'components' => null,
'meta_description' => 'Bartenders para bodas, eventos de empresa y fiestas privadas en San Sebastián y Gipuzkoa. Consulta disponibilidad y solicita tu propuesta.',
'keywords' => null,
'og_title' => 'Bartender para Eventos en San Sebastián | La Ex',
'og_description' => 'Bartenders para bodas, eventos de empresa y fiestas privadas en San Sebastián y Gipuzkoa. Consulta disponibilidad y solicita tu propuesta.',
'og_image' => null,
'twitter_title' => 'Bartender para Eventos en San Sebastián | La Ex',
'twitter_description' => 'Bartenders para bodas, eventos de empresa y fiestas privadas en San Sebastián y Gipuzkoa. Consulta disponibilidad y solicita tu propuesta.',
'twitter_image' => null,
'canonical_url' => 'https://laexcocteleria.com/bartender-para-eventos',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Barman y cocteleros para tu celebración</h2><p>Si buscas un barman para una fiesta o cocteleros para una boda, cuéntanos qué servicio necesitas. Podemos estudiar la propuesta de atención en barra y la preparación de bebidas para tu evento.</p></section>
<section class="landing-section"><h2>Cuántos bartenders necesita tu evento</h2><p>El número de profesionales depende de los invitados, la complejidad de los cócteles y los momentos de mayor demanda. Una recepción concentrada en poco tiempo exige una planificación distinta a un servicio repartido durante la celebración.</p></section>
<section class="landing-section"><h2>Coordinar el equipo y la carta de bebidas</h2><p>Antes del evento definimos el horario, la selección de cócteles y el material acordado. Si el recinto ya dispone de barra o tienes otro proveedor de bebidas, indícalo para estudiar cómo coordinar el servicio.</p></section>',
'faqs' => '[{"question": "¿Puedo contratar solo al bartender?", "answer": "Indícanos si ya dispones de barra, bebidas y material. Revisaremos las condiciones para confirmar qué modalidad de servicio podemos ofrecerte."}, {"question": "¿Cuántos profesionales necesito?", "answer": "Te proponemos el equipo después de conocer el número de invitados, la duración y la carta de bebidas."}, {"question": "¿Atendéis bodas y eventos de empresa?", "answer": "Sí. Preparamos propuestas para bodas, eventos corporativos y fiestas privadas según la fecha y disponibilidad."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 4,
'page_id' => 4,
'language' => 'es',
'slug' => 'galeria',
'title' => 'Galería de Eventos | La Ex Coctelería',
'h1' => 'Galería de eventos',
'hero_type' => 'text',
'hero_path' => null,
'hero_eyebrow' => null,
'hero_cta_text' => null,
'hero_cta_url' => null,
'hero_image' => null,
'hero_image_alt' => null,
'hero_text_theme' => 'dark',
'excerpt' => 'Descubre momentos de los eventos en los que hemos servido cócteles.',
'components' => 'Gallery',
'meta_description' => 'Fotografías de cócteles y eventos de La Ex Coctelería en San Sebastián y Gipuzkoa.',
'keywords' => null,
'og_title' => 'Galería de Eventos | La Ex Coctelería',
'og_description' => 'Fotografías de cócteles y eventos de La Ex Coctelería en San Sebastián y Gipuzkoa.',
'og_image' => null,
'twitter_title' => 'Galería de Eventos | La Ex Coctelería',
'twitter_description' => 'Fotografías de cócteles y eventos de La Ex Coctelería en San Sebastián y Gipuzkoa.',
'twitter_image' => null,
'canonical_url' => 'https://laexcocteleria.com/galeria',
'robots' => 'index,follow',
'content' => '',
'faqs' => '[]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 5,
'page_id' => 5,
'language' => 'es',
'slug' => 'cocteleria-para-bodas',
'title' => 'Coctelería para bodas | La Ex',
'h1' => 'Coctelería para bodas en San Sebastián',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Una barra de cócteles a la altura de vuestro día',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/services/bodas.png',
'hero_image_alt' => 'Coctelería para bodas',
'hero_text_theme' => 'light',
'excerpt' => 'Desde la recepción hasta la fiesta, diseñamos una propuesta de coctelería que acompañe vuestra boda en Donostia-San Sebastián y Gipuzkoa. Elegimos con vosotros las bebidas y el momento del servicio para que la barra forme parte de la celebración.',
'components' => null,
'meta_description' => 'Coctelería para bodas en San Sebastián y Gipuzkoa. Carta personalizada, bartenders y montaje según tu evento. Consulta disponibilidad.',
'keywords' => null,
'og_title' => 'Coctelería para bodas | La Ex',
'og_description' => 'Coctelería para bodas en San Sebastián y Gipuzkoa. Carta personalizada, bartenders y montaje según tu evento. Consulta disponibilidad.',
'og_image' => 'https://laexcocteleria.com/assets/img/services/bodas.png',
'twitter_title' => 'Coctelería para bodas | La Ex',
'twitter_description' => 'Coctelería para bodas en San Sebastián y Gipuzkoa. Carta personalizada, bartenders y montaje según tu evento. Consulta disponibilidad.',
'twitter_image' => 'https://laexcocteleria.com/assets/img/services/bodas.png',
'canonical_url' => 'https://laexcocteleria.com/cocteleria-para-bodas',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Una carta con vuestra personalidad</h2><p>Podéis combinar cócteles clásicos con una selección pensada para vuestros gustos. Si queréis una bebida protagonista para la boda, contadnos vuestra idea y estudiaremos cómo incorporarla a la carta.</p></section>
<section class="landing-section"><h2>Del aperitivo a la celebración</h2><p>Un cóctel de bienvenida y una barra durante la fiesta requieren una organización distinta. Definimos el horario con vosotros y coordinamos el servicio con los responsables del espacio y los demás proveedores.</p></section>
<section class="landing-section"><h2>Una propuesta clara para vuestra boda</h2><p>Para preparar el presupuesto necesitamos la fecha, el lugar, el número aproximado de invitados y la duración. Concretamos personal, material, bebidas y montaje según las condiciones del recinto.</p></section>',
'faqs' => '[{"question": "¿Podemos incluir cócteles sin alcohol?", "answer": "Sí. Podemos incorporar mocktails para quienes prefieran bebidas sin alcohol. Acordamos la selección al preparar vuestra carta."}, {"question": "¿Podéis coordinaros con la finca o el restaurante?", "answer": "Sí. Indícanos el espacio y la persona responsable para revisar accesos, montaje y horarios del servicio."}, {"question": "¿Con cuánta antelación debemos consultar?", "answer": "Cuanto antes conozcamos la fecha, antes podremos confirmar disponibilidad y estudiar vuestra propuesta."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 6,
'page_id' => 6,
'language' => 'es',
'slug' => 'cocteleria-para-eventos-corporativos',
'title' => 'Coctelería para eventos corporativos | La Ex',
'h1' => 'Coctelería para eventos corporativos en San Sebastián',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Cócteles que acompañan las conversaciones',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/services/eventos-corporativos.png',
'hero_image_alt' => 'Coctelería para eventos corporativos',
'hero_text_theme' => 'light',
'excerpt' => 'Una presentación, una inauguración o un encuentro de empresa necesita un servicio que encaje con su ritmo. En La Ex Coctelería preparamos propuestas para eventos corporativos en San Sebastián y Gipuzkoa, con una carta y una atención en barra adaptadas al encuentro.',
'components' => null,
'meta_description' => 'Coctelería para eventos de empresa en San Sebastián y Gipuzkoa. Presentaciones, inauguraciones y fiestas corporativas. Solicita presupuesto.',
'keywords' => null,
'og_title' => 'Coctelería para eventos corporativos | La Ex',
'og_description' => 'Coctelería para eventos de empresa en San Sebastián y Gipuzkoa. Presentaciones, inauguraciones y fiestas corporativas. Solicita presupuesto.',
'og_image' => 'https://laexcocteleria.com/assets/img/services/eventos-corporativos.png',
'twitter_title' => 'Coctelería para eventos corporativos | La Ex',
'twitter_description' => 'Coctelería para eventos de empresa en San Sebastián y Gipuzkoa. Presentaciones, inauguraciones y fiestas corporativas. Solicita presupuesto.',
'twitter_image' => 'https://laexcocteleria.com/assets/img/services/eventos-corporativos.png',
'canonical_url' => 'https://laexcocteleria.com/cocteleria-para-eventos-corporativos',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Presentaciones e inauguraciones</h2><p>La recepción de invitados suele concentrar la demanda en poco tiempo. Planificamos contigo el inicio del servicio, la selección de bebidas y el equipo necesario según el aforo y el horario.</p></section>
<section class="landing-section"><h2>Una carta acorde con tu empresa</h2><p>Cuéntanos el estilo del evento y el perfil de los asistentes. Estudiamos una propuesta de cócteles y alternativas sin alcohol que acompañe el encuentro y su tono.</p></section>
<section class="landing-section"><h2>Coordinación con la organización</h2><p>Revisamos la ubicación de la barra, los accesos para el montaje y los tiempos del programa. El presupuesto concreta el alcance del servicio para que puedas coordinarlo con tu equipo o agencia.</p></section>',
'faqs' => '[{"question": "¿Atendéis fiestas de empresa?", "answer": "Sí. Preparamos propuestas para fiestas de empresa, inauguraciones y presentaciones según la fecha, el lugar y el número de asistentes."}, {"question": "¿Podéis adaptar las bebidas al estilo del evento?", "answer": "Podemos estudiar una carta acorde con el encuentro. Comparte tus preferencias y las necesidades de los asistentes."}, {"question": "¿Qué necesitáis para presupuestar?", "answer": "Fecha, recinto, asistentes previstos, duración y programa aproximado, especialmente los momentos de recepción y pausas."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 7,
'page_id' => 7,
'language' => 'es',
'slug' => 'cocteleria-para-cumpleanos',
'title' => 'Coctelería para cumpleaños | La Ex',
'h1' => 'Coctelería para cumpleaños en San Sebastián',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Tu celebración, tu carta de cócteles',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/services/cumpleanos.png',
'hero_image_alt' => 'Coctelería para cumpleanos',
'hero_text_theme' => 'light',
'excerpt' => 'Reúne a tus amigos y celebra con cócteles preparados durante la fiesta. Diseñamos propuestas para cumpleaños de adultos en San Sebastián y Gipuzkoa, desde encuentros pequeños hasta celebraciones con más invitados.',
'components' => null,
'meta_description' => 'Servicio de coctelería para cumpleaños en San Sebastián y Gipuzkoa. Cócteles personalizados para tu fiesta. Consulta disponibilidad.',
'keywords' => null,
'og_title' => 'Coctelería para cumpleaños | La Ex',
'og_description' => 'Servicio de coctelería para cumpleaños en San Sebastián y Gipuzkoa. Cócteles personalizados para tu fiesta. Consulta disponibilidad.',
'og_image' => 'https://laexcocteleria.com/assets/img/services/cumpleanos.png',
'twitter_title' => 'Coctelería para cumpleaños | La Ex',
'twitter_description' => 'Servicio de coctelería para cumpleaños en San Sebastián y Gipuzkoa. Cócteles personalizados para tu fiesta. Consulta disponibilidad.',
'twitter_image' => 'https://laexcocteleria.com/assets/img/services/cumpleanos.png',
'canonical_url' => 'https://laexcocteleria.com/cocteleria-para-cumpleanos',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Una carta para compartir con tus invitados</h2><p>Cuéntanos qué bebidas te gustan y cómo imaginas la fiesta. Acordamos una selección de cócteles que se adapte al grupo, con opciones sin alcohol si las necesitas.</p></section>
<section class="landing-section"><h2>En casa o en el espacio de tu fiesta</h2><p>Indícanos si celebrarás el cumpleaños en una vivienda, un jardín o un recinto reservado. Revisamos el espacio de trabajo, los accesos y las condiciones necesarias antes de confirmar el montaje.</p></section>
<section class="landing-section"><h2>Un servicio ajustado a la celebración</h2><p>El número de invitados y la duración nos ayudan a dimensionar la barra. Te proponemos el equipo y los materiales acordes con el horario para que el servicio acompañe la fiesta.</p></section>',
'faqs' => '[{"question": "¿Podéis atender un cumpleaños en casa?", "answer": "Consulta tu ubicación y las condiciones del espacio. Revisaremos accesos y zona disponible para confirmar si podemos prestar el servicio."}, {"question": "¿Hay un mínimo de invitados?", "answer": "Cuéntanos el tamaño del grupo y la duración prevista. Estudiaremos la viabilidad y prepararemos una propuesta personalizada."}, {"question": "¿Podemos elegir los cócteles?", "answer": "Sí. Adaptamos la selección a tus preferencias y al tipo de celebración."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 8,
'page_id' => 8,
'language' => 'es',
'slug' => 'cocteleria-para-despedidas',
'title' => 'Coctelería para despedidas | La Ex',
'h1' => 'Coctelería para despedidas en San Sebastián',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Un brindis para celebrar con vuestro grupo',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/services/despedidas.png',
'hero_image_alt' => 'Coctelería para despedidas',
'hero_text_theme' => 'light',
'excerpt' => 'Preparamos propuestas de cócteles para despedidas de soltera y soltero en Donostia-San Sebastián y Gipuzkoa. Cuéntanos cómo será el encuentro y estudiaremos un servicio de barra que encaje con vuestro plan.',
'components' => null,
'meta_description' => 'Servicio de cócteles para despedidas de soltera y soltero en San Sebastián y Gipuzkoa. Cuéntanos tu plan y solicita presupuesto.',
'keywords' => null,
'og_title' => 'Coctelería para despedidas | La Ex',
'og_description' => 'Servicio de cócteles para despedidas de soltera y soltero en San Sebastián y Gipuzkoa. Cuéntanos tu plan y solicita presupuesto.',
'og_image' => 'https://laexcocteleria.com/assets/img/services/despedidas.png',
'twitter_title' => 'Coctelería para despedidas | La Ex',
'twitter_description' => 'Servicio de cócteles para despedidas de soltera y soltero en San Sebastián y Gipuzkoa. Cuéntanos tu plan y solicita presupuesto.',
'twitter_image' => 'https://laexcocteleria.com/assets/img/services/despedidas.png',
'canonical_url' => 'https://laexcocteleria.com/cocteleria-para-despedidas',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>La barra dentro de vuestro plan</h2><p>Si ya habéis reservado un espacio para la despedida, revisamos cómo incorporar el servicio de bebidas. Acordamos cuándo empieza la coctelería y cuánto durará para coordinarla con el resto de actividades.</p></section>
<section class="landing-section"><h2>Cócteles para los gustos del grupo</h2><p>Podéis compartir vuestras preferencias y proponer una selección para la celebración. También podemos incluir bebidas sin alcohol para quienes las prefieran.</p></section>
<section class="landing-section"><h2>Organizarlo desde el primer mensaje</h2><p>Indícanos fecha, ubicación y número de personas. Si todavía no tenéis un espacio, acláralo: la propuesta de coctelería necesita un lugar adecuado y no supone la reserva de un local.</p></section>',
'faqs' => '[{"question": "¿Organizáis toda la despedida?", "answer": "Nuestro servicio se centra en la coctelería. El espacio y las demás actividades deben concretarse con sus respectivos proveedores."}, {"question": "¿Ofrecéis talleres de cócteles?", "answer": "Esta página describe el servicio de barra para eventos. Si buscas un taller, consúltanos para confirmar qué opciones podemos ofrecerte."}, {"question": "¿Podéis servir bebidas sin alcohol?", "answer": "Sí. Podemos incorporar mocktails a la carta acordada para el grupo."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 9,
'page_id' => 9,
'language' => 'es',
'slug' => 'cocteleria-para-graduaciones',
'title' => 'Coctelería para graduaciones | La Ex',
'h1' => 'Coctelería para graduaciones en San Sebastián',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Celebrad el comienzo de una nueva etapa',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/services/graduaciones.png',
'hero_image_alt' => 'Coctelería para graduaciones',
'hero_text_theme' => 'light',
'excerpt' => 'Una graduación reúne a compañeros, amigos y familias. Diseñamos propuestas de coctelería para celebraciones en San Sebastián y Gipuzkoa, teniendo en cuenta el espacio, los asistentes y el formato del encuentro.',
'components' => null,
'meta_description' => 'Coctelería para graduaciones en San Sebastián y Gipuzkoa. Propuestas de bebidas y cócteles sin alcohol para tu celebración.',
'keywords' => null,
'og_title' => 'Coctelería para graduaciones | La Ex',
'og_description' => 'Coctelería para graduaciones en San Sebastián y Gipuzkoa. Propuestas de bebidas y cócteles sin alcohol para tu celebración.',
'og_image' => 'https://laexcocteleria.com/assets/img/services/graduaciones.png',
'twitter_title' => 'Coctelería para graduaciones | La Ex',
'twitter_description' => 'Coctelería para graduaciones en San Sebastián y Gipuzkoa. Propuestas de bebidas y cócteles sin alcohol para tu celebración.',
'twitter_image' => 'https://laexcocteleria.com/assets/img/services/graduaciones.png',
'canonical_url' => 'https://laexcocteleria.com/cocteleria-para-graduaciones',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Una carta adecuada para los asistentes</h2><p>Antes de definir las bebidas, cuéntanos quiénes asistirán a la celebración. Podemos plantear opciones sin alcohol; no se sirven bebidas alcohólicas a menores de edad.</p></section>
<section class="landing-section"><h2>Recepción, cena o fiesta</h2><p>El momento del brindis y el horario de la barra cambian según el programa. Revisamos con la organización dónde se concentrará la demanda para dimensionar el servicio.</p></section>
<section class="landing-section"><h2>Un presupuesto para compartir con el grupo</h2><p>La propuesta detalla el alcance acordado de la coctelería. Envíanos la fecha, el recinto, los asistentes previstos y el tiempo de servicio para estudiar disponibilidad y preparar el presupuesto.</p></section>',
'faqs' => '[{"question": "¿Se pueden preparar solo cócteles sin alcohol?", "answer": "Sí. Podemos estudiar una carta de mocktails para una celebración sin alcohol."}, {"question": "¿Quién debe coordinar el servicio?", "answer": "Es útil contar con una persona responsable del grupo o del recinto para acordar horarios, montaje y necesidades del espacio."}, {"question": "¿Qué necesitáis para consultar disponibilidad?", "answer": "La fecha, la localidad, el recinto y el número aproximado de asistentes, indicando si habrá menores."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
],
[
'id' => 10,
'page_id' => 10,
'language' => 'es',
'slug' => 'cocteleria-para-eventos-privados',
'title' => 'Coctelería para eventos privados | La Ex',
'h1' => 'Coctelería para eventos privados en San Sebastián',
'hero_type' => 'image',
'hero_path' => null,
'hero_eyebrow' => 'Un encuentro especial alrededor de la barra',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/contacto',
'hero_image' => 'assets/img/services/eventos-privados.png',
'hero_image_alt' => 'Coctelería para eventos privados',
'hero_text_theme' => 'light',
'excerpt' => 'Aniversarios, reuniones familiares y celebraciones entre amigos: adaptamos el servicio de coctelería a tu evento privado en Donostia-San Sebastián y Gipuzkoa. Definimos contigo el estilo de bebidas y el horario de la barra.',
'components' => null,
'meta_description' => 'Coctelería para fiestas y eventos privados en San Sebastián y Gipuzkoa. Aniversarios y celebraciones familiares. Solicita tu propuesta.',
'keywords' => null,
'og_title' => 'Coctelería para eventos privados | La Ex',
'og_description' => 'Coctelería para fiestas y eventos privados en San Sebastián y Gipuzkoa. Aniversarios y celebraciones familiares. Solicita tu propuesta.',
'og_image' => 'https://laexcocteleria.com/assets/img/services/eventos-privados.png',
'twitter_title' => 'Coctelería para eventos privados | La Ex',
'twitter_description' => 'Coctelería para fiestas y eventos privados en San Sebastián y Gipuzkoa. Aniversarios y celebraciones familiares. Solicita tu propuesta.',
'twitter_image' => 'https://laexcocteleria.com/assets/img/services/eventos-privados.png',
'canonical_url' => 'https://laexcocteleria.com/cocteleria-para-eventos-privados',
'robots' => 'index,follow',
'content' => '<section class="landing-section"><h2>Celebraciones con su propio ritmo</h2><p>Un aperitivo familiar y una fiesta de noche tienen necesidades diferentes. Cuéntanos el programa del encuentro para plantear la carta y el momento del servicio.</p></section>
<section class="landing-section"><h2>Una propuesta para todos los gustos</h2><p>Podemos combinar cócteles con opciones sin alcohol. Si hay preferencias o necesidades concretas, coméntalas antes para estudiar la selección de ingredientes y bebidas.</p></section>
<section class="landing-section"><h2>La barra en el espacio de tu evento</h2><p>Revisamos contigo la ubicación, el espacio disponible y las condiciones del montaje. Si el recinto ya cuenta con barra o material, indícalo para concretar qué debe incluir nuestra propuesta.</p></section>',
'faqs' => '[{"question": "¿Atendéis aniversarios y reuniones familiares?", "answer": "Sí. Estudiamos propuestas para distintas celebraciones privadas según la ubicación, la fecha y el número de invitados."}, {"question": "¿Se puede personalizar el servicio?", "answer": "Adaptamos la carta y el alcance de la propuesta al formato del evento. Cuéntanos tus preferencias al solicitar presupuesto."}, {"question": "¿Cómo se calcula el precio?", "answer": "Influyen los asistentes, la duración, las bebidas seleccionadas, el equipo, el montaje y el desplazamiento. Por eso presupuestamos cada evento de forma individual."}]',
'created_at' => '2026-10-01 00:00:00',
'updated_at' => '2026-10-01 00:00:00'
]
];

    public function rows(): array
    {
        return ['page' => self::PAGE, 'page_translations' => self::PAGE_TRANSLATIONS];
    }

    public function findBySlug(string $slug, string $language = 'es'): ?array
    {
        foreach (self::PAGE_TRANSLATIONS as $translation) {
            if ($translation['slug'] !== $slug || $translation['language'] !== strtolower($language)) continue;
            foreach (self::PAGE as $page) {
                if ($page['id'] === $translation['page_id'] && $page['status'] === 'published') {
                    return ['page' => $page, 'translation' => $translation];
                }
            }
        }
        return null;
    }
}

