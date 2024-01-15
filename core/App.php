<?php

namespace core;

use core\Router\Request;
use core\Router\Response;
use core\Router\Router;
use core\Router\RoutesCollector;

class App
{
    public Router $router;
    public Request $request;
    public Response $response;
    public View $view;
    public static App $app;

    public function __construct(
        public RoutesCollector $routesCollector,
        public $config = [],
    )
    {
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->view = new View($this->config['view_path'], $this->config['layout_path']);
    }

    public function run(string $environment = 'cli'): void
    {
        if ($environment == 'web') {
            $this->router = new Router($this->response, $this->request, $this->routesCollector);
            $this->router->run();
        }
        echo "<p class='text-gray-500'>App process time: " . round(microtime(true)-START, 4) . "ms</p>";
    }
}