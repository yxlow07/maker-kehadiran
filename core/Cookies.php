<?php

namespace core;

class Cookies
{
    public static function unsetCookie(string $key): void
    {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            setcookie($key, '', -1, '/');
        }
    }

    public static function unsetCookies(array $keys): void
    {
        foreach ($keys as $key) {
            self::unsetCookie($key);
        }
    }
}