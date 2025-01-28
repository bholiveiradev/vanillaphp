<?php

declare(strict_types=1);

namespace App\Traits;

use App\Support\ViewHtml;

trait View
{
    public function render(string $view, array $data = []): void
    {
        ViewHtml::processRender($view, $data);
    }
}
