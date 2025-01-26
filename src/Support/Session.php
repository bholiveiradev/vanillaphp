<?php

declare(strict_types=1);

namespace App\Support;

class Session
{
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);    
    }

    public static function get(string $key): ?array
    {
        if (self::has($key)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function remove(string $key): void
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    public static function flash(string $key, string $value): void
    {
        $_SESSION['__flash'][$key] = $value;
    }

    public static function dump(): void
    {
        dd($_SESSION);
    }
}
