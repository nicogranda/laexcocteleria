<?php
// definir URL
define('BASE_URL', 'https://ikusa.net');


//Business Data
    $address='Calle General Freire, Portal 5 Piso 2  Puerta A';
    $city = 'Irún';
    $state = 'Guipuzcoa';
    $country = 'España';
    $zip = '20303';
    $phone='+34 600 142663';
    
//Social Media
    $user_facebook = "ikusa.creativestudio";
    $user_instagram = "ikusa.creativestudio";
    $user_youtube = "@ikusa.creativestudio";
    $user_tiktok = "ikusa.creativestudio";
    $user_x = "ikusa.ads";
    
//Claves de Stripe
$config = [
    'stripe' => [
        'public_key' => 'pk_test_51Nf0nSIXNNwsG0dJB9IF009nmkeQEb76LwK7o9UUgacRTCIN3Gy2Hij6HlMxOfJdkBjISoTLTZhCSgb4C36bE7vO00cSsZtqZ0', // Tu clave pública de Stripe
        'secret_key' => 'sk_test_51Nf0nSIXNNwsG0dJIhNUmvRvLx6sifBMvMBSMiTuOoXnNvXXMB63FsmXutzRHAKfmtyQWeW1uFXsvOnMfWrDT0Gk00eHuTxoRM'  // Tu clave secreta de Stripe
    ]
];

// Definir las claves de reCAPTCHA
define('RECAPTCHA_SITE_KEY', '6LfkueIqAAAAAKQBc2Qkp--Bm7jH8rkbJSXtxga0'); // Sustituir con tu clave del sitio
define('RECAPTCHA_SECRET_KEY', '6LfkueIqAAAAAICBU7yNUGGpeGoV0QW-BOXew15H'); // Sustituir con tu clave secreta


//Credenciales de USPS
$client_id = "feCHb0DAjhX2Vn3k0ZRwORtrcI7YIJ3TTxQF6N0TUxJZkU18";   // Reemplaza con tu Consumer Key
$client_secret = "GjaVxg6WX4vWodGxJOO1X0e5niYbPbynCSLSYPVVpJ54yMAGFrNYM8QRGRT4kTVe";  // Reemplaza con tu Consumer Secret
$cookie_value = "9D194ACC285DA613E4FFAE0085279CFD~000000000000000000000000000000~YAAQS2vcF/mMmmqUAQAAIZ9Vdho/OTaJVkw2Gjg/+3nZUAKMknhkmogGL1a7p8SMXheKjo6oQ2qxuI54F9JfaVHZu2gEhNKGU+v9IUGsnOsCCiknmOoqA2lGSIsjPvntE/bWugR7Od3whLpB3NBH/AqcFl0NSLW04w5mWnASf+uIP+NLCCIU1GrEq3sGfigsNpCjgCgOg/tml2ALFEbeglQgJdHm9MXadp/J1ZxmF0LmFLeD4CP7CLYOKBg0GCh2J9g23t0jB5USfEZoO8f/6DcraJ7zPYjfYfcBeM3MEG7IoEMtQsX3Ml1hJtn+ms10Bbl/6Ws2e5RL1jRFDwPUEH79rn/AwQG3";

//
$server_url=$_SERVER['HTTP_HOST'];
$baseURL='https://ikusa.net/';
$domain='ikusa.net';
$brand='Ikusa';
$business_name="Ikusa LLC";
 
$brand_url='https://ikusa.net';
$brand_email = "contact@ikusa.net";
$ahref_contact = "<a href='mailto:".$brand_email."'>".$brand_email."</a>";
$link_email_contact = "<a href='mailto:".$brand_email."'>".$brand_email."</a>";

$h21='Diseño Gráfico';
$h22='Branding';
$h23='Diseño Web';
$h24='Community Manager';
$h25='SEO';
$h26='Marketing Digital';


$h31='Diseño Gráfico';
$h32='Branding';
$h33='Diseño Web';
$h34='Community Manager';
$h35='SEO';
$h36='Marketing Digital';

//$h1 = "Atlanta Web Design";

// API URL de geolocalización (usa tu propio token si es necesario)
$ip = $_SERVER['REMOTE_ADDR'];

$h21 = "Our Services";
$h3211 = "Atlanta Web Design";
$h3212 = "SEO";
$h3213 = "PPC";
$h3214 = "UI and UX";
$h3215 = "Digital and Inbound Marketing";
$h3216 = "Content Writing";


$located='Donostia-San Sebastián';

$server_url=$_SERVER['HTTP_HOST'];
    
    $domain = "ikusa.net";
    $logo = 'ikusa.svg';
    
    $profile_name='ikusa';
    $color='#ED1C24';
    
    // function isPageActive($currentPage) {
    //     global $page; // Usa la variable global $page definida en tu archivo
    //     if ($currentPage === $page) {
    //         return ' class="active"';
    //     } else {
    //         return ''; 
    //     }
    // }
?>   
  
