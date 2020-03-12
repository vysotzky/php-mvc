<?php
define('ROOT_PATH', dirname(__DIR__) . '/');

require_once('core/helpers.php');

if(false == true && getRequestProtocol() == "http")
{

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
    if(class_exists($class)){
        return true;
    }

    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require_once strtolower($file . '.php');
});


$routes = new Core\Router;
registerRoutes($routes,'web');
//$Database = \DB::getDB();
//$Positions = new \Models\Positions;

?>