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
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('img', [$this, 'img']),
        ];
    }

    public function css($filename)
    {
        return $this->asset('css/' . $filename);
    }

    public function img($filename)
    {
        return $this->asset('img/' . $filename);
    }

    public function asset($filename)
    {
        return '/resources/' . $filename;
    }
}