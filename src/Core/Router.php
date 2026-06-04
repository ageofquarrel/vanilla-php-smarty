<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;

final class Router
{
    /** @param array<string, array<string, array{0: class-string, 1: string}>> $routes */
    public function __construct(private readonly array $routes)
    {
    }

    /**
     * @return array{0: class-string, 1: string, 2: array<int, string>}
     */
    public function dispatch(Request $request): array
    {
        $methodRoutes = $this->routes[$request->method()] ?? [];

        foreach ($methodRoutes as $pattern => $handler) {
            $params = $this->match($pattern, $request->path());

            if ($params !== null) {
                return [$handler[0], $handler[1], $params];
            }
        }

        header('HTTP/1.0 404 Not Found');
        echo 'Not found';
        exit;
    }

    /** @return array<int, string>|null */
    private function match(string $pattern, string $path): ?array
    {
        $regex = preg_replace('#\{([a-zA-Z_]+)\}#', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        array_shift($matches);

        return $matches;
    }
}
