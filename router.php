<?php

class router
{
    private $route = [];
    private $allowedMethods = ['get', 'post'];

    private function addRoute($method, $url, $callback): void
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

    public function forceHttps()
    {
        if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
        }
    }

    private function isIndex($url): bool
    {
        return empty($url) || in_array($url, $this->indexUrls);
    }

    private function getRequestURI(): string
    {
        return trim(str_replace(dirname($_SERVER['SCRIPT_NAME']) . '/',
            "", $_SERVER['REQUEST_URI']), '/');

    }

    private function routeExists($method, $route): bool
    {
        return isset($this->route[$method][$route]);
    }

    private function invokeRoute($method, $route, $args = array()): void
    {
        try {
            call_user_func_array($this->route[$method][$route]['callback'], $args);
        } catch (ArgumentCountError $e) {
            $this->invokeNotFound();
        }
    }

    private function invokeNotFound()
    {
        if ($this->routeExists('error', 404)) {
            $this->invokeRoute('error', 404);
        }
    }

    public function run($customRoute = '', $customMethod = 'get'): void
    {
        if ($customRoute != '') {
            $url = $customRoute;
            $method = $customMethod;
        } else {
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            $url = $this->getRequestURI();
        }

        $urlParts = explode('/', $url);

        for ($i = 0; $i < count($urlParts); $i++) {
            $findRoute = implode('/', array_slice($urlParts, 0, count($urlParts) - $i));
            if ($this->routeExists($method, $findRoute)) {
                $route = $findRoute;
                $args = array_filter(array_slice($urlParts, count($urlParts) - $i));
                $this->invokeRoute($method, $route, $args);
                return;
            }
        }

        $this->invokeNotFound();
    }

    public function __destruct()
    {
        $this->run();
    }
}