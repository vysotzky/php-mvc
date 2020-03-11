<?php
if($_SERVER['HTTP_X_FORWARDED_PROTO'] == "http")
{

    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start();
require_once('vendor/autoload.php');

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
    require_once strtolower('app' . DIRECTORY_SEPARATOR . $file . '.php');
});


$Database = \DB::getDB();
$Positions = new \Models\Positions;

if(!defined('API')) {
    $fb['app_id'] = '820204248405120';
    $fb['app_secret'] = '18ed3bc9e4b2d8f6b23e8e9339729f4c';
    $Facebook = new Facebook\Facebook([
        'app_id' => $fb['app_id'], // Replace {app-id} with your app id
        'app_secret' => $fb['app_secret'],
        'default_graph_version' => 'v5.0',
    ]);

    $oAuth2Client = $Facebook->getOAuth2Client();


    if (isset($_SESSION['fb_access_token'])) {

        $response = $Facebook->get('/me?locale=en_US&fields=id,name,email&access_token='.$_SESSION['fb_access_token']);
        $response = $response->getDecodedBody();

        if(!isset($response['name'])){
            unset($_SESSION['fb_access_token']);
        } else {
            $fbtoken = $_SESSION['fb_access_token'];
            $Facebook->setDefaultAccessToken($fbtoken);
            $user = $response;
        }
    }
}
?>