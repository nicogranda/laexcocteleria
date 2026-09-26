<head>
<?php

/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN GENERAL
|--------------------------------------------------------------------------
*/

$baseUrl = rtrim($baseUrl ?? ($_ENV['APP_URL'] ?? 'https://laexcocteleria.com'), '/');
$brand = $brand ?? 'La Ex Coctelería';
$page = $page ?? 'home';
$currentUrl = $seoData['url'] ?? $baseUrl;


/*
|--------------------------------------------------------------------------
| SEO PRINCIPAL
|--------------------------------------------------------------------------
*/

$seoTitle = $seoData['title'] ?? 'Bartender para Eventos en San Sebastián | La Ex Coctelería';
$seoDescription = $seoData['description'] ?? 'Servicio de bartenders profesionales y coctelería para bodas, eventos corporativos, cumpleaños y celebraciones en San Sebastián, Gipuzkoa y País Vasco.';
$seoRobots = $seoData['robots'] ?? 'index, follow';
$seoCanonical = $seoData['canonical'] ?? $currentUrl;


/*
|--------------------------------------------------------------------------
| IMÁGENES
|--------------------------------------------------------------------------
*/

$faviconUrl = $baseUrl . '/assets/img/favicon.png?v=6';
$logoUrl = $baseUrl . '/assets/img/logo/laex.svg';
$ogImage = $seoData['og_image'] ?? $baseUrl . '/assets/img/hero/bartender-evento-sansebastian.png';


/*
|--------------------------------------------------------------------------
| OPEN GRAPH
|--------------------------------------------------------------------------
*/

$ogTitle = $seoData['og_title'] ?? $seoTitle;
$ogDescription = $seoData['og_description'] ?? $seoDescription;


/*
|--------------------------------------------------------------------------
| TWITTER / X
|--------------------------------------------------------------------------
*/

$twitterTitle = $seoData['twitter_title'] ?? $seoTitle;
$twitterDescription = $seoData['twitter_description'] ?? $seoDescription;
$twitterImage = $seoData['twitter_image'] ?? $ogImage;


/*
|--------------------------------------------------------------------------
| MARKETING
|--------------------------------------------------------------------------
*/

$fbPixel = $fbPixel ?? ($_ENV['FACEBOOK_PIXEL_ID'] ?? '');
$gaId = $gaId ?? ($_ENV['GOOGLE_ANALYTICS_ID'] ?? '');
$ahrefKey = $ahrefKey ?? ($_ENV['AHREF_KEY'] ?? '');


/*
|--------------------------------------------------------------------------
| DATOS ESTRUCTURADOS
|--------------------------------------------------------------------------
*/

$schemas = [];


/*
|--------------------------------------------------------------------------
| WEBSITE
|--------------------------------------------------------------------------
*/

$schemas[] = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    '@id' => $baseUrl . '/#website',
    'url' => $baseUrl . '/',
    'name' => $brand,
    'description' => $seoDescription,
    'inLanguage' => 'es-ES',
    'publisher' => ['@id' => $baseUrl . '/#organization']
];


/*
|--------------------------------------------------------------------------
| ORGANIZATION
|--------------------------------------------------------------------------
*/

$schemas[] = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    '@id' => $baseUrl . '/#organization',
    'name' => $brand,
    'url' => $baseUrl . '/',
    'logo' => ['@type' => 'ImageObject', 'url' => $logoUrl],
    'description' => $seoDescription
];


/*
|--------------------------------------------------------------------------
| SERVICIO LOCAL
|--------------------------------------------------------------------------
*/

$schemas[] = [
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    '@id' => $baseUrl . '/#business',
    'name' => $brand,
    'url' => $baseUrl . '/',
    'image' => $ogImage,
    'description' => $seoDescription,
    'areaServed' => [
        ['@type' => 'City', 'name' => 'San Sebastián'],
        ['@type' => 'AdministrativeArea', 'name' => 'Gipuzkoa'],
        ['@type' => 'AdministrativeArea', 'name' => 'País Vasco']
    ],
    'knowsAbout' => [
        'Coctelería para eventos',
        'Bartenders para bodas',
        'Bartenders para eventos corporativos',
        'Coctelería para cumpleaños',
        'Cócteles para eventos privados'
    ],
    'parentOrganization' => ['@id' => $baseUrl . '/#organization']
];

?>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- SEO -->
<title><?= htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8') ?></title>
<meta name="description" content="<?= htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8') ?>">
<meta name="robots" content="<?= htmlspecialchars($seoRobots, ENT_QUOTES, 'UTF-8') ?>">
<meta name="author" content="<?= htmlspecialchars($brand, ENT_QUOTES, 'UTF-8') ?>">
<link rel="canonical" href="<?= htmlspecialchars($seoCanonical, ENT_QUOTES, 'UTF-8') ?>">

<!-- FAVICON -->
<link rel="icon" type="image/png" href="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>">

<!-- OPEN GRAPH -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= htmlspecialchars($brand, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($ogDescription, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:url" content="<?= htmlspecialchars($seoCanonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:locale" content="es_ES">

<!-- TWITTER / X -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($twitterTitle, ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($twitterDescription, ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($twitterImage, ENT_QUOTES, 'UTF-8') ?>">

<!-- DATOS ESTRUCTURADOS JSON-LD -->
<script type="application/ld+json">
<?= json_encode($schemas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

<!-- reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- META / FACEBOOK PIXEL -->
<?php if (!empty($fbPixel)): ?>
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init','<?= htmlspecialchars($fbPixel, ENT_QUOTES, 'UTF-8') ?>');
fbq('track','PageView');
</script>
<noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id=<?= urlencode($fbPixel) ?>&ev=PageView&noscript=1"></noscript>
<?php endif; ?>

<!-- GOOGLE ANALYTICS -->
<?php if (!empty($gaId)): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= urlencode($gaId) ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config','<?= htmlspecialchars($gaId, ENT_QUOTES, 'UTF-8') ?>');
</script>
<?php endif; ?>

<!-- AHREFS WEB ANALYTICS -->
<?php if (!empty($ahrefKey)): ?>
<script src="https://analytics.ahrefs.com/analytics.js" data-key="<?= htmlspecialchars($ahrefKey, ENT_QUOTES, 'UTF-8') ?>" async></script>
<?php endif; ?>

</head>
