<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

final class CategoryPageService
{
    private const PER_PAGE = 10;
    private const SORT_DATE = 'date';
    private const SORT_VIEWS = 'views';
    private const ORDER_ASC = 'asc';
    private const ORDER_DESC = 'desc';

    /**
     * Получение данных для страницы категории.
     */
    public static function getData(string $slug, array $query): ?array
    {
        $category = CategoryRepository::findBySlug($slug);

        if ($category === null) {
            return null;
        }

        $categoryId = (int) $category['id'];
        $categorySlug = (string) $category['slug'];
        $sort = self::resolveSort($query['sort'] ?? null);
        $order = self::resolveOrder($query['order'] ?? null);
        $total = ArticleRepository::countByCategoryId($categoryId);
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min(self::resolvePage($query['page'] ?? null), $totalPages);
        $offset = ($page - 1) * self::PER_PAGE;

        return [
            'category' => [
                'id' => $categoryId,
                'name' => $category['name'],
                'slug' => $categorySlug,
                'description' => $category['description'],
            ],
            'articles' => array_map(
                static function (array $row): array {
                    return self::mapArticle($row);
                },
                ArticleRepository::findByCategoryId($categoryId, $sort, $order, self::PER_PAGE, $offset),
            ),
            'sort' => $sort,
            'order' => $order,
            'sort_date_url' => self::buildSortUrl($categorySlug, self::SORT_DATE, $sort, $order),
            'sort_views_url' => self::buildSortUrl($categorySlug, self::SORT_VIEWS, $sort, $order),
            'pagination' => [
                'page' => $page,
                'per_page' => self::PER_PAGE,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
                'prev_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < $totalPages ? $page + 1 : null,
            ],
        ];
    }

    private static function buildSortUrl(
        string $categorySlug,
        string $targetSort,
        string $currentSort,
        string $currentOrder
    ): string {
        if ($targetSort === $currentSort) {
            $order = $currentOrder === self::ORDER_DESC ? self::ORDER_ASC : self::ORDER_DESC;
        } else {
            $order = self::ORDER_DESC;
        }

        return '/category/' . rawurlencode($categorySlug)
            . '?sort=' . $targetSort
            . '&order=' . $order;
    }

    /**
     * Определение типа сортировки для запроса.
     */
    private static function resolveSort(mixed $value): string
    {
        if ($value === self::SORT_VIEWS) {
            return self::SORT_VIEWS;
        }

        return self::SORT_DATE;
    }

    /**
     * Определение порядка сортировки для запроса.
     */
    private static function resolveOrder(mixed $value): string
    {
        if ($value === self::ORDER_ASC) {
            return self::ORDER_ASC;
        }

        return self::ORDER_DESC;
    }

    /**
     * Определение номера страницы для запроса.
     */
    private static function resolvePage(mixed $value): int
    {
        $page = filter_var($value, FILTER_VALIDATE_INT);

        if ($page === false || $page < 1) {
            return 1;
        }

        return $page;
    }

    /**
     * Преобразование строки статьи в массив.
     */
    private static function mapArticle(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'title' => $row['title'],
            'slug' => $row['slug'],
            'description' => $row['description'],
            'image' => $row['image'],
            'published_at' => $row['published_at'],
            'views' => (int) $row['views'],
        ];
    }
}
