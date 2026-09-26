<?php
$footerContent = [
    'ES' => [
        'description' => 'Bartenders profesionales para eventos en San Sebastián, Gipuzkoa y todo el País Vasco.',
        'copyright' => '© 2026 La Ex Coctelería. Todos los derechos reservados.',
        'columns' => [
            'links' => [
                'title' => 'Enlaces',
                'items' => [
                    ['text' => 'Inicio', 'url' => ''],
                    ['text' => 'Servicios', 'url' => '#servicios'],
                    ['text' => 'Nosotros', 'url' => '#about'],
                    ['text' => 'Galería', 'url' => '#gallery'],
                    ['text' => 'FAQ', 'url' => '#faq']
                ]
            ],
            'services' => [
                'title' => 'Servicios',
                'items' => ['Bodas', 'Eventos Corporativos', 'Cumpleaños', 'Despedidas', 'Eventos Privados']
            ],
            'contact' => [
                'title' => 'Contacto',
                'address' => 'San Sebastián, Gipuzkoa',
                'phone' => '+34 623 11 82 45',
                'email' => 'contacto@laexcocteleria.com',
                'whatsapp' => ['text' => 'WhatsApp', 'url' => 'https://wa.me/34623118245']
            ]
        ],
        'legal' => [
            ['text' => 'Aviso Legal', 'url' => 'es/aviso-legal'],
            ['text' => 'Política de Privacidad', 'url' => '/es/politica-de-privacidad'],
            ['text' => 'Política de Cookies', 'url' => '/es/politica-de-cookies'],
            ['text' => 'Términos y Condiciones', 'url' => '/es/terminos-y-condiciones'],
        ]
    ],

    'EN' => [
        'description' => 'Professional bartenders for events in San Sebastián, Gipuzkoa and the Basque Country.',
        'copyright' => '© 2026 La Ex Coctelería. All rights reserved.',
        'columns' => [
            'links' => [
                'title' => 'Links',
                'items' => [
                    ['text' => 'Home', 'url' => ''],
                    ['text' => 'Services', 'url' => '#servicios'],
                    ['text' => 'About', 'url' => '#about'],
                    ['text' => 'Gallery', 'url' => '#gallery'],
                    ['text' => 'FAQ', 'url' => '#faq']
                ]
            ],
            'services' => [
                'title' => 'Services',
                'items' => ['Weddings', 'Corporate Events', 'Birthdays', 'Farewell Parties', 'Private Events']
            ],
            'contact' => [
                'title' => 'Contact',
                'address' => 'San Sebastián, Gipuzkoa',
                'phone' => '+34 623 11 82 45',
                'email' => 'contacto@laexcocteleria.com',
                'whatsapp' => ['text' => 'WhatsApp', 'url' => 'https://wa.me/34623118245']
            ]
        ],
        'legal' => [
            ['text' => 'Legal Notice', 'url' => 'es/aviso-legal'],
            ['text' => 'Privacy Policy', 'url' => 'es/politica-privacidad'],
            ['text' => 'Cookie Policy', 'url' => 'es/cookies']
        ]
    ]
];

$baseUrl = rtrim($baseUrl ?? '', '/');
$currentLang = strtoupper($lang ?? 'ES');
$footerText = $footerContent[$currentLang] ?? $footerContent['ES'];

$logoUrl = '/assets/img/logo/laex.svg';
$homeUrl = $baseUrl . '/';
?>

<footer class="footer">
    <div class="container">

        <div class="footer-top">

            <div class="footer-brand">
                <a href="<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="La Ex Coctelería" width="120" style="filter: brightness(0) invert(1)">
                </a>

                <p><?= htmlspecialchars($footerText['description'], ENT_QUOTES, 'UTF-8') ?></p>

                <div class="footer-social">
                    <a href="https://www.instagram.com/laexcocteleria/" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/profile.php?id=61576162771683" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?= htmlspecialchars($footerText['columns']['contact']['whatsapp']['url'], ENT_QUOTES, 'UTF-8') ?>" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-column">
                <h3><?= htmlspecialchars($footerText['columns']['links']['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <ul>
                    <?php foreach ($footerText['columns']['links']['items'] as $item):
                        if ($item['url'] === '') {
                            $itemUrl = $homeUrl;
                        } elseif (str_starts_with($item['url'], '#')) {
                            $itemUrl = $homeUrl . $item['url'];
                        } else {
                            $itemUrl = $baseUrl . '/' . ltrim($item['url'], '/');
                        }
                    ?>
                        <li><a href="<?= htmlspecialchars($itemUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-column">
                <h3><?= htmlspecialchars($footerText['columns']['services']['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <ul>
                    <?php foreach ($footerText['columns']['services']['items'] as $service): ?>
                        <li><?= htmlspecialchars($service, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-column">
                <h3><?= htmlspecialchars($footerText['columns']['contact']['title'], ENT_QUOTES, 'UTF-8') ?></h3>

                <ul class="footer-contact">
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?= htmlspecialchars($footerText['columns']['contact']['address'], ENT_QUOTES, 'UTF-8') ?></span>
                    </li>

                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <a href="tel:<?= preg_replace('/\D/', '', $footerText['columns']['contact']['phone']) ?>">
                            <?= htmlspecialchars($footerText['columns']['contact']['phone'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </li>

                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:<?= htmlspecialchars($footerText['columns']['contact']['email'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($footerText['columns']['contact']['email'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </li>
                </ul>

                <a class="footer-whatsapp" href="<?= htmlspecialchars($footerText['columns']['contact']['whatsapp']['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-whatsapp"></i>
                    <?= htmlspecialchars($footerText['columns']['contact']['whatsapp']['text'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>

        </div>

        <div class="footer-bottom">
            <span><?= htmlspecialchars($footerText['copyright'], ENT_QUOTES, 'UTF-8') ?></span>

            <nav>
                <?php foreach ($footerText['legal'] as $key => $legal):
                    $legalUrl = $baseUrl . '/' . ltrim($legal['url'], '/');
                ?>
                    <a href="<?= htmlspecialchars($legalUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($legal['text'], ENT_QUOTES, 'UTF-8') ?></a>
                    <?php if ($key < count($footerText['legal']) - 1): ?><span>|</span><?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div>

    </div>
</footer>

<style>
.footer{background:#980c28;color:#fff}
.footer .container{max-width:1400px;margin:auto;padding:70px 20px 25px}
.footer-top{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:60px}

.footer-brand img{display:block;width:120px;height:auto;margin-bottom:20px}
.footer-brand p{max-width:260px;line-height:1.7}

.footer-social{display:flex;gap:18px;margin-top:25px}
.footer-social a{color:#fff;font-size:22px;transition:opacity .3s ease}
.footer-social a:hover{opacity:.7}

.footer-column h3{margin-bottom:18px;text-transform:uppercase;font-size:18px}
.footer-column ul{list-style:none;padding:0;margin:0}
.footer-column li{margin-bottom:12px}
.footer-column a{color:#fff;text-decoration:none}
.footer-column a:hover{text-decoration:underline}

.footer-contact li{display:flex;align-items:center;gap:10px}

.footer-whatsapp{display:inline-flex;align-items:center;gap:10px;border:1px solid rgba(255,255,255,.35);border-radius:30px;padding:10px 18px;margin-top:20px;color:#fff;text-decoration:none}
.footer-whatsapp:hover{background:rgba(255,255,255,.1)}

.footer-bottom{border-top:1px solid rgba(255,255,255,.15);margin-top:50px;padding-top:25px;display:flex;justify-content:space-between;align-items:center}
.footer-bottom nav{display:flex;gap:15px;align-items:center;flex-wrap:wrap}
.footer-bottom nav a{color:#fff;text-decoration:none}
.footer-bottom nav a:hover{text-decoration:underline}

@media(max-width:992px){
    .footer-top{grid-template-columns:1fr 1fr}
}

@media(max-width:768px){
    .footer-top{grid-template-columns:1fr;gap:40px}
    .footer-bottom{flex-direction:column;gap:20px;text-align:center}
    .footer-bottom nav{justify-content:center}
}
</style>
