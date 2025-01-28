<?php

declare(strict_types=1);

namespace App\Support;

use App\Core\Http\Response;
use App\Support\Contracts\ViewInterface;

class ViewHtml implements ViewInterface
{
    public static function processRender(string $view, array $data = []): void
    {
        $path = VIEW_PATH . '/' . $view . '.php';

        if (! file_exists($path)) {
            throw new \Exception("View not implemented: {$path}");
        }

        extract($data);

        ob_start();

        include $path;

        $result = ob_get_clean();

        Response::html($result);
    }
}
