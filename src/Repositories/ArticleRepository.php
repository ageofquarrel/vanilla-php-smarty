<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Connection;
use PDO;

final class ArticleRepository
{
    private const SORT_DATE = 'date';
    private const SORT_VIEWS = 'views';

    /**
     * Поиск статьи по slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        $stmt = Connection::pdo()->prepare(
            'SELECT id, title, slug, description, body, image, views, published_at
             FROM articles
             WHERE slug = ? AND published_at IS NOT NULL'
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public static function incrementViews(int $articleId): void
    {
        $stmt = Connection::pdo()->prepare(
            'UPDATE articles SET views = views + 1 WHERE id = ?'
        );
        $stmt->execute([$articleId]);
    }

    /**
     * Поиск последних статей.
     */
    public static function findRecent(int $limit = 3): array
    {
        $sql = <<<'SQL'
            SELECT
                id,
                title,
                slug,
                description,
                image,
                published_at,
                views
            FROM articles
            WHERE published_at IS NOT NULL
            ORDER BY published_at DESC, id DESC
            LIMIT :limit
            SQL;

        $stmt = Connection::pdo()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Подсчет количества статей для категории.
     */
    public static function countByCategoryId(int $categoryId): int
    {
        $sql = <<<'SQL'
            SELECT COUNT(DISTINCT a.id)
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
              AND a.published_at IS NOT NULL
            SQL;

        $stmt = Connection::pdo()->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }


    /**
     * Список статей для категории.
     */
    public static function findByCategoryId(
        int $categoryId,
        string $sort,
        string $order,
        int $limit,
        int $offset
    ): array {
        $orderBy = self::orderByForSort($sort, $order);

        $sql = <<<SQL
            SELECT
                a.id,
                a.title,
                a.slug,
                a.description,
                a.image,
                a.published_at,
                a.views
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
              AND a.published_at IS NOT NULL
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
            SQL;

        $stmt = Connection::pdo()->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Определение порядка сортировки для запроса.
     */
    private static function orderByForSort(string $sort, string $order): string
    {
        $direction = $order === 'asc' ? 'ASC' : 'DESC';

        if ($sort === self::SORT_VIEWS) {
            return "a.views {$direction}, a.id {$direction}";
        }

        return "a.published_at {$direction}, a.id {$direction}";
    }
}
