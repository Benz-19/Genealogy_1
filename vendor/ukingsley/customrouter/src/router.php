<?php

namespace CustomRouter;

class Router
{
    private array $routes = [];

    public function get(string $path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    // old
    // private function addRoute(string $method, string $path, $handler)
    // {
    //     $this->routes[$method][$path] = $handler;
    // }

    // new
    private function addRoute(string $method, string $path, $handler)
    {
        // Normalize route path at registration time
        $path = rtrim($path, '/') ?: '/';
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $path)
    {
        // Normalize incoming request path
        $path = rtrim($path, '/') ?: '/';

        // Method not registered
        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        // Route not registered
        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $handler = $this->routes[$method][$path];

        if (is_array($handler)) {
            [$class, $methodName] = $handler;

            if (!class_exists($class)) {
                throw new \RuntimeException("Controller {$class} not found");
            }

            if (!method_exists($class, $methodName)) {
                throw new \RuntimeException("Method {$methodName} not found in {$class}");
            }

            return (new $class)->$methodName();
        }

        if (is_callable($handler)) {
            return call_user_func($handler);
        }

        throw new \RuntimeException('Invalid route handler');
    }

}
