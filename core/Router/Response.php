<?php

namespace core\Router;

use core\App;
use core\Exceptions\ViewNotFoundException;
use JetBrains\PhpStorm\ArrayShape;

class Response
{
    public function setStatusCode(int $code = 200): void
    {
        http_response_code($code);
    }

    public function redirect(string $location, int $status = 200)
    {
        // TODO: Allow redirect from routers or something of similar nature
        header("Location: ./$location", response_code: $status);
    }
}