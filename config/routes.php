<?php

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;

return [
    'GET' => [
        '/' => [HomeController::class, 'index'],
        '/category/{slug}' => [CategoryController::class, 'show'],
        '/article/{slug}' => [ArticleController::class, 'show'],
    ],
];
