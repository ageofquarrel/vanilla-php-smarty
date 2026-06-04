<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

final class CategoryPageService
{
    /**
     * Получение данных для страницы категории.
     */
    public static function getData(string $slug): ?array
    {
        $category = CategoryRepository::findBySlug($slug);

        if ($category === null) {
            return null;
        }

        $categoryId = (int) $category['id'];

        return [
            'category' => [
                'id' => $categoryId,
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
            ],
            'articles' => array_map(
                static function (array $row): array {
                    return [
                        'id' => (int) $row['id'],
                        'title' => $row['title'],
                        'slug' => $row['slug'],
                        'description' => $row['description'],
                        'image' => $row['image'],
                        'published_at' => $row['published_at'],
                        'views' => (int) $row['views'],
                    ];
                },
                ArticleRepository::findAllByCategoryId($categoryId),
            ),
        ];
    }
}
