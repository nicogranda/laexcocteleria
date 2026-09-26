<?php
namespace App\Domains\Policies;

class Policy
{
    public static function find(string $slug): ?array
    {
        return PolicyData::get($slug);
    }
}
