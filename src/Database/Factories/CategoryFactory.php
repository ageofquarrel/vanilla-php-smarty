<?php

declare(strict_types=1);

namespace App\Database\Factories;

use App\Database\Connection;
use Faker\Generator;

final class CategoryFactory
{
    public function __construct(private readonly Generator $faker)
    {
    }

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => $this->slugify($name),
            'description' => $this->faker->paragraph(),
        ];
    }

    public function create(): int
    {
        $data = $this->definition();
        $stmt = Connection::pdo()->prepare(
            'INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'],
        ]);

        return (int) Connection::pdo()->lastInsertId();
    }

    public function createMany(int $count): array
    {
        $ids = [];

        for ($i = 0; $i < $count; $i++) {
            $ids[] = $this->create();
        }

        return $ids;
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?? $text;

        return trim($text, '-') ?: 'category';
    }
}
