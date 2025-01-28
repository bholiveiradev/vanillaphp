<?php

declare(strict_types=1);

namespace App\Support;

use App\Support\Contracts\CacheInterface;
use Predis\Client;

class CacheRedis implements CacheInterface
{
    public function __construct(public Client $client)
    {
    }

    public function set(string $key, mixed $value): void
    {
        $this->client->set('cache_'.$key, json_encode($value));
    }

    public function get(string $key): mixed
    {
        $cache = $this->client->get('cache_'.$key);

        return !empty($cache) ? json_decode($cache, false) : null;
    }

    public function forget(string $key): void
    {
        $this->client->del('cache_'.$key);
    }

    public function flushAll(): void
    {
        $this->client->flushall();
    }
}
