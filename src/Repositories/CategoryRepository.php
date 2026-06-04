<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Connection;
use PDO;

final class CategoryRepository
{
    /**
     * Категории, в которых есть опубликованные статьи.
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

    /**
     * Список статей для категории.
     */
    public static function findBySlug(string $slug): ?array
    {
        $stmt = Connection::pdo()->prepare(
            'SELECT id, name, slug, description FROM categories WHERE slug = ?'
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }
}
