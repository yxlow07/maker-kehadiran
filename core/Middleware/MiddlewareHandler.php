<?php

namespace core\Middleware;

use core\Exceptions\MiddlewareException;

class MiddlewareHandler
{
    /**
     * @throws MiddlewareException
     */
    public function __construct(
        protected array $middlewares = []
    )
    {}

    public function handleMiddlewares(): void
    {
        foreach ($this->middlewares as &$middleware) {
            if (is_string($middleware) && str_contains($middleware, '@')) {
                $middleware = explode('@', $middleware);
            }
        }

        foreach ($this->middlewares as &$middleware) {
            if (is_array($middleware)) {
                $middleware[0] = new $middleware[0];
            }
        }
    }

    /**
     * @throws MiddlewareException
     */
    public function runMiddlewares(): void
    {
        foreach ($this->middlewares as $middleware) {
            call_user_func($middleware);
        }
    }
}