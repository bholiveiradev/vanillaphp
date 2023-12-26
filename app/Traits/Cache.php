<?php

declare(strict_types=1);

namespace App\Traits;

use App\Support\CacheRedis;
use Predis\Client;

trait Cache
{    
    private CacheRedis $cache;

    public function cacheInit()
    {
        $this->cache = new CacheRedis(
            new Client([
                'scheme' => 'tcp',
                'host'   => 'redis',
                'port'   => 6379
            ])
        );
    }

    public function set(string $key, mixed $value): void
    {
        $this->cache->set($key, $value);
    }

    public function get(string $key): mixed
    {
        $cache = $this->cache->get($key);

        return !empty($cache) ?: null;
    }

    public function forget(string $key): void
    {
        $this->cache->forget($key);
    }
}
