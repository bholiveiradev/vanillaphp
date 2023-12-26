<?php

namespace App\Support\Contracts;

interface ViewInterface
{
    public function processRender(string $view, array $data = []): void;
}
