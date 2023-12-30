<?php

namespace App\Support\Contracts;

interface CacheInterface
{
    public function set(string $key, mixed $value): void;
    public function get(string $key): mixed;
    public function forget(string $key): void;
}
