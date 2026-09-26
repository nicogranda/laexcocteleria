<?php
namespace App\Domains\Policies;

class PolicyData
{
    public static function all(): array
    {
        return [
            'aviso-legal' => [
                'eyebrow' => 'INFORMACIÓN LEGAL',
                'title' => 'Aviso Legal',
                'excerpt' => 'Información legal relativa al uso y acceso al sitio web de La Ex Coctelería.',
                'sections' => [
                    ['title' => 'Titular del sitio web', 'content' => '<p>El presente sitio web corresponde a <strong>La Ex Coctelería</strong>.</p><p>Para cualquier consulta relacionada con este sitio web puedes ponerte en contacto con nosotros a través de los medios disponibles en nuestra página de contacto.</p>'],
                    ['title' => 'Objeto', 'content' => '<p>Este sitio web tiene como finalidad ofrecer información sobre los servicios de coctelería, eventos y experiencias ofrecidos por La Ex Coctelería.</p>'],
                    ['title' => 'Propiedad intelectual', 'content' => '<p>Los textos, fotografías, diseños, logotipos, elementos gráficos y demás contenidos de este sitio web están protegidos por la normativa aplicable en materia de propiedad intelectual e industrial.</p><p>No está permitida su reproducción, distribución o utilización sin autorización previa, salvo en los casos permitidos legalmente.</p>'],
                    ['title' => 'Responsabilidad', 'content' => '<p>La Ex Coctelería procura que la información publicada sea correcta y esté actualizada, aunque no puede garantizar la ausencia absoluta de errores o interrupciones en el funcionamiento del sitio web.</p>'],
                ],
            ],
            'politica-de-privacidad' => [
                'eyebrow' => 'PROTECCIÓN DE DATOS',
                'title' => 'Política de Privacidad',
                'excerpt' => 'Te explicamos qué datos personales podemos recoger, para qué los utilizamos y cuáles son tus derechos.',
                'sections' => [
                    ['title' => 'Responsable del tratamiento', 'content' => '<p>El responsable del tratamiento de los datos recogidos a través de este sitio web es <strong>La Ex Coctelería</strong>. Antes de publicar esta página deben incorporarse los datos identificativos y de contacto del titular.</p>'],
                    ['title' => 'Datos que podemos recoger', 'content' => '<p>Cuando utilizas nuestros formularios podemos solicitar datos como:</p><ul><li>Nombre.</li><li>Correo electrónico.</li><li>Teléfono, cuando sea necesario.</li><li>Información relacionada con el evento o servicio solicitado.</li></ul>'],
                    ['title' => 'Finalidad del tratamiento', 'content' => '<p>Utilizamos tus datos para responder consultas, preparar presupuestos, gestionar solicitudes y prestar los servicios que nos solicites.</p>'],
                    ['title' => 'Conservación de los datos', 'content' => '<p>Los datos personales se conservarán durante el tiempo necesario para gestionar la solicitud y posteriormente durante los plazos exigidos por la legislación aplicable.</p>'],
                    ['title' => 'Tus derechos', 'content' => '<p>Puedes solicitar el acceso, rectificación, supresión, oposición, limitación o portabilidad de tus datos cuando corresponda conforme a la normativa aplicable.</p>'],
                ],
            ],
            'politica-de-cookies' => [
                'eyebrow' => 'COOKIES',
                'title' => 'Política de Cookies',
                'excerpt' => 'Información sobre las cookies y tecnologías similares utilizadas en este sitio web.',
                'sections' => [
                    ['title' => '¿Qué son las cookies?', 'content' => '<p>Las cookies son pequeños archivos que se almacenan en el dispositivo del usuario cuando visita determinados sitios web.</p>'],
                    ['title' => 'Cookies utilizadas', 'content' => '<p>Este sitio puede utilizar cookies técnicas necesarias para su funcionamiento y, cuando corresponda, cookies analíticas o de terceros.</p>'],
                    ['title' => 'Cookies de terceros', 'content' => '<p>Algunos servicios integrados en el sitio web pueden utilizar cookies gestionadas por terceros. Estas cookies estarán sujetas a las políticas de sus respectivos proveedores.</p>'],
                    ['title' => 'Configuración de cookies', 'content' => '<p>Cuando sea necesario obtener consentimiento, podrás aceptar, rechazar o configurar las cookies no esenciales mediante el gestor de consentimiento disponible en el sitio web.</p>'],
                ],
            ],
            'terminos-y-condiciones' => [
                'eyebrow' => 'CONDICIONES DEL SERVICIO',
                'title' => 'Términos y Condiciones',
                'excerpt' => 'Condiciones generales aplicables a la solicitud y contratación de servicios de La Ex Coctelería.',
                'sections' => [
                    ['title' => 'Servicios', 'content' => '<p>La Ex Coctelería ofrece servicios relacionados con coctelería para eventos privados, celebraciones, bodas, eventos corporativos y otras experiencias.</p>'],
                    ['title' => 'Presupuestos', 'content' => '<p>Los precios y condiciones definitivas de cada servicio se establecerán en el presupuesto correspondiente, teniendo en cuenta las características, ubicación, fecha, duración y necesidades del evento.</p>'],
                    ['title' => 'Reserva', 'content' => '<p>La solicitud de información o presupuesto mediante el sitio web no supone por sí misma la reserva definitiva de una fecha.</p>'],
                    ['title' => 'Cancelaciones', 'content' => '<p>Las condiciones específicas de modificación o cancelación podrán establecerse en el presupuesto o acuerdo correspondiente a cada evento.</p>'],
                ],
            ],
        ];
    }

    public static function get(string $slug): ?array
    {
        $slug = trim($slug, '/');
        $policies = self::all();
        return $policies[$slug] ?? null;
    }
}
