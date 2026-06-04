<?php

declare(strict_types=1);

namespace App\Database\Factories;

use App\Database\Connection;
use Faker\Generator;

final class ArticleFactory
{
    public function __construct(private readonly Generator $faker)
    {
    }

    /** @param list<int> $categoryIds */
    public function definition(array $categoryIds): array
    {
        $title = $this->faker->unique()->sentence(4);

        return [
            'title' => rtrim($title, '.'),
            'slug' => $this->slugify($title),
            'description' => $this->faker->paragraph(2),
            'body' => implode("\n\n", $this->faker->paragraphs(5)),
            'image' => '/images/placeholder.jpg',
            'views' => $this->faker->numberBetween(0, 5000),
            'published_at' => $this->faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
            'category_ids' => $this->pickCategories($categoryIds),
        ];
    }

    /** @param list<int> $categoryIds */
    public function create(array $categoryIds): int
    {
        $data = $this->definition($categoryIds);
        $categoryIdsToAttach = $data['category_ids'];
        unset($data['category_ids']);

        $stmt = Connection::pdo()->prepare(
            'INSERT INTO articles (title, slug, description, body, image, views, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['description'],
            $data['body'],
            $data['image'],
            $data['views'],
            $data['published_at'],
        ]);

        $articleId = (int) Connection::pdo()->lastInsertId();
        $linkStmt = Connection::pdo()->prepare(
            'INSERT INTO article_category (article_id, category_id) VALUES (?, ?)'
        );

        foreach ($categoryIdsToAttach as $categoryId) {
            $linkStmt->execute([$articleId, $categoryId]);
        }

        return $articleId;
    }

    /** @param list<int> $categoryIds */
    public function createMany(int $count, array $categoryIds): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->create($categoryIds);
        }
    }

    /** @param list<int> $categoryIds */
    private function pickCategories(array $categoryIds): array
    {
        if ($categoryIds === []) {
            return [];
        }

        $count = min(count($categoryIds), random_int(1, min(3, count($categoryIds))));
        $keys = (array) array_rand(array_flip($categoryIds), $count);

        return array_map('intval', $keys);
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?? $text;

        return trim($text, '-') ?: 'article';
    }
}
