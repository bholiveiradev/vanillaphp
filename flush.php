<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Support\CacheRedis;
use Predis\Client;

$redis = new CacheRedis(new Client([
    'scheme' => 'tcp',
    'host'   => 'redis',
    'port'   => '6379'
]));

$redis->flushall();