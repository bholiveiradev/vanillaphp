<?php

namespace App\Support\Contracts;

interface ViewInterface
{
    public static function processRender(string $view, array $data = []): void;
}
