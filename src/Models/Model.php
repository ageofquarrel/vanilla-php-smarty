<?php

declare(strict_types=1);

namespace App\Models;

abstract class Model
{
    protected static string $table;

    public function __construct(protected array $attributes = [])
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function id(): ?int
    {
        $id = $this->get('id');

        return $id === null ? null : (int) $id;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public static function table(): string
    {
        return static::$table;
    }

    public static function fromRow(array $row): static
    {
        return new static($row);
    }
}
