<?php
namespace App\Domains\Card;

use mysqli;

class Card
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * Obtener un producto completo por slug, con variantes y media
     */
    public function getBySlug(string $lang, string $slug): ?array
    {
        $stmt = $this->mysqli->prepare("
            SELECT p.id AS product_id,
                   pt.name AS name,
                   pt.short_description AS short_description,
                   pt.description AS description,
                   p.slug AS product_slug,
                   c.name AS category_name,
                   c.slug AS category_slug,
                   v.id AS variant_id,
                   v.price,
                   v.stock,
                   v.sku AS variant_sku,
                   v.color AS attribute_value,
                   pm.image_path AS image_path
            FROM products p
            INNER JOIN product_translations pt ON pt.product_id = p.id AND pt.language = ?
            INNER JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_variants v ON v.product_id = p.id
            LEFT JOIN product_media pm ON pm.product_id = p.id AND pm.type = 'image'
            WHERE p.slug = ?
        ");
    
        $stmt->bind_param('ss', $lang, $slug);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    
        return $rows ?: null;
    }

    /**
     * Obtener todos los productos de una categoría con media y variantes
     */
    public function getByCategory(string $lang, string $categorySlug): array
    {
        $stmt = $this->mysqli->prepare("
            SELECT p.id AS product_id,
                   p.name AS name,
                   p.slug AS product_slug,
                   pm.image_path AS image_path,      -- CORRECTO según tu DB
                   c.name AS category_name,
                   c.slug AS category_slug,
                   v.id AS variant_id,
                   v.price,
                   v.stock,
                   v.sku AS variant_sku,
                   v.color AS attribute_value
            FROM products p
            INNER JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_variants v ON v.product_id = p.id
            LEFT JOIN product_media pm ON pm.product_id = p.id AND pm.type = 'image'
            WHERE c.slug = ?
            ORDER BY p.id DESC
        ");

        $stmt->bind_param('s', $categorySlug);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }
}