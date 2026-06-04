<?php

declare(strict_types=1);

namespace App\Models;

final class Article extends Model
{
    protected static string $table = 'articles';

    public function title(): string
    {
        return (string) $this->get('title');
    }

    public function slug(): string
    {
        return (string) $this->get('slug');
    }

    public function description(): ?string
    {
        $value = $this->get('description');

        return $value === null ? null : (string) $value;
    }

    public function body(): string
    {
        return (string) $this->get('body');
    }

    public function image(): string
    {
        return (string) $this->get('image');
    }

    public function views(): int
    {
        return (int) $this->get('views', 0);
    }

    public function publishedAt(): ?string
    {
        $value = $this->get('published_at');

        return $value === null ? null : (string) $value;
    }
}
