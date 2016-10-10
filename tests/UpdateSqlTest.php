<?php
use \Rdb\UpdateSql;
use \Rdb\Where;

class UpdateSqlTest extends \TestCase {

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage update sql no table name
     */
    public function testNoTableName() {
        $updateSql = new UpdateSql();
        $updateSql->build();
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage update sql no set
     */
    public function testNoSets() {
        $updateSql = new UpdateSql();
        $updateSql->setTableName("table");
        $updateSql->build();
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage update sql no where
     */
    public function testNoWhere() {
        $updateSql = new UpdateSql();
        $updateSql->setTableName("table");
        $updateSql->set("cola",5)->set("colb", "test");
        $updateSql->build();
    }

    public function testUpdateSql() {
        $where = new Where();
        $where->bind("cola>?", 5);

        $updateSql = new UpdateSql();
        $updateSql->setTableName("table")
                ->set("cola",5)
                ->set("colb","test")
                ->setWhere($where)
                ->build();
        $this->assertEquals($updateSql->sqlStr(), "UPDATE `table` SET `cola`=?,`colb`=? WHERE cola>?");
        $this->assertEquals($updateSql->binds(), array(5,"test",5));
        $this->assertEquals($updateSql->__toString(),"UPDATE `table` SET `cola`=5,`colb`='test' WHERE cola>5");
    }

}