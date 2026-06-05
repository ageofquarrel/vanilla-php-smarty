<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Response;
use App\Services\ArticlePageService;

final class ArticleController extends Controller
{
    /**
     * Отображение страницы статьи.
     */
    public function show(string $slug): Response
    {
        $data = ArticlePageService::getData($slug);

        if ($data === null) {
            return $this->notFound();
        }

        return $this->render('article/show.tpl', $data);
    }
}
