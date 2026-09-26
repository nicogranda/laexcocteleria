<?php

namespace App\Domains\Home;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_status() === PHP_SESSION_NONE && session_start();

require_once __DIR__ . '/../../Shared/Model.php';
require_once __DIR__ . '/../Categories/Categories.php';
require_once __DIR__ . '/../Portfolios/Portfolio.php';

use App\Domains\Portfolios\Portfolio;
use App\Domains\Categories\Categories;

class HomeController
{
    private $portfolio;
    private $category;
    private $mysqli;

    public function __construct()
    {
        global $mysqli;

        $this->mysqli = $mysqli;

        $this->category = new Categories($this->mysqli);
        $this->portfolio = new Portfolio($this->mysqli);
    }


    /**
     * Página principal del Portfolio
     */
    public function index($lang)
    {
        $search = $_GET['search'] ?? '';
        $appName = $_ENV['APP_NAME'];
    
        $categories = $this->category->getByLanguage($lang);
    
        if (!empty($search)) {
            $productsRaw = $this->portfolio->searchByString($lang, $search);
        } else {
            $productsRaw = $this->portfolio->getAllByDate($lang);
        }
    
        $products = $this->groupProducts($productsRaw);
    
        // ✅ Foreach para construir array de categorías desde los productos
        $categoriesFromProducts = [];
        foreach ($productsRaw as $product) {
            $slug = $product['category_slug'] ?? '';
            if (!empty($slug) && !isset($categoriesFromProducts[$slug])) {
                $categoriesFromProducts[$slug] = [
                    'name'  => ucfirst($product['category_name'] ?? ''),
                    'slug'  => $slug,
                    'image' => $product['category_image'] ?? 'placeholder.png',
                ];
            }
        }
        // $categoriesFromProducts = array_values($categoriesFromProducts);
    
        include __DIR__ . '/../../../views/pages/home.php';
    }


    /**
     * Página de categoría
     */
    public function show($lang, $categoryName)
    {
        $productsRaw = $this->portfolio->getByCategory($lang, $categoryName) ?: [];
        $products = $this->groupProducts($productsRaw);
    
        if (empty($products)) {
            include dirname(__DIR__, 3) . '/views/pages/404.php';
            return;
        }
    
        $categories = $this->category->getAll();
    
        $category = [
            'name' => ucfirst($categoryName),
            'slug' => $categoryName
        ];
    
        include __DIR__ . '/../../../views/pages/portfolio.php';
    }

    /**
     * Búsqueda AJAX para products y categories
     */
    public function search($lang)
    {
        header('Content-Type: application/json');
    
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    
        if ($q === '') {
            echo json_encode(['products' => [], 'categories' => []]);
            return;
        }
    
        // Sanitizar
        $qLike = '%' . $this->mysqli->real_escape_string($q) . '%';
    
        // Buscar productos   
        $sqlProducts = "
            SELECT 
                p.id,
                p.name,
                p.slug,
                p.main_image
            FROM products p
            WHERE p.name LIKE '$qLike'
               OR p.description LIKE '$qLike'
            LIMIT 20
        ";
    
        $products = [];
        if ($res = $this->mysqli->query($sqlProducts)) {
            while ($row = $res->fetch_assoc()) {
                $products[] = $row;
            }
        }
    
        // Buscar categorías
        $sqlCategories = "
            SELECT 
                c.id,
                c.name,
                c.slug
            FROM categories c
            WHERE c.name LIKE '$qLike'
            LIMIT 20
        ";
    
        $categories = [];
        if ($res = $this->mysqli->query($sqlCategories)) {
            while ($row = $res->fetch_assoc()) {
                $categories[] = $row;
            }
        }
    
        echo json_encode([
            'products' => $products,
            'categories' => $categories
        ]);
    }


    /**
     * Agrupa variantes por product_id
     */
    private function groupProducts($raw)
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
