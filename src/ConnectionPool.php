<?php
namespace Rdb;

class ConnectionPool {

    private static $pool = array();
    private static $connectionClass;

    public static function set($dbname, $connection) {
        self::$pool[$dbname] = $connection;
    }

    public static function get($dbname) {
        if(!isset(self::$pool[$dbname])) {
            $connectionClass = self::getConnectionClass();
            self::$pool[$dbname] = new $connectionClass($dbname);
        }
        return self::$pool[$dbname];
    }

    public static function setConnectionClass($connectionClass) {
        self::$connectionClass = $connectionClass;
    }

    public static function getConnectionClass() {
        if(!self::$connectionClass) {
            self::$connectionClass = "\\Rdb\\Connection";
        }
        return self::$connectionClass;
    }

    public static function delConnection($dbname) {
        if(isset(self::$pool[$dbname])) {
            unset(self::$pool[$dbname]);
        }
    }
    
}