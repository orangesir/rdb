<?php
use \Rdb\DeleteSql;
use \Rdb\Where;

class DeleteSqlTest extends \TestCase {

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage update sql no table name
     */
    public function testNoTableName() {
        $deleteSql = new DeleteSql();
        $deleteSql->build();
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage update sql no set where
     */
    public function testNoWhere() {
        $deleteSql = new DeleteSql();
        $deleteSql->setTableName("table");
        $deleteSql->build();
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql must build before use
     */
    public function testNotBuildGetSql() {
        $deleteSql = new DeleteSql();
        $deleteSql->sqlStr();
    }

    /**
     * @expectedException \Rdb\Exception\SqlException
     * @expectedExceptionMessage sql must build before use
     */
    public function testNotBuildGetBinds() {
        $deleteSql = new DeleteSql();
        $deleteSql->binds();
    }


    public function testDeleteSql() {
        $deleteSql = new DeleteSql();
        $deleteSql->setTableName("table");

        $where = new Where();
        $where->bind("cola>?", 5);
        $where->bind("colb=?", "te?st");

        $deleteSql->setWhere($where);
        $deleteSql->build();

        $this->assertEquals($deleteSql->binds(), array(5,"te?st"));
        $this->assertEquals($deleteSql->sqlStr(), "DELETE FROM `table` WHERE cola>? AND colb=?");
        $this->assertEquals($deleteSql->__toString(), "DELETE FROM `table` WHERE cola>5 AND colb='te?st'");
    }

}