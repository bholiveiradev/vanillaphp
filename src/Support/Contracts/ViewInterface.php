<?php

declare(strict_types=1);

namespace App\Support\Contracts;

interface ViewInterface
{
    public static function processRender(string $view, array $data = []): void;
}
