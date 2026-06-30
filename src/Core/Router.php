<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middleware = [];
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, callable|array $handler, array $middleware): void
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function addMiddleware(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function dispatch(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();

        // Run global middleware
        foreach ($this->middleware as $mw) {
            $result = $mw($this->request);
            if ($result === false) {
                return;
            }
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Run route middleware
                foreach ($route['middleware'] as $mw) {
                    $result = $mw($this->request);
                    if ($result === false) {
                        return;
                    }
                }

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $handler = $route['handler'];

                if (is_array($handler)) {
                    [$class, $method] = $handler;
                    $controller = new $class();
                    call_user_func_array([$controller, $method], [$this->request, $params]);
                } else {
                    call_user_func_array($handler, [$this->request, $params]);
                }

                return;
            }
        }

        // 404
        http_response_code(404);
        View::render('errors/404', [
            'title' => 'Page Not Found',
            'description' => 'The page you are looking for does not exist.',
        ]);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
