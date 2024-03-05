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

    public static function redirect(string $location, int $status = 200): void
    {
        $absolutePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . "/$location";
        header("Location: $absolutePath", true, $status);
        exit;
    }
}