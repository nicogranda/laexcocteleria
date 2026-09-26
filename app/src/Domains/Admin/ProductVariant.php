<?php
namespace App\Domains\Admin;

require_once __DIR__ . '/../../Shared/Model.php';

use App\Shared\Models\Model;
use mysqli;

class ProductVariant extends Model
{
    protected string $table = 'product_variants'; // Nombre de la tabla en la base de datos

    public function __construct(mysqli $mysqli)
    {
        parent::__construct($mysqli);
        $this->setTable($this->table);
    }
}