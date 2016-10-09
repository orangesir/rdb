<?php

class ConfigTest extends \TestCase {

    /**
     * @expectedException \Rdb\Exception\ConfigException
     * @expectedExceptionMessage dbconfig:dbname unset!
     */
    public function testNoConfig() {
        \Rdb\Config::get("dbname");
    }

    /**
     * @expectedException \Rdb\Exception\ConfigException
     * @expectedExceptionMessage dbname not a string!
     */
    public function testDbNameNotString() {
        \Rdb\Config::clean();
        $config = array(
            "dsn" => "mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf-8",
            "username" => "username",
            "password" => "password"
            );
        \Rdb\Config::set(123, $config);
    }

    /**
     * @expectedException \Rdb\Exception\ConfigException
     * @expectedExceptionMessage dbconfig:dbname have not dsn(PDO dsn)!
     */
    public function testNoDsn() {
        \Rdb\Config::clean();
        $config = array(
            "username" => "username",
            "password" => "password"
            );
        \Rdb\Config::set("dbname", $config);
    }

    /**
     * @expectedException \Rdb\Exception\ConfigException
     * @expectedExceptionMessage dbconfig:dbname have not username!
     */
    public function testNoUsername() {
        \Rdb\Config::clean();
        $config = array(
            "dsn" => "mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf-8",
            "password" => "password"
            );
        \Rdb\Config::set("dbname", $config);
    }

    /**
     * @expectedException \Rdb\Exception\ConfigException
     * @expectedExceptionMessage dbconfig:dbname have not password!
     */
    public function testNoPassWord() {
        \Rdb\Config::clean();
        $config = array(
            "dsn" => "mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf-8",
            "username" => "username"
            );
        \Rdb\Config::set("dbname", $config);
    }

    public function testOkSetGet() {
        $dbname = "dbname";
        \Rdb\Config::clean();
        $config = array(
            "dsn" => "mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf-8",
            "username" => "username",
            "password" => "password"
            );
        \Rdb\Config::set($dbname, $config);
        $dbconfig = \Rdb\Config::get($dbname);
        $this->assertEquals($dbconfig, $config);
    }
}