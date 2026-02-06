<?php

namespace CustomRouter;

class Route
{
    protected static Router $router;

    public static function get(string $uri, $action)
    {
        self::ensureRouter();
        self::$router->get($uri, $action);
    }

    public static function post(string $uri, $action)
    {
        self::ensureRouter();
        self::$router->post($uri, $action);
    }

    public static function dispatch()
    {
        self::ensureRouter();
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        self::$router->dispatch($method, $uri);
    }

    protected static function ensureRouter()
    {
        if (!isset(self::$router)) {
            self::$router = new Router();
        }
    }
}
