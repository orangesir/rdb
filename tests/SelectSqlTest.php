<?php
use \Rdb\SelectSql;
use \Rdb\Where;

class SelectSqlTest extends \TestCase {

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage select sql no table name
     */
    public function testNoTableName() {
        $selectSql = new SelectSql();
        $selectSql->build();
    }

    public function testSelectSqlHasField() {
        $selectSql = new SelectSql();
        $selectSql->setTableName("table");
        $selectSql->addField("cola")
                ->addFields(array("colb","colc"))
                ->build();
        $this->assertEquals($selectSql->sqlStr(),"SELECT `cola`,`colb`,`colc` FROM `table`");
        $this->assertEquals($selectSql->__toString(),"SELECT `cola`,`colb`,`colc` FROM `table`");
    }

    public function testSelectSqlNoField() {
        $selectSql = new SelectSql();
        $selectSql->setTableName("table")
                ->build();
        $this->assertEquals($selectSql->sqlStr(),"SELECT * FROM `table`");
    }

    public function testSelectSqlHasWhere() {
        $where = new Where();
        $where->bind("cola=?", "test");

        $selectSql = new SelectSql();
        $selectSql->setTableName("table")
                ->setWhere($where)
                ->build();

        $this->assertEquals($selectSql->sqlStr(),"SELECT * FROM `table` WHERE cola=?");
        $this->assertEquals($selectSql->binds(),array("test"));
        $this->assertEquals($selectSql->__toString(),"SELECT * FROM `table` WHERE cola='test'");
    }

    public function testSelectSqlHasOrderAsc() {
        $selectSql = new SelectSql();
        $selectSql->setTableName("table")
                ->ascField("cola")
                ->descField("colb")
                ->build();
        $this->assertEquals($selectSql->sqlStr(),"SELECT * FROM `table` ORDER BY `cola` ASC,`colb` DESC");
        $this->assertEquals($selectSql->__toString(),"SELECT * FROM `table` ORDER BY `cola` ASC,`colb` DESC");
    }

    public function testSelectSqlHasLimit() {
        $selectSql = new SelectSql();
        $selectSql->setTableName("table")
                ->setLimit(10)
                ->setOffset(5)
                ->build();

        $this->assertEquals($selectSql->sqlStr(),"SELECT * FROM `table` LIMIT ?,?");
        $this->assertEquals($selectSql->__toString(),"SELECT * FROM `table` LIMIT 5,10");

        $selectSql1 = new SelectSql();
        $selectSql1->setTableName("table")
                ->setLimit(10)
                ->build();

        $this->assertEquals($selectSql1->sqlStr(),"SELECT * FROM `table` LIMIT ?");
        $this->assertEquals($selectSql1->__toString(),"SELECT * FROM `table` LIMIT 10");
    }

    public function testSelectSqlAll() {
        $where = new Where();
        $where->bind("cola>?", 5);

        $selectSql = new SelectSql();
        $selectSql->setTableName("table")
                ->addFields(array("colb","colc"))
                ->addField("cola")
                ->setLimit(10)
                ->setOffset(5)
                ->setWhere($where)
                ->descField("colb")
                ->ascField("cola")
                ->build();

        $this->assertEquals($selectSql->sqlStr(),"SELECT `colb`,`colc`,`cola` FROM `table` WHERE cola>? ORDER BY `colb` DESC,`cola` ASC LIMIT ?,?");
        $this->assertEquals($selectSql->binds(), array(5,5,10));
        $this->assertEquals($selectSql->__toString(),"SELECT `colb`,`colc`,`cola` FROM `table` WHERE cola>5 ORDER BY `colb` DESC,`cola` ASC LIMIT 5,10");
    }
}