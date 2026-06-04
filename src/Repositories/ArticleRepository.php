<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Connection;
use PDO;

final class ArticleRepository
{
    /**
     * @return list<array<string, mixed>>
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
}
