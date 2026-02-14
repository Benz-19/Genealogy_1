<?php
namespace CustomRouter;

class Router {
    private array $routes = [];

    public function get($path, $handler) { $this->addRoute('GET', $path, $handler); }
    public function post($path, $handler) { $this->addRoute('POST', $path, $handler); }

    private function addRoute(string $method, string $path, $handler) {
        // Standardize: remove trailing slashes and ensure leading slash
        $path = '/' . ltrim(trim($path), '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch($method, $path) {
        $path = '/' . ltrim(trim($path), '/');
        if ($path !== '/') $path = rtrim($path, '/');

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 Not Found: Router could not find [{$method}] path [{$path}]";
            return;
        }

        $handler = $this->routes[$method][$path];
        if (is_array($handler)) {
            $controller = new $handler[0]();
            return $controller->{$handler[1]}();
        }
        return call_user_func($handler);
    }
}