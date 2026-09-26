<?php
namespace App\Domains\Card;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_status() === PHP_SESSION_NONE && session_start();

use mysqli;

// Incluir el modelo Card
require_once __DIR__ . '/Card.php';

class CardController
{
    private Card $cardModel;
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->cardModel = new Card($mysqli); // Ahora sí existe la clase
    }

    public function show(string $lang, ?string $slug): void
    {
        if (!$slug) {
            include dirname(__DIR__, 3) . '/views/pages/404.php';
            return;
        }

        $productCard = $this->cardModel->getBySlug($lang, $slug);

        if (!$productCard) {
            include dirname(__DIR__, 3) . '/views/pages/404.php';
            return;
        }
        
        // Extraemos el primer producto de la respuesta para la vista
        $product = $productCard[0] ?? null;
        $variants = $productCard ?: [];
        
        $category = [
            'name' => $product['category_name'] ?? 'Producto',
            'slug' => $product['category_slug'] ?? 'product'
        ];
        
        include dirname(__DIR__, 3) . '/views/pages/card.php';
    }
}