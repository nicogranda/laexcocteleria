<?php
declare(strict_types=1);

namespace App\Domains\Page;

/** Catálogo temporal de páginas; más adelante puede leer una tabla. */
final class Page
{
    public function findBySlug(string $slug): ?array
    {
        $pages = [
            'galeria' => [
                'slug' => 'galeria',
                'title' => 'Galería de eventos',
                'description' => 'Descubre momentos de los eventos en los que hemos servido cócteles.',
                'components' => ['Gallery'],
            ],
        ];

        return $pages[$slug] ?? null;
    }
}
