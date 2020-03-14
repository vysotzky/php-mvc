<?php
class Database
{
    private static $instance = null;
    private $db;
    private function __construct()
    {
        $host = DB_HOST;
        $user = DB_USER;
        $pass =  DB_PASSWORD;
        $db_name = DB_NAME;
        try {
            $this->db = new PDO("mysql:host={$host};dbname={$db_name}", $user, $pass);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die ("PDO Error: " . $e->getMessage() . "<br/>");
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