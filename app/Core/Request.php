<?php

namespace App\Core;

class Request
{
    private array $get;
    private array $post;
    private array $params = [];

    public function __construct()
    {
        $this->get  = $_GET;
        $this->post = $_POST;
    }

    /** Verbo HTTP, considerando override via campo _method. */
    public function method(): string
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'POST' && isset($this->post['_method'])) {
            return strtoupper($this->post['_method']);
        }
        return $method;
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /** Caminho da URI sem querystring. */
    public function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        return $path === '' ? '/' : $path;
    }

    /** Valor de POST (com fallback em GET). */
    public function input(string $key, mixed $default = null): mixed
    {
        $value = $this->post[$key] ?? $this->get[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        $value = $this->get[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    /** Retorna apenas as chaves informadas a partir do corpo. */
    public function only(array $keys): array
    {
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = $this->input($key);
        }
        return $out;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /** Parâmetro de rota (ex.: {id}). */
    public function param(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }
}
