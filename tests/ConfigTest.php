<?php

class ConfigTest extends \TestCase {

    /**
     * @expectedException \Rdb\Exception\ConfigException
     * @expectedExceptionMessage dbconfig:infoq unset!
     */
    public function testNoConfig() {
        \Rdb\Config::get("infoq");
    }

    public function testNoDsn() {

    }

    public function testNoUsername() {

    }

    public function testNoPassWord() {
        
    }
}