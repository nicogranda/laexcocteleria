<?php
namespace App\Models;

require_once __DIR__ . '/../../Shared/Model.php';

use App\Shared\Models\Model;

class Cart extends Model
{
    protected string $table = 'orders';

    /**
     * Constructor
     * @param \mysqli $mysqli Database connection
     */
    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli, $this->table);
    }

    public function add(int $variant_id, int $quantity = 1): array
    {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    
        $key = $variant_id; 
    
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key] += $quantity;
            return ['message' => 'Quantity updated'];
        } else {
            $_SESSION['cart'][$key] = $quantity;
            return ['message' => 'Product added to cart'];
        }
    }

    public function getItems(): array
    {
        $items = $_SESSION['cart'] ?? [];
        if (empty($items)) return [];
    
        $productModel = new Product($this->mysqli);
        $detailedItems = [];
    
        foreach ($items as $variantId => $qty) {
            $variantData = $productModel->getByProductVariantId((int)$variantId);
            if (!$variantData) continue;
    
            $detailedItems[$variantId] = [
                'variant_id' => (int)$variantId,
                'product_id' => $variantData['product_id'],
                'name'       => $variantData['product_name'],
                'price'      => (float)$variantData['price'],
                'quantity'   => $qty,
                'weight'     => (float)($variantData['weight'] ?? 0),
                'width'      => (float)($variantData['width'] ?? 0),
                'height'     => (float)($variantData['height'] ?? 0),
                'length'     => (float)($variantData['length'] ?? 0),
                'image'      => $variantData['image_path'] ?? $variantData['product_image'] ?? 'default.jpg'
            ];
        }
    
        return $detailedItems;
    }

    public function removeByKey(string $key): void
    {
        if (isset($_SESSION['cart'][$key])) unset($_SESSION['cart'][$key]);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    public function count(): int
    {
        return array_sum($_SESSION['cart'] ?? []);
    }

    public function getDimensionsSummary(): array
    {
        $items = $this->getItems();
        if (empty($items)) return [
            'total_weight'=>0,
            'total_length'=>0,
            'max_width'=>0,
            'max_height'=>0
        ];

        $total_weight = $total_length = $max_width = $max_height = 0;

        foreach ($items as $item) {
            $qty = $item['qty'] ?? 1;
            $total_weight += ($item['weight'] ?? 0) * $qty;
            $total_length += ($item['height'] ?? 0) * $qty;
            $max_width = max($max_width, $item['width'] ?? 0);
            $max_height = max($max_height, $item['length'] ?? 0);
        }

        return compact('total_weight','total_length','max_width','max_height');
    }
}