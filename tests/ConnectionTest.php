<?php
use \Rdb\Config;
use \Rdb\Connection;
use \Rdb\CustomSql;
use \Rdb\DeleteSql;
use \Rdb\InsertSql;
use \Rdb\SelectSql;
use \Rdb\UpdateSql;
use \Rdb\Where;

/**
 * 数据库查询测试
 * 测试依赖本机上安装MySQL,登录 账号:root 密码:""
 * 创建数据库:testDb
 */
class ConnectionTest extends \TestCase {

    private $connection;

    public function setUp() {
        Config::set("testdb", array(
            "dsn" => "mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf8",
            "username" => "root",
            "password" => ""
            ));
        $this->connection = new Connection("testdb");
    }

    public function testConnection() {
        $table =<<<sql
        DROP TABLE IF EXISTS `table`;

        CREATE TABLE `table` (
          `id` int(11) NOT NULL auto_increment,
          `name` char(20) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
sql;
        $customSql = new CustomSql();
        $customSql->set($table)->build();
        $tableResult = $this->connection->execute($customSql);
        $this->assertTrue($tableResult);

        $insertSqlUser1 = new InsertSql();
        $insertSqlUser1->setTableName("table")->set("name","user1")->build();
        $insertUser1Result = $this->connection->execute($insertSqlUser1);
        $this->assertTrue($insertUser1Result);

        $selectUser1 = new SelectSql();
        $where = new Where();
        $where->bind("name=?","user1");
        $selectUser1->setTableName("table")->setWhere($where)->build();
        $userRow = $this->connection->getRow($selectUser1);
        $this->assertEquals($userRow, array("id"=>"1","name"=>"user1"));

        $insertSqlUser2 = new InsertSql();
        $insertSqlUser2->setTableName("table")->set("name","user2")->build();
        $insertUser2Result = $this->connection->execute($insertSqlUser2);
        $this->assertTrue($insertUser2Result);
        $this->assertTrue($this->connection->lastInsertId()==="2");

        $selectUsersNoKey = new SelectSql();
        $selectUsersNoKey->setTableName("table")->build();
        $userRowsNoKey = $this->connection->getRows($selectUsersNoKey);
        $this->assertEquals($userRowsNoKey, array(array("id"=>"1","name"=>"user1"),array("id"=>"2","name"=>"user2")));

        $selectUsersKey = new SelectSql();
        $selectUsersKey->setTableName("table")->build();
        $userRowsKey = $this->connection->getRows($selectUsersKey, "id");
        $this->assertEquals($userRowsKey, array("1"=>array("id"=>"1","name"=>"user1"),"2"=>array("id"=>"2","name"=>"user2")));

        $where = new Where();
        $where->bind("name=?", "user1");
        $selectUser1Id = new SelectSql();
        $selectUser1Id->setTableName("table")->addField("id")->setWhere($where)->build();
        $user1Id = $this->connection->getOne($selectUser1Id);
        $this->assertEquals($user1Id, "1");

        $selectUserNameCol = new SelectSql();
        $selectUserNameCol->setTableName("table")->addField("name")->build();
        $userNames = $this->connection->getList($selectUserNameCol);
        $this->assertEquals($userNames, array("user1","user2"));

        $where = new Where();
        $where->bind("name=?", "user1");
        $updateUser1ToUser3 = new UpdateSql();
        $updateUser1ToUser3->setTableName("table")->set("name","user3")->setWhere($where)->build();
        $updateUser1ToUser3Result = $this->connection->execute($updateUser1ToUser3);
        $this->assertTrue($updateUser1ToUser3Result);
        $this->assertEquals($this->connection->rowCount($updateUser1ToUser3), 1);

        $where = new Where();
        $where->bind("id=?", 1);
        $selectId1Name = new SelectSql();
        $selectId1Name->setTableName("table")->setWhere($where)->addField("name")->build();
        $user1Name = $this->connection->getOne($selectId1Name);
        $this->assertEquals($user1Name, "user3");

        $deleteSql = new DeleteSql();
        $deleteSql->setTableName("table")->setWhere($where)->build();
        $deleteResult = $this->connection->execute($deleteSql);
        $this->assertTrue($deleteResult);
        $this->assertEquals($this->connection->rowCount($deleteSql), 1);
    }

    /**
     * @depends testConnection
     * @expectedException \Rdb\Exception\DbException
     * @expectedExceptionMessage table col idx notExist!
     */
    public function testSelectNoKey() {
        $selectUsersKey = new SelectSql();
        $selectUsersKey->setTableName("table")->build();
        $this->connection->getRows($selectUsersKey, "idx");
    }

    public function testConnectionString() {
        $this->assertEquals($this->connection->__toString(), "db testdb: mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf8<|>root<|>");
    }

}