<?php

namespace core\Router;

use JetBrains\PhpStorm\ArrayShape;

class RoutesCollector
{
    protected static array $methods = ["GET", "POST", "PUT", "DELETE", "OPTIONS", "PATCH", "HEAD"];
    protected static array $handling = [];

    public static function GET(string $route, callable|object|string|array $fn, array $options = []): void
    {
        self::addHandling('GET', $route, $fn, $options);
    }

    public static function POST(string $route, callable|object|string|array $fn, array $options = []): void
    {
        self::addHandling('POST', $route, $fn, $options);
    }

    protected static function addHandling(string $method, string $route, callable|object|string|array $fn, array $options): void
    {
        $route = self::trimRoute($route);
        RoutesCollector::$handling[$route][$method] = new Route($route, $method, $fn, $options);

    }

    #[ArrayShape(['status' => 'int', 'route' => 'core\Router\Route', 'error_message' => 'string|null'])]
    public static function routeExists(string $route, string $method = 'GET'): array
    {
        $returns = ['status' => 0, 'route' => null ,'error_message' => null];
        $route = self::trimRoute($route);

        /** @var Route $findRoute */
        $findRoute = self::$handling[$route] ?? null;

        // Check if the route exists
        if (is_null($findRoute)) {
            $returns['error_message'] = "Route {$route} does not exist";
            return $returns;
        }

        // Check if the method exists for the given route
        if (!isset($findRoute[$method])) {
            $returns['error_message'] = "Method {$method} for route {$route} does not exist";
            return $returns;
        }

        // Both route and method exist, set the status and function
        $returns['route'] = $findRoute[$method];
        $returns['status'] = 1;

        return $returns;
    }

    protected static function trimRoute(string $route): string
    {
        return '/' . trim($route, '/\\');
    }

}