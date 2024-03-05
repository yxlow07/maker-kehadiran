<?php

function dd(mixed $data, $html = true): void
{
    echo $html ? "<pre>" : '';
    var_dump($data);
    echo $html ? "</pre>" : '';
    exit;
}

function rd(string $loc)
{
    header('Location: /');
    die;
}