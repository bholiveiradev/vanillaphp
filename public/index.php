<?php

session_start();

use Predis\Autoloader as RedisLoader;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;
use App\Core\Bootstrap;
use App\Core\Http\Router;

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../config/app.php');

RedisLoader::register();

$whoops = new Whoops;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

require_once(ROOT_PATH . '/routes/routes.php');

Bootstrap::run(Router::routes());