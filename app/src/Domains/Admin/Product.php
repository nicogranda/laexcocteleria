<?php
namespace App\Domains\Admin;

require_once __DIR__ . '/../../Shared/Model.php';

use App\Shared\Models\Model;
use mysqli;

class Product extends Model
{
    protected string $table = 'products';

    protected array $deleteRelations = [
        [
            'table' => 'product_media',
            'fk'    => 'product_id',
        ],
        [
            'table' => 'product_variants',
            'fk'    => 'product_id',
        ],
        [
            'join' => true,
            'sql'  => "
                DELETE va
                FROM variant_attributes va
                INNER JOIN product_variants pv
                    ON pv.id = va.variant_id
                WHERE pv.product_id = ?
            "
        ],
    ];

    public function __construct(mysqli $mysqli)
    {
        parent::__construct($mysqli);
        $this->setTable($this->table);
    }

    public function searchProducts(string $search, $category = '', $sort = '', int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE ?";
        $params = ["%$search%"];
        $types = "s";

        if (!empty($category)) {
            $sql .= " AND category_id = ?";
            $params[] = (int)$category;
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

    public function getTotalFiltered(string $search, $category = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE name LIKE ?";
        $params = ["%$search%"];
        $types = "s";

        if (!empty($category)) {
            $sql .= " AND category_id = ?";
            $params[] = (int)$category;
            $types .= "i";
        }

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return (int)($result['total'] ?? 0);
    }

    public function deleteItem(int $id): bool
    {
        foreach ($this->deleteRelations as $relation) {

            if (!empty($relation['join'])) {
                $stmt = $this->mysqli->prepare($relation['sql']);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                continue;
            }

            $sql = "DELETE FROM {$relation['table']} WHERE {$relation['fk']} = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }

        return parent::deleteById($id);
    }
    
    public function getAllPaginated(int $limit, int $offset): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getTotal(?string $search = null): int
    {
        if ($search) {
            $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM {$this->table} WHERE name LIKE ?");
            $like = "%$search%";
            $stmt->bind_param("s", $like);
        } else {
            $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
        }
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return (int)($result['total'] ?? 0);
    }
    
    public function getTranslation(int $id, string $lang)
    {
        $id = intval($id);
        $lang = trim($lang);
    
        $sql = "SELECT * FROM product_translations WHERE product_id = ? AND language = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $id, $lang);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_assoc() ?: null;
    }
    
    public function upsertTranslation(array $data)
    {
        $product_id = intval($data['product_id']);
        $language = trim($data['lang']); 
        $name = $data['name'] ?? '';
        $slug = $data['slug'] ?? '';
        $short_description = $data['short_description'] ?? '';
        $description = $data['description'] ?? '';
        $meta_title = $data['meta_title'] ?? '';
        $meta_description = $data['meta_description'] ?? '';
    
        // Revisar si ya existe traducción
        $sqlCheck = "SELECT id FROM product_translations WHERE product_id = ? AND language = ?";
        $stmt = $this->mysqli->prepare($sqlCheck);
        $stmt->bind_param("is", $product_id, $language);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing = $result->fetch_assoc();
    
        if ($existing) {
            // Actualizar traducción existente
            $sqlUpdate = "UPDATE product_translations 
                          SET name=?, slug=?, short_description=?, description=?, meta_title=?, meta_description=? 
                          WHERE id=?";
            $stmt = $this->mysqli->prepare($sqlUpdate);
            $stmt->bind_param("ssssssi", $name, $slug, $short_description, $description, $meta_title, $meta_description, $existing['id']);
            $stmt->execute();
        } else {
            // Insertar nueva traducción
            $sqlInsert = "INSERT INTO product_translations 
                          (product_id, language, name, slug, short_description, description, meta_title, meta_description) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->mysqli->prepare($sqlInsert);
            $stmt->bind_param("isssssss", $product_id, $language, $name, $slug, $short_description, $description, $meta_title, $meta_description);
            $stmt->execute();
        }
    }
}