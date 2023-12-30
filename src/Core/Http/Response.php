<?php

declare(strict_types=1);

namespace App\Core\Http;

class Response
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_MOVED_PERMANENTLY = 301;
    const HTTP_FOUND = 302;
    const HTTP_NOT_MODIFIED = 304;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501;
    const HTTP_BAD_GATEWAY = 502;
    const HTTP_SERVICE_UNAVAILABLE = 503;
    
    public static function setStatusCode(int $statusCode): self
    {
        http_response_code($statusCode);
        return new static;
    }

    public static function setHeader(string $header, string $value): self
    {
        header("{$header}: {$value}");
        return new static;
    }

    public static function setHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            self::setHeader($header, $value);
        }

        return new static;
    }

    public static function html(string $html, int $statusCode = Response::HTTP_OK, array $headers = []): void
    {
        self::setStatusCode($statusCode);
        self::setHeaders($headers);

        header('Content-Type: text/html');

        echo $html;
    }

    public static function json(array $data, int $statusCode = Response::HTTP_OK, array $headers = []): void
    {
        self::setStatusCode($statusCode);
        self::setHeaders($headers);
        
        header('Content-Type: application/json');

        echo json_encode($data);
    }
}
