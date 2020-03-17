<?php

namespace Core;
class Router
{
    protected $route = [];
    protected $autoResolve;
    protected $allowedMethods = ['get', 'post'];

    public function __construct($autoResolve = false)
    {
        $this->autoResolve = $autoResolve;
    }

    protected function addRoute($method, $url, $callback): void
    {
        $this->route[$method][trim($url, '/')] = ['callback' => $callback];
    }

    public function __call($func_name, $args): void
    {
        if (in_array($func_name, $this->allowedMethods) && count($args) >= 2) {
            $this->addRoute($func_name, $args[0], $args[1]);
        }
    }

    public function notFound($callback): void
    {
        $this->addRoute('error', 404, $callback);
    }

    public function index($callback): void
    {
        $this->addRoute('get', '', $callback);
    }

    public function getRequestURI(): string
    {
        return trim(str_replace(dirname($_SERVER['SCRIPT_NAME']) . '/',
            "", $_SERVER['REQUEST_URI']), '/');

    }

    public function routeExists($method, $route): bool
    {
        return isset($this->route[$method][$route]);
    }

    public function getRouteCallback($method, $route)
    {
        if ($this->routeExists($method, $route)) {
            return $this->route[$method][$route]['callback'];
        }
        return false;
    }

    public function getNotFoundCallback()
    {
        return $this->getRouteCallback('error', 404);
    }

    public function invokeCallback($callback, $args = array()): void
    {
        if (is_callable($callback)) {
            try {
                call_user_func_array($callback, $args);
            } catch (\ArgumentCountError $e) {
                $this->invokeCallback($this->getNotFoundCallback());
            }
        }
    }

    public function run(): void
    {
        $currentRoute = $this->resolve();
        $this->invokeCallback($currentRoute['callback'], $currentRoute['args']);
    }

    public function resolve(): array
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $url = $this->getRequestURI();

        $urlParts = explode('/', $url);

        for ($i = 0; $i < count($urlParts); $i++) {
            $findRoute = implode('/', array_slice($urlParts, 0, count($urlParts) - $i));
            if ($this->routeExists($method, $findRoute)) {
                $route = $findRoute;
                $args = array_filter(array_slice($urlParts, count($urlParts) - $i));
                return ['callback' => $this->getRouteCallback($method, $route), 'args' => $args];
            }
        }

        return ['callback' => $this->getNotFoundCallback(), 'args' => []];
    }

    public function __destruct()
    {
        if ($this->autoResolve) {
            $this->run();
        }
    }
}