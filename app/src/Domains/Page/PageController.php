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
        $data = $this->prepare($slug);
        if ($data === null) {
            require __DIR__ . '/../../../views/pages/404.php';
            return;
        }
        $this->render($data, $assetBasePath);
    }

    /** Antes del head: resuelve la página y establece 404 para slugs desconocidos. */
    public function prepare(string $slug): ?array
    {
        $data = $this->pages->findBySlug($slug, 'es');
        if ($data === null) http_response_code(404);
        return $data;
    }

    public function seo(array $data, string $baseUrl): array
    {
        $t = $data['translation'];
        $url = rtrim($baseUrl, '/') . '/' . $t['slug'];
        return [
            'title' => $t['title'], 'description' => $t['meta_description'],
            'canonical' => $url, 'url' => $url, 'robots' => $t['robots'],
            'og_title' => $t['og_title'], 'og_description' => $t['og_description'],
            'twitter_title' => $t['twitter_title'], 'twitter_description' => $t['twitter_description'],
            'og_image' => $t['hero_image'] !== null ? rtrim($baseUrl, '/') . '/' . $t['hero_image'] : null,
            'twitter_image' => $t['hero_image'] !== null ? rtrim($baseUrl, '/') . '/' . $t['hero_image'] : null,
        ];
    }

    public function render(array $data, string $assetBasePath = ''): void
    {
        $page = $data['page'];
        $translation = $data['translation'];
        $faqs = json_decode($translation['faqs'] ?? '[]', true, 512, JSON_THROW_ON_ERROR);
        $relatedPages = array_filter($this->pages->rows()['page_translations'],
            static fn (array $item): bool => $item['page_id'] >= 5 && $item['slug'] !== $translation['slug']);
        $components = [];
        foreach (array_filter(explode(',', $translation['components'] ?? '')) as $component) {
            if ($component === 'Gallery') {
                $gallery = new Gallery();
                $images = $gallery->images(
                    dirname(__DIR__, 4) . '/public_html/assets/img/portfolio',
                    rtrim($assetBasePath, '/') . '/assets/img/portfolio'
                );

                // Mostrar las fotos existentes mientras se prepara el directorio portfolio.
                if ($images === []) {
                    $images = $gallery->images(
                        dirname(__DIR__, 4) . '/public_html/assets/img/gallery',
                        rtrim($assetBasePath, '/') . '/assets/img/gallery'
                    );
                }

                $components[] = [
                    'type' => 'Gallery',
                    'images' => $images,
                ];
            }
        }

        require __DIR__ . '/Views/Show.php';
    }
}

