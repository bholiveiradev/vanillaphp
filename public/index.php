<?php

session_start();

use Predis\Autoloader as Redis;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;
use App\Core\Bootstrap;
use App\Core\Http\{Request, Response, Router};

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/../'));
$dotenv->load();

require_once __DIR__ . '/../config/config.php';

if (env('MAINTENANCE', false)) {
    require_once VIEW_PATH . '/maintenance/index.php';
    return;
}

Redis::register();

$whoops = new Whoops();
$whoops->pushHandler(new PrettyPageHandler());
$whoops->register();

require_once ROOT_PATH . '/bootstrap/app.php';
require_once ROOT_PATH . '/routes/routes.php';

$bootstrap = new Bootstrap($container);
$bootstrap->dispatch(Router::getRoutes(), new Request(), new Response());
