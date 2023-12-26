<?php

declare(strict_types=1);

use App\Support\Session;
use App\Support\ViewHtml;

if (! function_exists('dd')) {
    function dd(mixed $var, ...$moreVars): void
    {
        dump($var, ...$moreVars);
        exit();
    }
}

if (! function_exists('redirect')) {
    function redirect(string $path = '/'): void
    {
        header('Location: ' . APP_URL . $path);
        exit();
    }
}

if (! function_exists('back')) {
    function back(): void
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
}

if (! function_exists('has_flash')) {
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
        (new ViewHtml)->processRender($view, $data);
    }
}