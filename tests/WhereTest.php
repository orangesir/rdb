<?php

use \Rdb\Where;

class WhereTest extends \TestCase {

    public function testWhere() {
        $where = new Where();
        $where->bind("cola=?", 5)->bind("colb>?", "test");
        $binds = $where->binds();
        $this->assertEquals($binds, array(5, "test"));
        $whereStr = $where->whereStr();
        $this->assertEquals($whereStr, " where cola=? AND colb>?");
    }

}