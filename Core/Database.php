<?php
class Database
{
    public static $db;
    public static function init()
    {
        $host = DB_HOST;
        $user = DB_USER;
        $pass =  DB_PASSWORD;
        $db_name = DB_NAME;
        try {
            self::$db = new PDO("mysql:host={$host};dbname={$db_name}", $user, $pass);
            self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die ("PDO Error: " . $e->getMessage() . "<br/>");
        }
    }
}