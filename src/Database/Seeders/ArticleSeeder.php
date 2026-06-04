<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Database\Connection;
use App\Database\Factories\ArticleFactory;
use Faker\Factory;
use PDO;

final class ArticleSeeder
{
    public static function run(): void
    {
        $stmt = Connection::pdo()->query('SELECT id FROM categories ORDER BY id');
        $categoryIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

        if ($categoryIds === []) {
            return;
        }

        $faker = Factory::create();
        (new ArticleFactory($faker))->createMany(30, $categoryIds);
    }
}
