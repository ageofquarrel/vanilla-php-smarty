<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Connection;
use PDO;

final class ArticleRepository
{
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
     * Все статьи для категории.
     */
    public static function findAllByCategoryId(int $categoryId): array
    {
        $sql = <<<'SQL'
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
            ORDER BY a.published_at DESC, a.id DESC
            SQL;

        $stmt = Connection::pdo()->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
