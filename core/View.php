<?php

namespace core;

use core\Exceptions\ViewNotFoundException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class View
{
    public FilesystemLoader $loader;
    public Environment $environment;

    public function __construct($view_path, $layout_path = '')
    {
        $this->loader = new FilesystemLoader([$view_path, $layout_path]);
        $this->environment = new Environment($this->loader, [
//            'cache' => App::$app->config['cache_path']
            'cache' => false,
            'debug' => true,
        ]);
        $this->environment->addExtension(new TwigFunctions());
        $this->environment->addExtension(new DebugExtension());
    }

    public static function make(): static
    {
        return new static(App::$app->config['view_path'], App::$app->config['layout_path']);
    }


    /**
     * @throws ViewNotFoundException
     */
    public function renderView($view, array $params = []): string
    {
        try {
            return $this->environment->render("$view.twig", $params);
        } catch (LoaderError|SyntaxError|RuntimeError $e) {
            throw new ViewNotFoundException($e->getMessage());
        }
    }
}