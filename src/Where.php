<?php
namespace Rdb;

class Where {

    private $whereStrs = array();
    private $binds = array();

    public function bind($whereStr, $value=null) {
        $this->whereStrs[] = $whereStr;
        if($value) {
            $this->binds[] = $value;
        }
    }

    public function binds() {
        return $this->binds;
    }

    public function whereStr() {
        return implode(" AND ", $this->whereStrs);
    }

}