<?php

namespace core;

use app\Models\User;
use core\Database\Database;
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
    public Database $database;
    public Session $session;
    public ?User $user;
    public bool $loggedIn = false;
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
        $this->database = new Database($this->config['db']);
        $this->user = new User();
        $this->session = new Session();
    }

    public function run(string $environment = 'cli'): void
    {
        if ($environment == 'web') {
            $this->runWeb();
        }
        echo "<p class='text-gray-500'>App process time: " . round(microtime(true)-START, 4) . "ms</p>";
    }

    public function runWeb(): void
    {
        if (!empty($this->session->getSession()['user']) && $this->session->getSession()['user'] instanceof User) {
            $this->user = $this->session->getSession()['user'];
        }
        $this->loggedIn = $this->user->isLogin();

        $this->router = new Router($this->response, $this->request, $this->routesCollector);
        $this->router->dispatch();
    }
}