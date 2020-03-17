<?php

interface IView
{
    public static function init(): void;

    public static function render($file, $vars): string;

    public static function addGlobalVar($key, $value): void;
}

class View implements IView
{
    private static $engine = null;

    public static function init(): void
    {
        $loader = new \Twig\Loader\FilesystemLoader(ROOT . "/" . PATH_VIEWS . "/");
        $twig = new \Twig\Environment($loader);
        self::$engine = $twig;
    }

    public static function render($file, $vars = array()): string
    {
        $template = self::$engine->load($file);
        return $template->render($vars);
    }

    public static function addGlobalVar($key, $value): void
    {
        self::$engine->addGlobal($key, $value);
    }
}