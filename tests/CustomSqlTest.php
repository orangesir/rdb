<?php
use \Rdb\CustomSql;

class CustomSqlTest extends \TestCase {

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage custom sql sqlstr is null or null string
     */
    public function testSqlStrNull() {
        $customSql = new CustomSql();
        $customSql->set("");
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage custom sql sqlstr is not a string
     */
    public function testSqlStrNotString() {
        $customSql = new CustomSql();
        $customSql->set(5);
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage custom sql values is not a array
     */
    public function testValuesNotArray() {
        $customSql = new CustomSql();
        $customSql->set("select * from table", 5);
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage custom sql have not set sqlstr
     */
    public function testCustomSqlNotSqlStr() {
        $customSql = new CustomSql();
        $customSql->build();
    }

    public function testCustomSql() {
        $customSql = new CustomSql();
        $customSql->set("SELECT * FROM table WHERE cola=? AND colb>?", array(5,6));
        $customSql->build();

        $this->assertEquals($customSql->sqlStr(),"SELECT * FROM table WHERE cola=? AND colb>?");
        $this->assertEquals($customSql->binds(), array(5,6));
        $this->assertEquals($customSql->__toString(), "SELECT * FROM table WHERE cola=5 AND colb>6");
    }

}