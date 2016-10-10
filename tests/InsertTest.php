<?php
use \Rdb\InsertSql;

class InsertTest extends \TestCase {

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage insert sql no table name
     */
    public function testNoTableName() {
        $insertSql = new InsertSql();
        $insertSql->build();
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage insert set values not set
     */
    public function testNoInsertValues() {
        $insertSql = new InsertSql();
        $insertSql->setTableName("table");
        $insertSql->build();
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql field is null or null string
     */
    public function testSetFiledNull() {
        $insertSql = new InsertSql();
        $insertSql->set(null,5);
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql field is null or null string
     */
    public function testSetFiledNullString() {
        $insertSql = new InsertSql();
        $insertSql->set("", 5);
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql field is not a string
     */
    public function testSetFiledNotString() {
        $insertSql = new InsertSql();
        $insertSql->set(5, 5);
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql value is null
     */
    public function testSetValueNull() {
        $insertSql = new InsertSql();
        $insertSql->set("cola", null);
    }

    public function testSetValueNullString() {
        $insertSql = new InsertSql();
        $insertSql->set("cola", "");
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql value is a object
     */
    public function testSetValueObject() {
        $insertSql = new InsertSql();
        $insertSql->set("cola", $insertSql);
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql value is a array
     */
    public function testSetValueArray() {
        $insertSql = new InsertSql();
        $insertSql->set("cola", array("zzz"));
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql value is a bool
     */
    public function testSetValueBool() {
        $insertSql = new InsertSql();
        $insertSql->set("cola", false);
    }

    public function testInsertSql() {
        $insertSql = new InsertSql();
        $insertSql->setTableName("table")->set("cola", 1)->set("colb", "valueb")->build();
        
        $this->assertEquals($insertSql->sqlStr(), "INSERT INTO `table`(`cola`,`colb`) VALUES(?,?)");
        $this->assertEquals($insertSql->binds(), array(1,"valueb"));
        $this->assertEquals($insertSql->__toString(), "INSERT INTO `table`(`cola`,`colb`) VALUES(1,'valueb')");
    }
}