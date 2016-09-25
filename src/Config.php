<?php
namespace Rdb;

class Config {

    private static $configs;

    /**
     * @configParam dsn:
     *     example: "mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf-8"
     *     mysqldoc: http://php.net/manual/zh/ref.pdo-mysql.connection.php
     * @configParam username
     * @configParam password
     * 
     */
    public static function set($dbname, array $config) {
        if(!is_string($dbname)) {
            throw new Exception\ConfigException("dbname not a String!");
        }
        if(!isset($config["dsn"])) {
            throw new Exception\ConfigException("dbconfig:{$dbname} have not dsn(PDO dsn)!");
        }
        if(!isset($config["username"])) {
            throw new Exception\ConfigException("dbconfig:{$dbname} have not username!");
        }
        if(!isset($config["password"])) {
            throw new Exception\ConfigException("dbconfig:{$dbname} have not password!");
        }
        self::$configs[$dbname] = $config;
    }

    public static function get($dbname) {
        if(!isset(self::$configs[$dbname])) {
            throw new Exception\ConfigException("dbconfig:{$dbname} unset!");
        }
        return self::$configs[$dbname];
    }

}