<?php

namespace App\Support\Contracts;

use App\Core\Http\Response;

interface ViewInterface
{
    public static function processRender(string $view, array $data = []): void;
}
