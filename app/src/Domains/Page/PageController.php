<?php
declare(strict_types=1);

namespace App\Domains\Page;

require_once __DIR__ . '/Page.php';
require_once __DIR__ . '/Gallery.php';

final class PageController
{
    public function __construct(private Page $pages = new Page())
    {
    }

    public function show(string $slug, string $assetBasePath = ''): void
    {
        $page = $this->pages->findBySlug($slug);

        if ($page === null) {
            http_response_code(404);
            require __DIR__ . '/../../../views/pages/404.php';
            return;
        }

        $components = [];
        foreach ($page['components'] as $component) {
            if ($component === 'Gallery') {
                $components[] = [
                    'type' => 'Gallery',
                    'images' => (new Gallery())->images(
                        dirname(__DIR__, 4) . '/public_html/assets/img/portfolio',
                        rtrim($assetBasePath, '/') . '/assets/img/portfolio'
                    ),
                ];
            }
        }

        require __DIR__ . '/Views/Show.php';
    }
}
