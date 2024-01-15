<?php

namespace core\Router;

use core\App;
use core\Exceptions\RouteNotFoundException;
use core\Exceptions\ViewNotFoundException;
use core\View;

class Router
{
    public function __construct(
        public Response $response,
        public Request $request,
        public RoutesCollector $routesCollector,
    )
    {}

    public function run(): void
    {
        $method = Request::method();
        $url = $this->request->path();

        try {
            echo $this->resolve($url, $method);
        } catch (RouteNotFoundException|ViewNotFoundException $e) {
            echo View::make()->renderView('error', ['error' => $e]);
        }
    }

    /**
     * @throws ViewNotFoundException
     * @throws RouteNotFoundException
     */
    private function resolve(string $url, string $method)
    {
        // TODO: Instead of whole route becomes a key in an array, explode it and add it dynamically (Allows for grouping)
        // array_values(array_filter(explode('/', $url), 'strlen'));
        $routeExists = $this->routesCollector::routeExists($url, $method);

        if ($routeExists['status']) {
            return $routeExists['route']->dispatch();
        } else {
            throw new RouteNotFoundException($routeExists['error_message']);
        }
    }
}