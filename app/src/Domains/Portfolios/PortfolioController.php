<?php
namespace App\Domains\Portfolios;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_status() === PHP_SESSION_NONE && session_start();

use mysqli;

class PortfolioController
{
    private Portfolio $portfolio;
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->portfolio = new Portfolio($mysqli);
    }

    /**
     * Mostrar todos los productos o filtrados por búsqueda
     */
    public function index(string $lang, string $search = ''): void
    {
        if (!empty($search)) {
            $productsRaw = $this->portfolio->searchByString($lang, $search);
        } else {
            $productsRaw = $this->portfolio->getAllByDate($lang);
        }

        $products = $this->groupProducts($productsRaw);

        // Variable $category mínima para evitar warnings en la vista
        $category = ['name' => 'Todos los productos', 'slug' => 'all'];

        include dirname(__DIR__, 3) . '/views/pages/portfolio.php';
    }

    /**
     * Mostrar productos por categoría
     */
    public function show(string $lang, string $categorySlug): void
    {
        $productsRaw = $this->portfolio->getByCategory($lang, $categorySlug);
        
        if (empty($productsRaw)) {
            include dirname(__DIR__, 3) . '/views/pages/404.php';
            return;
        }
    
        $products = $this->groupProducts($productsRaw);
    
        // Extraemos el nombre de la categoría del primer producto del listado
        $categoryName = $productsRaw[0]['category_name'];
        $categoryImage = $productsRaw[0]['category_image'];
    
        $category = [
            'name'  => ucfirst($categoryName),
            'slug'  => $categorySlug,
            'image' => $categoryImage
        ];
    
        include dirname(__DIR__, 3) . '/views/pages/portfolio.php';
    }
    
    /**
     * Agrupa los productos y sus variantes
     */
    private function groupProducts(array $raw): array
    {
        $grouped = [];

        foreach ($raw as $p) {
            $id = $p['product_id'];
            if (!isset($grouped[$id])) {
                $grouped[$id] = $p;
                $grouped[$id]['variants'] = [];
            }

            if (!empty($p['variant_id'])) {
                $grouped[$id]['variants'][] = [
                    'id'    => $p['variant_id'],
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                    'sku'   => $p['variant_sku'],
                    'color' => $p['attribute_value'] ?? ''
                ];
            }
        }

        return array_values($grouped);
    }
}