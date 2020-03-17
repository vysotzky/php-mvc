<?php
define('ROOT', __DIR__);
require_once 'vendor/autoload.php';
require_once('Core/Config.php');
require_once('Core/Database.php');
require_once('Core/Helpers.php');
require_once('Core/View.php');

// HTTPS Enforcer
if (FORCE_HTTPS === true && getRequestProtocol() == "http") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

// Class autoloader
spl_autoload_register(function ($class) {
    if (class_exists($class)) {
        return true;
    }
    $file = ROOT . DIRECTORY_SEPARATOR. str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
    return false;
});

// Initialize template engine
View::init();

// Initialize database
Database::init();

// Initialize router
$routes = new \Core\Router;
registerRoutes($routes, 'web');
extract($routes->resolve()); // returns $callback and $args

if (!empty($callback)) {
    if (!is_callable($callback)) {
        $controllersPath = trim(str_replace('/', '\\', PATH_CONTROLLERS), '\\');
        $controllerParts = explode('@', $callback);
        $controllerName = $controllersPath . "\\" . $controllerParts[0];
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