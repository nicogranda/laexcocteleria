<?php

$lang = strtolower($lang ?? 'es');
$lang = in_array($lang, ['es', 'en'], true) ? $lang : 'es';

$policyUrl = '/' . $lang . '/cookies';
?>

<style>
<?php include __DIR__ . '/cookie-consent.css'; ?>
</style>

<script>
<?php
$js = file_get_contents(__DIR__ . '/cookie-consent.js');

/*
 * IMPORTANTE:
 * Un </script> dentro del JS, incluso dentro de un comentario,
 * cierra el elemento <script> en HTML.
 */
$js = str_ireplace('</script>', '<\/script>', $js);

echo $js;
?>
</script>

<script>
CookieConsent.init({
    lang: <?= json_encode($lang) ?>,
    policyUrl: <?= json_encode($policyUrl) ?>,

    onConsentChange: function(consent) {
        if (consent.analytics) {
            // GA4
        }

        if (consent.marketing) {
            // Meta Pixel / Google Ads
        }
    }
});
</script>