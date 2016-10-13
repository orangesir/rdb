<?php
namespace tests\mockClass;
class Connection {
    public $name;

    public function __construct($name) {
        $this->name = $name;
    }
}

namespace tests;

use \Rdb\ConnectionPool;
class ConnectionPoolTest extends \TestCase {

    public function testConnectionPool() {
        $connectionClass = ConnectionPool::getConnectionClass();
        $this->assertEquals($connectionClass, "\\Rdb\\Connection");
        ConnectionPool::setConnectionClass("\\tests\\mockClass\\Connection");

        $connection = ConnectionPool::get("dbnameget");
        $this->assertEquals($connection->name, "dbnameget");

        $connectionSet = new \tests\mockClass\Connection("dbnameset");
        ConnectionPool::set("dbnameset", $connectionSet);
        $connectionSetGet = ConnectionPool::get("dbnameset");
        $this->assertEquals($connectionSetGet->name, "dbnameset");
        $this->assertTrue($connectionSet===$connectionSetGet);

        ConnectionPool::delConnection("dbnameset");
        $connectionSetGet = ConnectionPool::get("dbnameset");
        $this->assertEquals($connectionSetGet->name, "dbnameset");

        $this->assertFalse($connectionSet===$connectionSetGet);

    }

}