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
'hero_type' => 'text',
'hero_path' => null,
'hero_eyebrow' => 'Una barra de cócteles para tu celebración en Donostia',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/es/contacto',
'hero_image' => null,
'hero_image_alt' => null,
'hero_text_theme' => 'dark',
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
'hero_type' => 'text',
'hero_path' => null,
'hero_eyebrow' => 'Coordinamos la parte de bebidas de tu evento',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/es/contacto',
'hero_image' => null,
'hero_image_alt' => null,
'hero_text_theme' => 'dark',
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
'hero_type' => 'text',
'hero_path' => null,
'hero_eyebrow' => 'Profesionales al frente de la barra de tu evento',
'hero_cta_text' => 'Solicitar presupuesto',
'hero_cta_url' => '/es/contacto',
'hero_image' => null,
'hero_image_alt' => null,
'hero_text_theme' => 'dark',
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
