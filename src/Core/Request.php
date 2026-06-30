<?php
namespace App\Core;

class Request
{
    private array $query;
    private array $body;
    private array $files;
    private array $server;

    public function __construct()
    {
        $this->query = $_GET;
        $this->body = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public function getUri(): string
    {
        $uri = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($uri, '/') ?: '/';
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function post(string $key, $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function input(string $key, $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function isAjax(): bool
    {
        return strtolower($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    public function wantsJson(): bool
    {
        $accept = $this->server['HTTP_ACCEPT'] ?? '';
        return str_contains($accept, 'application/json');
    }

    public function getMethodOverride(): string
    {
        $method = $this->post('_method', '');
        return in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']) ? strtoupper($method) : $this->getMethod();
    }

    public function getHost(): string
    {
        return $this->server['HTTP_HOST'] ?? 'localhost';
    }

    public function isSecure(): bool
    {
        return (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off')
            || ($this->server['SERVER_PORT'] ?? 80) == 443;
    }

    public function getScheme(): string
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    public function getFullUrl(): string
    {
        return $this->getScheme() . '://' . $this->getHost() . $this->server['REQUEST_URI'];
    }
}
