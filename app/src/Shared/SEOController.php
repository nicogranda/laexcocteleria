<?php
namespace App\Shared;

use App\Domains\Card\Card;

class SEOController
{
    private string $brand;
    private string $baseUrl;
    private string $defaultImage;
    private string $city;
    private string $state;

    private string $author;
    private string $publisher;
    private string $robots;

    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?? $GLOBALS['mysqli'] ?? null;

        $host = $_SERVER['HTTP_HOST'] ?? '';
        $this->baseUrl = strpos($host, 'localhost') !== false
            ? 'http://localhost:8888/laexcocteleria'
            : $_ENV['APP_URL'] ?? 'https://laexcocteleria';

        $this->brand = $_ENV['APP_NAME'] ?? 'Borjas Design';
        $this->defaultImage = $this->baseUrl . '/assets/img/logo/laex-cocteleria-logo.png';
        $this->city = $_ENV['APP_CITY'] ?? 'Katy';
        $this->state = $_ENV['APP_STATE'] ?? 'Texas';

        $this->author = $_ENV['SEO_AUTHOR'] ?? $this->brand;
        $this->publisher = $_ENV['SEO_PUBLISHER'] ?? $this->brand;
        $this->robots = $_ENV['SEO_ROBOTS'] ?? 'index, follow';
    }

    public function generate(string $page, ?string $productSlug = null, string $lang = 'EN'): array
    {
        $seo = [
            'home' => [
                'title'       => "Bartender Profesional para Eventos en San Sebastián | {$this->brand}",
                'description' => "Barra de cócteles profesional para bodas, eventos corporativos y celebraciones privadas en San Sebastián y Gipuzkoa. Ingredientes premium y servicio 100% personalizado.",
                'keywords'    => "bartender San Sebastián, barra de cócteles eventos, coctelería para bodas, bartender Gipuzkoa, catering cócteles",
                'url'         => $this->baseUrl,
                'image'       => $this->baseUrl . '/assets/img/hero/bartender-evento-sansebastian.png',
                'canonical'   => $this->baseUrl,
                'author'      => $this->author,
                'publisher'   => $this->publisher,
                'robots'      => $this->robots,
            ],
        ];

        // Productos/cards usando tu modelo DDD
        if (($page === 'card' || $page === 'product') && $productSlug && $this->db) {
            $cardModel = new Card($this->db);
            $product = $cardModel->getBySlug($lang, $productSlug); // ✅ PASAR LANG PRIMERO

            if (!empty($product)) {
                $product = $product[0]; // Tomar el primer registro si hay varios
                $productSlugForUrl = $product['product_slug'] ?? $productSlug;
                $productCategory = $product['category_slug'] ?? $_GET['category'] ?? null;
                $productUrl = $this->baseUrl
                    . ($productCategory ? '/' . urlencode($productCategory) : '')
                    . ($productSlugForUrl ? '/' . urlencode($productSlugForUrl) : '');

                $image = $this->defaultImage;
                if (!empty($product['image_path'])) {
                    $imagePath = $product['image_path'];
                    $image = str_starts_with($imagePath, 'http')
                        ? $imagePath
                        : $this->baseUrl . '/uploads/products/' . ltrim($imagePath, '/');
                }

                $seo[$page] = [
                    'title' => ($product['name'] ?? 'Product') . " | {$this->brand}",
                    'description' => $product['description'] ?? 'Enjoy ' . ($product['name'] ?? 'our jewelry') . " at {$this->brand}",
                    'keywords' => $this->generateKeywords($product),
                    'url' => $productUrl,
                    'image' => $image,
                    'canonical' => $productUrl,
                    'author' => $this->author,
                    'publisher' => $this->publisher,
                    'robots' => $this->robots,
                ];
            }
        }

        // SEO de categorías dinámico
        if (($page === 'portfolio' || $page === 'category') && !empty($_GET['category'])) {
            $categorySlug = strtolower($_GET['category']);
            $categoryName = ucfirst(str_replace('-', ' ', $categorySlug));
            $url = $this->baseUrl . '/' . urlencode($categorySlug);

            $seo['category'] = [
                'title' => "{$categoryName} for Women | {$this->brand}",
                'description' => "Shop trendy {$categoryName} for women at {$this->brand}.",
                'keywords' => strtolower("{$categoryName}, {$categoryName} for women, {$this->brand}"),
                'url' => $url,
                'image' => $this->baseUrl . '/images/portfolio-banner.jpg',
                'canonical' => $url,
                'author' => $this->author,
                'publisher' => $this->publisher,
                'robots' => $this->robots,
            ];
        }

        return $seo[$page] ?? $seo['home'];
    }

    private function generateKeywords($product): string
    {
        $keywords = [];
        if (!empty($product['category_name'])) $keywords[] = strtolower($product['category_name']);
        if (!empty($product['name'])) $keywords[] = strtolower($product['name']);
        if (!empty($product['attribute_value'])) $keywords[] = strtolower($product['attribute_value']);
        return !empty($keywords) ? implode(', ', array_unique($keywords)) : 'jewelry, accessories, fashion';
    }
}
