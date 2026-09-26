<?php
declare(strict_types=1);

final class MediaService
{
    public static function upload(int $productId, array $images): array
    {
        // $basePath = __DIR__ . "/../../../../../public_html/products/{$productId}/images";
         $basePath = $_SERVER['DOCUMENT_ROOT'] . "/products/{$productId}/images";

// file_put_contents(__DIR__ . '/upload_debug.log', "BasePath: $basePath\n", FILE_APPEND);

        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true);
        }

        $uploadedFiles = [];

        foreach ($images['tmp_name'] as $i => $tmp) {
            if (!is_uploaded_file($tmp)) continue;

            $name = 'image-' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . '.jpg';
            move_uploaded_file($tmp, "$basePath/$name");

            $uploadedFiles[] = $name;
        }

        return $uploadedFiles;
    }
}

