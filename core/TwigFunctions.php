<?php

namespace core;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigFunctions extends AbstractExtension
{
    public static function filter(): TwigFilter
    {
        return new TwigFilter('hash', function ($str) {
            return md5($str);
        });
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('css', [$this, 'css']),
            new TwigFunction('js', [$this, 'js']),
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('img', [$this, 'img']),
            new TwigFunction('backlink', [$this, 'previousReferrer']),
            new TwigFunction('json_decode', [$this, 'json_decode']),
            new TwigFunction('npm', [$this, 'node_module']),
            new TwigFunction('path', [$this, 'path'])
        ];
    }

    public function css($filename)
    {
        return $this->asset('css/' . $filename);
    }

    public function js($filename)
    {
        return $this->asset('js/' . $filename);
    }

    public function img($filename)
    {
        return $this->asset('img/' . $filename);
    }

    public function asset($filename)
    {
        return $this->path('/../resources/' . $filename);
    }

    public function previousReferrer()
    {
        return $_SERVER['HTTP_REFERER'] ?? 'javascript:history.go(-1)';
    }

    public function json_decode($json_string)
    {
        return json_decode($json_string);
    }

    public function node_module($file = '')
    {
        return $this->path('/../node_modules/' . $file);
    }

    public function path($url='')
    {
        $base_url = $_ENV['LOCALHOST_URL'] ?? '';
        return ($base_url . $url);
    }
}