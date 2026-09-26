<?php
namespace App\Domains\Admin;

require_once __DIR__ . '/../../Shared/Model.php';

use App\Shared\Models\Model;
use mysqli;

class ProductMedia extends Model
{
    protected string $table = 'product_media'; // Nombre de la tabla en la base de datos

    public function __construct(mysqli $mysqli)
    {
        parent::__construct($mysqli);
        $this->setTable($this->table);
    }

    /**
     * Crea un nuevo registro en product_media
     */
    public function create(array $data): int
    {
        return parent::create($data);
    }

    /**
     * Actualiza un registro por ID
     */
    public function updateById(int $id, array $data): bool
    {
        return parent::updateById($id, $data);
    }

    /**
     * Obtiene todos los registros de un producto
     */
    public function getByProductId(int $productId): array
    {
        return $this->getByColumn('product_id', $productId);
    }

    /**
     * Borra registros de un producto
     */
    public function deleteByProductId(int $productId): bool
    {
        return $this->deleteByColumn('product_id', $productId);
    }
}