<?php

function getRequestProtocol(): string
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
        return $_SERVER['HTTP_X_FORWARDED_PROTO'];
    else
        return !empty($_SERVER['HTTPS']) ? "https" : "http";
}

function registerRoutes(\Core\Router &$routes, string $name): void
{
    require_once(ROOT . "/" . PATH_ROUTES . "/{$name}.php");
}

function view($file, $vars = array()): string
{
    return \View::render($file, $vars);
}

function addViewVar($key, $value): void
{
    \View::addGlobalVar($key, $value);
}