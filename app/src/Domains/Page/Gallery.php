<?php
declare(strict_types=1);

namespace App\Domains\Page;

final class Gallery
{
    /** @return list<array{src:string, alt:string}> */
    public function images(string $directory, string $publicPath): array
    {
        if (!is_dir($directory)) {
            return [];
        }

        $images = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isFile() || !in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'], true)) {
                continue;
            }

            $relative = substr($file->getPathname(), strlen(rtrim($directory, DIRECTORY_SEPARATOR)) + 1);
            $segments = explode(DIRECTORY_SEPARATOR, $relative);
            $name = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $images[] = [
                'src' => rtrim($publicPath, '/') . '/' . implode('/', array_map('rawurlencode', $segments)),
                'alt' => trim(str_replace(['-', '_'], ' ', $name)),
            ];
        }

        usort($images, static fn (array $a, array $b): int => strnatcasecmp($a['src'], $b['src']));
        return $images;
    }
}
