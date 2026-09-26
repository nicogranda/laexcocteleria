<?php
declare(strict_types=1);

require_once __DIR__ . '/../../Shared/config/connection.php';
require_once __DIR__ . '/../../Shared/Model.php';
require_once __DIR__ . '/Media.php';
require_once __DIR__ . '/MediaService.php';


$mediaModel = new \App\Domains\Media\Media($db); // namespace completo


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);

// 1. Subir archivos físicos (el Service debe devolver el array de nombres)
$uploadedFiles = MediaService::upload($productId, $_FILES['images']);

// 2. Guardar en DB usando el create() del padre
   

    foreach ($uploadedFiles as $i => $fileName) {
        $mediaModel->create([
            'type'        => 'image',
            'product_id'  => $productId,
            'position'    => $i,
            'image_path' => $fileName,
            'uploaded_at' => date('Y-m-d H:i:s')
        ]);
    }


echo json_encode(['status' => 'ok']);
