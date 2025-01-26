<?php

declare(strict_types=1);

namespace App\Core\Http;

class Request
{
    private static string $method;
    private static string $uri;
    private static array  $query;
    private static array  $headers;
    private static array  $params;
    private static array  $input;
    private static array  $files;
    private static string $body;

    public function __construct()
    {
        self::$method   = $_SERVER['REQUEST_METHOD'];
        self::$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        self::$query    = !empty($_GET) ? filter_input_array(INPUT_GET, FILTER_DEFAULT) : [];
        self::$input    = !empty($_POST) ? filter_input_array(INPUT_POST, FILTER_DEFAULT) : [];
        self::$files    = !empty($_FILES) ? $_FILES : [];
        self::$params   = (array) self::$query + (array) self::$input + $_COOKIE;
        self::$headers  = getallheaders();
        self::$body     = file_get_contents('php://input');
    }

    public function __get(string $name): mixed
    {
        return self::$params[$name];
    }

    public function getMethod(): string
    {
        return !empty(self::getParam('_method')) ? self::getParam('_method') : self::$method;
    }

    public static function getUri(): string
    {
        return rtrim(self::$uri, '/') . '/';
    }

    public static function getQuery(): array
    {
        return self::$query;
    }

    public static function getHeader(string $name): ?string
    {
        return self::$headers[$name] ?? null;
    }

    public static function setParams(array $params): void
    {
        self::$params = array_merge(self::$params, $params);
    }

    public static function getParam(string $name, ?string $default = null): ?string
    {
        return isset(self::$params[$name]) ? self::$params[$name] : $default;
    }

    public static function getInputs(): array
    {
        return self::$input;
    }

    public static function getInput(string $name, ?string $default = null): ?string
    {
        return isset(self::$input[$name]) ? self::$input[$name] : $default;
    }

    public static function getFile(string $name): ?array
    {
        return isset(self::$files[$name]) ? self::$files[$name] : null;
    }

    public static function getFiles(): array
    {
        return self::$files;
    }

    public static function getParams(): array
    {
        return self::$params;
    }

    public static function getBody(): string
    {
        return self::$body;
    }
}
