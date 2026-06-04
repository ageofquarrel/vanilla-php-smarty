<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Response;
use App\Services\HomePageService;

final class HomeController extends Controller
{
    public function index(): Response
    {
        return $this->render('home/index.tpl', HomePageService::getData());
    }
}
