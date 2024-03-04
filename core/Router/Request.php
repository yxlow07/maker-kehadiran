<?php

namespace core\Router;

class Request
{
    private array $routeParams = [];

    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? "GET");
    }

    public function isMethod(string $method): bool
    {
        return Request::method() == strtoupper($method);
    }

    public function data(bool $readPhpInput = false): array|string
    {
        $data = [];

        if (Request::method() == 'GET') {
            $data = $_GET;
        }

        if (Request::method() == 'POST') {
            $data = $_POST;
        }
        
        if ($readPhpInput) {
            $data = file_get_contents('php://input');
        }

        return $data;
    }

    public function path(): string
    {
        $path = preg_replace('#/+#', '/', rawurldecode($_SERVER['REQUEST_URI']));
        $position = strpos($path, '?');

        if (is_int($position)) {
            return substr($path, 0, $position);
        }

        return $path;
    }

    public function queryString($raw = false)
    {
        $rawQueryString = $_SERVER['QUERY_STRING'];
        $sanitised = htmlspecialchars(urlencode($rawQueryString), ENT_QUOTES, 'UTF-8');

        return $raw ? $rawQueryString : $sanitised;
    }

    public function getRequestHeaders(): false|array
    {
        $headers = array();

        // If getallheaders() is available, use that
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            // getallheaders() can return false if something went wrong
            if ($headers !== false) {
                return $headers;
            }
        }

        return $headers;
    }

    public function setRouteParams(array $routeParams): Request
    {
        $this->routeParams = $routeParams;
        return $this;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }
}