<?php

declare(strict_types=1);

namespace App\Core;

use App\Database\Connection;
use Dotenv\Dotenv;

final class App
{
    private Router $router;
    private View $view;

    private function __construct(private readonly string $basePath)
    {
        $this->loadEnv();
        Connection::initialize(require $this->basePath . '/config/database.php');
        $this->view = new View($this->basePath);
        $this->router = new Router(require $this->basePath . '/config/routes.php');
    }

    public static function create(string $basePath): self
    {
        return new self($basePath);
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function view(): View
    {
        return $this->view;
    }

    private function loadEnv(): void
    {
        $envFile = $this->basePath . '/.env';

        if (is_file($envFile)) {
            Dotenv::createImmutable($this->basePath)->load();
        }
    }
}
