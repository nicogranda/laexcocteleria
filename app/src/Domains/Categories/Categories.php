<?php
namespace App\Domains\Categories;

require_once __DIR__ . '/../../Shared/Model.php';

class Categories extends \App\Shared\Models\Model
{
    // ✅ mantener el tipo string igual que en la clase base
    protected string $table = 'category_translations';

    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    /**
     * Devuelve solo categorías activas según idioma
     */
    public function getByLanguage(string $lang, array $conditions = []): array
    {
        $lang = strtoupper($lang);
        if (!in_array($lang, ['EN','ES'])) $lang = 'EN';
    
        $sql = "
            SELECT 
                ct.id,
                ct.category_id,
                ct.name,
                ct.slug,
                c.image_url AS image
            FROM category_translations ct
            INNER JOIN categories c ON c.id = ct.category_id
            LEFT JOIN category_media cm 
                   ON cm.category_id = ct.category_id 
                  AND cm.is_primary = 1 
                  AND cm.file_type = 'image'
            WHERE ct.language = ? AND c.active = 1
        ";
    
        $params = [$lang];
        $types = "s";
    
        foreach ($conditions as $col => $val) {
            $sql .= " AND ct.{$col} = ?";
            $types .= is_int($val) ? "i" : "s";
            $params[] = $val;
        }
    
        $sql .= " ORDER BY ct.name ASC";
    
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) throw new \Exception("Error prepare SQL: " . $this->mysqli->error);
    
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}