<?php

namespace core\Router;

use core\Exceptions\ViewNotFoundException;
use core\View;

class Route
{
    public bool $isView = false;
    public bool $hasController;
    protected mixed $controller;

    public function __construct(
        protected string $path,
        protected string  $method,
        protected mixed  $handler,
        protected array  $options = [],
        protected array  $middlewares = [],
    )
    {
        $this->hasController = is_array($this->handler);
        $this->isView = is_string($this->handler);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function addMiddleware($middleware): static
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @throws ViewNotFoundException
     */
    public function dispatch(): mixed
    {
        if ($this->isView) {
           return View::make()->renderView($this->handler);
        }

        $this->controller = $this->handler;
        $this->controller[0] = new $this->handler[0];
        return call_user_func_array($this->controller, $this->options);
    }
}