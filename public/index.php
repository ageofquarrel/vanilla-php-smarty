<?php

declare(strict_types=1);

use App\Core\App;
use App\Http\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = App::create(dirname(__DIR__));
$request = Request::fromGlobals();

$app->router()->dispatch($request);
