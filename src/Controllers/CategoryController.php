<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\CategoryPageService;

final class CategoryController extends Controller
{
    public function show(string $slug): Response
    {
        $data = CategoryPageService::getData($slug, Request::fromGlobals()->query());

        if ($data === null) {
            return $this->notFound();
        }

        return $this->render('category/show.tpl', $data);
    }
}
