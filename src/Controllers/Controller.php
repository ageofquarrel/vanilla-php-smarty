<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Http\Response;

abstract class Controller
{
    public function __construct(protected View $view)
    {
    }

    /** @param array<string, mixed> $data */
    protected function render(string $template, array $data = []): Response
    {
        return Response::html($this->view->render($template, $data));
    }

    protected function notFound(string $body = 'Not found'): Response
    {
        return Response::notFound($body);
    }
}
