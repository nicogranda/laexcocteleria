<?php
namespace App\Shared\Models;

use mysqli;
use Exception;

class Model
{
    protected mysqli $mysqli;
    protected string $table;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * Obtiene registros según idioma
     * @param string $lang EN / ES
     * @param array $conditions ['column' => value]
     * @return array
     */
    public function getByLanguage(string $lang, array $conditions = []): array
    {
        if (empty($this->table)) throw new Exception("Table not set");
    
        $sql = "SELECT * FROM {$this->table} WHERE language = ?";
        $params = [$lang];
        $types = "s";
    
        foreach ($conditions as $col => $val) {
            $sql .= " AND {$col} = ?";
            $types .= is_int($val) ? "i" : "s";
            $params[] = $val;
        }
    
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) throw new Exception("Prepare error: " . $this->mysqli->error);
    
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* =====================
       CREATE
    ===================== */
    public function create(array $data): int
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
    
        $stmt = $this->mysqli->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        if (!$stmt) throw new Exception("Prepare error: " . $this->mysqli->error);
    
        $types = '';
        $params = [];
        foreach ($data as $value) {
            $types .= match(true) {
                is_int($value) => 'i',
                is_float($value) => 'd',
                default => 's',
            };
            $params[] = $value;
        }
    
        // ðŸ‘ˆ Pasar referencias a bind_param
        $refs = [];
        foreach ($params as $key => $value) {
            $refs[$key] = &$params[$key];
        }
    
        $stmt->bind_param($types, ...$refs);
    
        // if (!$stmt->execute()) throw new Exception("Execute error: " . $stmt->error);
        
        if (!$stmt->execute()) {
            $err = "Execute error: " . $stmt->error;
            file_put_contents(__DIR__ . '/model_debug.log', $err . "\n", FILE_APPEND);
            throw new Exception($err);
        }

    
        return $this->mysqli->insert_id;
    }

    /* =====================
       READ
    ===================== */
    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $result = $this->mysqli->query($sql);
        if (!$result) throw new Exception("Query error: " . $this->mysqli->error);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function getByColumn(string $column, $value): array
    {
        $type = is_int($value) ? 'i' : 's';
        $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} WHERE $column = ?");
        $stmt->bind_param($type, $value);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* =====================
       UPDATE
    ===================== */
    public function updateById(int $id, array $data): bool
    {
        $columns = array_keys($data);
        $setClause = implode(' = ?, ', $columns) . ' = ?';

        $stmt = $this->mysqli->prepare("UPDATE {$this->table} SET $setClause WHERE id = ?");
        if (!$stmt) throw new Exception("Prepare error: " . $this->mysqli->error);

        $types = '';
        $params = [];
        foreach ($data as $value) {
            $types .= match(true) {
                is_int($value) => 'i',
                is_float($value) => 'd',
                default => 's',
            };
            $params[] = $value;
        }

        $types .= 'i'; // id
        $params[] = $id;

        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    /* =====================
       DELETE
    ===================== */
    public function deleteById(int $id): bool
    {
        $stmt = $this->mysqli->prepare("DELETE FROM {$this->table} WHERE id = ?");
        if (!$stmt) throw new Exception("Prepare error: " . $this->mysqli->error);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function deleteByColumn(string $column, $value): bool
    {
        $type = is_int($value) ? 'i' : 's';
        $stmt = $this->mysqli->prepare("DELETE FROM {$this->table} WHERE $column = ?");
        if (!$stmt) throw new Exception("Prepare error: " . $this->mysqli->error);
        $stmt->bind_param($type, $value);
        return $stmt->execute();
    }

    /* =====================
       AUXILIAR / BUSQUEDA
    ===================== */
    public function search(string $column, string $query): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} WHERE $column LIKE ? LIMIT 25");
        $like = "%$query%";
        $stmt->bind_param('s', $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotal(): int
    {
        $result = $this->mysqli->query("SELECT COUNT(*) AS total FROM {$this->table}");
        if (!$result) throw new Exception("Query error: " . $this->mysqli->error);
        return $result->fetch_assoc()['total'] ?? 0;
    }
}
