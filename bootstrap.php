<?php
define('ROOT_PATH', dirname(__DIR__) . '/');

require_once('Core/Helpers.php');

if (false == true && getRequestProtocol() == "http") {

    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


class DB
{
    private static $instance = null;

    private $db;

    private function __construct()
    {
        $user = 'jmvildo3_bus';
        $pass = 'Naynoropar!23';
        $db_name = $user;
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=' . $db_name, $user, $pass);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            print "Error: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public static function getDB()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->db;
    }
}


spl_autoload_register(function ($class) {
    if (class_exists($class)) {
        return true;
    }

    $file = __DIR__.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

    if(file_exists($file)) {
        require_once $file;
    } else {
        return false;
    }
});


$routes = new \Core\Router;
registerRoutes($routes, 'web');

extract($routes->resolve());

if(!empty($callback)) {
    if (!is_callable($callback)) {
        $controllerParts = explode('@', $callback);
        $controllerName = "App\\Controllers\\{$controllerParts[0]}";
        $controllerMethod = $controllerParts[1];
        if (!class_exists($controllerName, true)) {
            throw new Exception("Controller '{$controllerName} doesn't exist");
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



//$Database = \DB::getDB();
//$Positions = new \Models\Positions;

?>