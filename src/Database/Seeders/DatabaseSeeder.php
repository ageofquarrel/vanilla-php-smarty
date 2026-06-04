<?php

declare(strict_types=1);

namespace App\Database\Seeders;

final class DatabaseSeeder
{
    public static function run(): void
    {
        CategorySeeder::run();
        ArticleSeeder::run();
    }
}
