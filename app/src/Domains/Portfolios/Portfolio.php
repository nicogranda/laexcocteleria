<?php
namespace App\Domains\Portfolios;

require_once __DIR__ . '/../../Shared/Model.php';


use App\Shared\Model; // tu Model genérico de DDD
use mysqli;
use Exception;

class Portfolio extends \App\Shared\Models\Model
{
    protected string $table = 'products'; // tabla principal

    public function __construct(mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    public function getAllByDate(string $lang): array
    {
        $sql = "
        SELECT 
            p.id AS product_id,
            pt.language,
            pt.name,
            pt.slug AS product_slug,
            pt.description,
            p.unit,
            p.category_id,
            p.vat_rate,
            p.created_at,
    
            v.id AS variant_id,
            v.sku AS variant_sku,
            v.price,
            v.stock,
            v.weight,
            v.image_url,
            v.is_active,
    
            pm.image_path,   
            pa.id AS attribute_id,
            pa.attribute,
            pa.attribute_value,
    
            ct.name AS category_name,
            ct.slug AS category_slug
    
        FROM products p
        INNER JOIN product_translations pt 
            ON pt.product_id = p.id 
            AND pt.language = ?
        LEFT JOIN product_variants v ON v.product_id = p.id
        LEFT JOIN variant_attributes pa ON pa.variant_id = v.id
        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN category_translations ct 
            ON ct.category_id = c.id 
            AND ct.language = ?
        LEFT JOIN product_media pm 
            ON pm.product_id = p.id 
           AND pm.type = 'image'
           AND pm.position = 0
        ORDER BY p.created_at DESC, p.id DESC, v.id DESC
        ";
    
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) return [];
    
        $stmt->bind_param("ss", $lang, $lang);  // dos parámetros ahora
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $products;
    }
    
    public function getByCategory(string $lang, string $categorySlug): array
    {
        $sql = "
        SELECT 
            p.id AS product_id,
            pt.name,
            pt.description,
            pt.short_description,
            pt.slug AS product_slug,
    
            v.id AS variant_id,
            v.sku AS variant_sku,
            v.price,
            v.stock,
            v.weight,
            v.image_url,
            v.is_active,
    
            pa.id AS attribute_id,
            pa.attribute,
            pa.attribute_value,
    
            c.id AS category_id,
            c.image_url AS category_image,
            ct.name AS category_name,
            ct.slug AS category_slug,
    
            pm.id AS media_id,
            pm.type AS media_type,
            pm.image_path,
            pm.position,
            pm.uploaded_at
    
        FROM category_translations ct
    
        INNER JOIN categories c 
            ON c.id = ct.category_id
    
        INNER JOIN products p 
            ON p.category_id = c.id
    
        INNER JOIN product_translations pt 
            ON pt.product_id = p.id AND pt.language = ?
    
        LEFT JOIN product_variants v 
            ON v.product_id = p.id
    
        LEFT JOIN variant_attributes pa 
            ON pa.variant_id = v.id
    
        LEFT JOIN product_media pm 
            ON pm.product_id = p.id
    
        WHERE ct.language = ?
          AND ct.slug = ?
    
        ORDER BY p.id, v.id, pm.position
        ";
    
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) return [];
    
        $stmt->bind_param("sss", $lang, $lang, $categorySlug);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
    
        return $products;
    }

    public function searchByString(string $lang, string $search): array
    {
        $searchLike = "%$search%";

        $sql = "
        SELECT 
            p.id AS product_id,
            p.language,
            p.name,
            p.slug AS product_slug,
            p.description,
            p.unit,
            p.category_id,
            p.vat_rate,
            v.id AS variant_id,
            v.sku AS variant_sku,
            v.price,
            v.stock,
            v.weight,
            v.image_url,
            v.is_active,
            pa.id AS attribute_id,
            pa.attribute,
            pa.attribute_value,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        LEFT JOIN product_variants v ON v.product_id = p.id
        LEFT JOIN variant_attributes pa ON pa.variant_id = v.id
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.language = ?
          AND (
                p.name LIKE ?
                OR p.description LIKE ?
                OR c.name LIKE ?
              )
        ORDER BY p.created_at DESC, p.id DESC, v.id DESC
        ";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("ssss", $lang, $searchLike, $searchLike, $searchLike);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products;
    }
}