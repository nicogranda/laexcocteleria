<?php
namespace App\Models;

require_once __DIR__ . '/../../Shared/Model.php';

use App\Shared\Models\Model;

class Product extends Model
{
    protected string $table = 'products';

    /**
     * Constructor
     * @param \mysqli $mysqli Database connection
     */
    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli, $this->table);
    }

    /**
     * Get product with variants
     */
    public function getProductWithVariants(int $productId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) return null;

        // Get variants
        $sqlVariants = "SELECT * FROM product_variants WHERE product_id = ?";
        $stmtVar = $this->mysqli->prepare($sqlVariants);
        $stmtVar->bind_param('i', $productId);
        $stmtVar->execute();
        $variants = $stmtVar->get_result()->fetch_all(MYSQLI_ASSOC);

        $product['variants'] = $variants;

        return $product;
    }
    /**
     * Search products with optional category, sort, limit, and offset
     */
    public function searchProducts(string $search, $category = '', $sort = '', int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE ?";
        $params = ["%$search%"];
        $types = "s";

        if (!empty($category)) {
            $sql .= " AND category_id = ?";
            $params[] = $category;
            $types .= "i";
        }

        if ($sort === 'price_asc') {
            $sql .= " ORDER BY price ASC";
        } elseif ($sort === 'price_desc') {
            $sql .= " ORDER BY price DESC";
        } else {
            $sql .= " ORDER BY id DESC";
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get total products matching filters
     */
    public function getTotalFiltered(string $search, $category = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE name LIKE ?";
        $params = ["%$search%"];
        $types = "s";

        if (!empty($category)) {
            $sql .= " AND category_id = ?";
            $params[] = $category;
            $types .= "i";
        }

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return (int)($result['total'] ?? 0);
    }
    
    /**
     * Get product and variant data by variant_id
     */
    public function getByProductVariantId(int $variantId): ?array
    {
        // Query para obtener la variante y datos del producto padre
        $sql = "
            SELECT 
                v.id AS variant_id,
                v.product_id,
                v.price,
                v.stock,
                v.sku,
                v.color,
                v.weight,
                v.width,
                v.height,
                v.length,
                p.name AS product_name,
                p.slug AS product_slug,
                p.category_id,
                pm.image_path AS image_path
            FROM product_variants v
            INNER JOIN products p ON p.id = v.product_id
            LEFT JOIN product_media pm ON pm.product_id = p.id AND pm.type = 'image'
            WHERE v.id = ?
            LIMIT 1
        ";
    
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) return null;
    
        $stmt->bind_param('i', $variantId);
        $stmt->execute();
    
        $data = $stmt->get_result()->fetch_assoc();
    
        return $data ?: null;
    }
}