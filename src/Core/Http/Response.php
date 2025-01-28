<?php

declare(strict_types=1);

namespace App\Core\Http;

class Response
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;

    public static function setStatusCode(int $statusCode): void
    {
        http_response_code($statusCode);
    }

    public static function setHeader(string $header, string $value): void
    {
        header("{$header}: {$value}");
    }

    public static function setHeaders(array $headers): void
    {
        foreach ($headers as $header => $value) {
            self::setHeader($header, $value);
        }
    }

    public static function html(string $html, int $statusCode = Response::HTTP_OK, array $headers = ['Content-Type' => 'text/html']): void
    {
        self::setStatusCode($statusCode);
        self::setHeaders($headers);

        echo $html;
    }

    public static function json(array $data, int $statusCode = Response::HTTP_OK, array $headers = ['Content-Type' => 'application/json']): void
    {
        self::setStatusCode($statusCode);
        self::setHeaders($headers);

        echo json_encode($data);
    }
}
