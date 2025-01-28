<?php

declare(strict_types=1);

namespace App\Traits;

use App\Support\CacheRedis;
use Predis\Client;

trait Cache
{
    private static CacheRedis $cache;

    public static function cacheInit()
    {
        self::$cache = new CacheRedis(
            new Client([
                'scheme' => 'tcp',
                'host'   => 'redis',
                'port'   => '6379'
            ])
        );
    }

    public static function set(string $key, mixed $value): void
    {
        self::$cache->set($key, $value);
    }

    public static function get(string $key): mixed
    {
        $cache = self::$cache->get($key);

        return !empty($cache) ?: null;
    }

    public static function forget(string $key): void
    {
        self::$cache->forget($key);
    }
}
