<?php

namespace core;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFunctions extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('css', [$this, 'css']),
            new TwigFunction('js', [$this, 'js']),
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('img', [$this, 'img']),
            new TwigFunction('backlink', [$this, 'previousReferrer']),
            new TwigFunction('json_decode', [$this, 'json_decode']),
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
        return '/resources/' . $filename;
    }

    public function previousReferrer()
    {
        return $_SERVER['HTTP_REFERER'] ?? 'javascript:history.go(-1)';
    }

    public function json_decode($json_string)
    {
        return json_decode($json_string);
    }
}