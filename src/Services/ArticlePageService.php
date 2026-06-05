<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

final class ArticlePageService
{
    private const SIMILAR_ARTICLES_LIMIT = 3;

    /**
     * Получение данных для страницы статьи.
     */
    public static function getData(string $slug): ?array
    {
        $row = ArticleRepository::findBySlug($slug);

        if ($row === null) {
            return null;
        }

        $articleId = (int) $row['id'];
        ArticleRepository::incrementViews($articleId);

        return [
            'article' => [
                'id' => $articleId,
                'title' => $row['title'],
                'slug' => $row['slug'],
                'description' => $row['description'],
                'body' => $row['body'],
                'image' => $row['image'],
                'published_at' => $row['published_at'],
                'views' => (int) $row['views'] + 1,
            ],
            'categories' => array_map(
                static function (array $category): array {
                    return [
                        'id' => (int) $category['id'],
                        'name' => $category['name'],
                        'slug' => $category['slug'],
                    ];
                },
                CategoryRepository::findByArticleId($articleId),
            ),
            'similar' => array_map(
                static function (array $item): array {
                    return [
                        'id' => (int) $item['id'],
                        'title' => $item['title'],
                        'slug' => $item['slug'],
                        'description' => $item['description'],
                        'image' => $item['image'],
                        'published_at' => $item['published_at'],
                        'views' => (int) $item['views'],
                    ];
                },
                ArticleRepository::findSimilarByArticleId($articleId, self::SIMILAR_ARTICLES_LIMIT),
            ),
        ];
    }
}
