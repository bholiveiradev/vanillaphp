<?php

declare(strict_types=1);

namespace App\Http;

class Request
{
    private string $method;
    private string $uri;
    private array  $query;
    private array  $headers;
    private array  $params;
    private array  $input;
    private array  $files;
    private string $body;

    public function __construct() {
        $this->method   = $_SERVER['REQUEST_METHOD'];
        $this->uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->query    = !empty($_GET) ? filter_input_array(INPUT_GET, FILTER_DEFAULT) : [];
        $this->input    = !empty($_POST) ? filter_input_array(INPUT_POST, FILTER_DEFAULT): [];
        $this->files    = !empty($_FILES) ? $_FILES : [];
        $this->params   = (array) $this->query + (array) $this->input + $_COOKIE;
        $this->headers  = getallheaders();
        $this->body     = file_get_contents('php://input');
    }

    public function __get(string $name): mixed
    {
        return $this->params[$name];
    }

    public function getMethod(): string
    {
        return !empty($this->getParam('_method')) 
            ? $this->getParam('_method') 
            : $this->method;
    }

    public function getUri(): string
    {
        return rtrim($this->uri, '/') . '/';
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    public function setParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }

    public function getParam(string $name, ?string $default = null): ?string
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    public function getInput(string $name, ?string $default = null): ?string
    {
        return isset($this->input[$name]) ? $this->input[$name] : $default;
    }

    public function getFiles(string $name): ?array
    {
        return isset($this->files[$name]) ? $this->files[$name] : null;
    }

    public function getAllFiles(): array
    {
        return $this->files;
    }

    public function getAll(): array
    {
        return $this->params;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
