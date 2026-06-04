<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

final class HomePageService
{
    private const RECENT_ARTICLES_LIMIT = 3;

    /** 
     * Получение категорий с постами и последние посты.
     */
    public static function getData(): array
    {
        return [
            'categories' => array_map(
                static function (array $row): array {
                    return [
                        'id' => (int) $row['id'],
                        'name' => $row['name'],
                        'slug' => $row['slug'],
                        'description' => $row['description'],
                    ];
                },
                CategoryRepository::findAllWithArticles(),
            ),
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
                ArticleRepository::findRecent(self::RECENT_ARTICLES_LIMIT),
            ),
        ];
    }
}
