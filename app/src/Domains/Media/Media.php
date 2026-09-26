<?php
declare(strict_types=1);

namespace App\Domains\Media;

use App\Models\Model;
use mysqli;

class Media extends Model
{

    protected string $table = 'product_media';

    public function __construct(mysqli $db)
    {
        parent::__construct($db);
    }

    public function reorderPositions(int $productId): void
    {
        $media = $this->getByColumn('product_id', $productId);
        foreach ($media as $i => $item) {
            $this->updateById((int)$item['id'], ['position' => $i]);
        }
    }
}
