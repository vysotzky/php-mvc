<?php
require_once('Core/Config.php');
require_once('Core/Database.php');
require_once('Core/Helpers.php');

if (FORCE_HTTPS === true && getRequestProtocol() == "http") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

spl_autoload_register(function ($class) {
    if (class_exists($class)) {
        return true;
    }
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
    return false;
});

$routes = new \Core\Router;
registerRoutes($routes, 'web');

extract($routes->resolve()); // returns $callback and $args

if (!empty($callback)) {
    if (!is_callable($callback)) {
        $controllerParts = explode('@', $callback);
        $controllerName = PATH_CONTROLLERS . $controllerParts[0];
        $controllerMethod = $controllerParts[1];
        if (!class_exists($controllerName, true)) {
            throw new Exception("Controller '{$controllerName}' doesn't exist");
        } else {
            $controller = new $controllerName;
        }
        if (!method_exists($controller, $controllerMethod)) {
            throw new Exception("Controller '{$controllerName}' method '{$controllerMethod}' doesn't exist");
        } else {
            $callback = [$controller, $controllerMethod];
            if (method_exists($controller, 'before')) {
                $controller->before();
            }
        }
    }

    $routes->invokeCallback($callback, $args);
}
?>