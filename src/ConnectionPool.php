<?php
namespace Rdb;

class ConnectionPool {

    private static $pool = array();

    public static function get($dbname) {
        if(!isset(self::$pool[$dbname])) {
            self::$pool[$dbname] = new Connection($dbname);
        }
        return self::$pool[$dbname];
    }
    
}