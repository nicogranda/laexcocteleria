<?php
namespace App\Domains\Admin;

require_once __DIR__ . '/../../Shared/Model.php';

use App\Shared\Models\Model;
use mysqli;

class Category extends Model
{
    protected string $table = 'categories';

    public function __construct(mysqli $mysqli)
    {
        parent::__construct($mysqli);
        $this->setTable($this->table);
    }

    public function create(array $data): int
    {
        $stmt = $this->mysqli->prepare("
            INSERT INTO {$this->table} (name, language, description) 
            VALUES (?, ?, ?)
        ");

        $description = $data['description'] ?? '';

        $stmt->bind_param(
            "sss",
            $data['name'],
            $data['language'],
            $description
        );

        $stmt->execute();

        return $this->mysqli->insert_id;
    }

    public function getAll(): array
    {
        $sql = "
            SELECT id, name,  image_url
            FROM {$this->table}
            WHERE active = 1
            ORDER BY name ASC
        ";

        $result = $this->mysqli->query($sql);

        $categories = [];

        while ($row = $result->fetch_assoc()) {
            $categories[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'images' => $row['image_url'] ? [$row['image_url']] : []
            ];
        }

        return $categories;
    }

    public function getCategoryById(int $id): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function searchCategories(string $q): array
    {
        return $this->search('name', $q);
    }
}