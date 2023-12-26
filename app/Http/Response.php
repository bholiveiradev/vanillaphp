<?php

declare(strict_types=1);

namespace App\Http;

class Response
{
    private int   $statusCode;
    private array $headers; 

    public function __construct(array $headers = [], int $statusCode = 200)
    {
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function send(string $body): void
    {
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $body;
    }

    public function json(array $data = []): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');

        echo json_encode($data);
    }
}
