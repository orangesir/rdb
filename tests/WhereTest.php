<?php

use \Rdb\Where;

class WhereTest extends \TestCase {

    public function testWhere() {
        $where = new Where();
        $where->bind("cola=?", 5)->bind("colb>?", "test");
        $binds = $where->binds();
        $this->assertEquals($binds, array(5, "test"));
        $whereStr = $where->whereStr();
        $this->assertEquals($whereStr, " WHERE cola=? AND colb>?");
    }

    public function testValueNone() {
        $where = new Where();
        $where->bind("cola=?", 0)->bind("colb>?", "");
        $binds = $where->binds();
        $this->assertEquals($binds, array(0, ""));
        $whereStr = $where->whereStr();
        $this->assertEquals($whereStr, " WHERE cola=? AND colb>?");
    }

    /**
    * @expectedException \Rdb\Exception\SqlException
    * @expectedExceptionMessage Where bind whereStr can not null
    */
    public function testWhereStrNull() {
        $where = new Where();
        $where->bind(null);
    }

    public function testIn() {
        $where = new Where();
        $where->in("cola", array(1,2,3,4));
        $this->assertEquals($where->binds(), array(1,2,3,4));
        $this->assertEquals($where->whereStr(), " WHERE `cola` IN(?,?,?,?)");
    }

}