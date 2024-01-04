<?php

declare(strict_types=1);

use App\Support\Session;
use App\Support\ViewHtml;

if (! function_exists('redirect')) {
    function redirect(string $path = '/'): void
    {
        header('Location: ' . APP_URL . $path);
    }
}

if (! function_exists('back')) {
    function back(): void
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}

if (! function_exists('has_flash')) 
{
    function has_flash(string $key): bool
    {
        return Session::has('__flash')
            ? array_key_exists($key, Session::get('__flash'))
            : false;
    }
}

if (! function_exists('flash')) {
    function flash(string $key): ?string
    {
        $flash = Session::get('__flash')[$key] ?? null;

        Session::remove('__flash');

        return $flash;
    }
}

if (! function_exists('view')) {
    function view(string $view, array $data = []): void
    {
        ViewHtml::processRender($view, $data);
    }
}

if (!function_exists('app_path')) {
    function app_path($path = '')
    {
        return __DIR__ . '/' . ltrim($path, '/');
    }
}