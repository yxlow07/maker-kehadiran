<?php

use JetBrains\PhpStorm\NoReturn;

function dd(mixed $data, $html = true): void
{
    echo $html ? "<pre>" : '';
    var_dump($data);
    echo $html ? "</pre>" : '';
    exit;
}

#[NoReturn]
function redirect(string $loc = '/'): void
{
    header('Location: ' . path($loc));
    die;
}

function path(string $location): string
{
    return ($_ENV['LOCALHOST_URL'] ?? '') . $location;
}