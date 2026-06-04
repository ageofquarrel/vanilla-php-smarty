<?php

declare(strict_types=1);

namespace App\Models;

final class Category extends Model
{
    protected static string $table = 'categories';

    public function name(): string
    {
        return (string) $this->get('name');
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
}
