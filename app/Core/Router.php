<?php

namespace App\Core;

class Router
{
    /** @var array<int, array{method:string, pattern:string, action:array, middlewares:array}> */
    private array $routes = [];

    public function get(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('GET', $uri, $action, $middlewares);
    }

    public function post(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('POST', $uri, $action, $middlewares);
    }

    public function put(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('PUT', $uri, $action, $middlewares);
    }

    public function delete(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('DELETE', $uri, $action, $middlewares);
    }

    private function add(string $method, string $uri, array $action, array $middlewares): void
    {
        $this->routes[] = [
            'method'      => $method,
            'pattern'     => $this->compile($uri),
            'action'      => $action,
            'middlewares' => $middlewares,
        ];
    }

    /** Converte /clientes/{id} em uma regex com grupos nomeados. */
    private function compile(string $uri): string
    {
        $uri = '/' . trim($uri, '/');
        $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $uri);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path   = $request->path();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $request->setParams($params);

            foreach ($route['middlewares'] as $middlewareClass) {
                (new $middlewareClass())->handle($request);
            }

            [$controllerClass, $action] = $route['action'];
            $controller = new $controllerClass();
            echo $controller->$action($request);
            return;
        }

        $this->notFound();
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo View::render('errors/404', [], 'layouts/app');
    }
}
