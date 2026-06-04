<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Database\Factories\CategoryFactory;
use Faker\Factory;

final class CategorySeeder
{
    public static function run(): void
    {
        $faker = Factory::create();
        (new CategoryFactory($faker))->createMany(5);
    }
}
