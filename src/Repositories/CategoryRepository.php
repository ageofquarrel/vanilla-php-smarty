<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Connection;
use PDO;

final class CategoryRepository
{
    /**
     * Категории, в которых есть опубликованные статьи.
     *
     * @return list<array<string, mixed>>
     */
    public static function findAllWithArticles(): array
    {
        $sql = <<<'SQL'
            SELECT DISTINCT
                c.id,
                c.name,
                c.slug,
                c.description
            FROM categories c
            INNER JOIN article_category ac ON ac.category_id = c.id
            INNER JOIN articles a ON a.id = ac.article_id
            WHERE a.published_at IS NOT NULL
            ORDER BY c.name ASC
            SQL;

        return Connection::pdo()->query($sql)->fetchAll();
    }
}
