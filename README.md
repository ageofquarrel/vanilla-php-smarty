# Vanilla PHP + Smarty Blog

## Требования

- PHP 8.1+
- Composer
- MySQL 8

## Установка

```bash
cp .env.example .env
composer install
chmod +x bin/migrate bin/seed
php bin/migrate
php bin/seed
```

Document root: `public/`

```bash
php -S localhost:8000 -t public public/router.php
```

## CLI

```bash
php bin/migrate           # применить миграции
php bin/migrate rollback  # откатить последнюю
php bin/migrate rollback 3
php bin/seed              # сидеры (5 категорий, 30 статей)
```

## Структура

- `src/Core` — App, Router, View
- `src/Http` — Request, Response
- `src/Models` — Category, Article
- `src/Database` — Connection, миграции, сидеры, factories
- `database/migrations` — схема БД
- `templates/` — Smarty-шаблоны (пока пусто)

## Docker

```bash
docker compose up -d
в контейнере php: composer install && php bin/migrate && php bin/seed
```
