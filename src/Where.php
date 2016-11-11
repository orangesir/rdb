<?php
namespace Rdb;

class Where {

    private $whereStrs = array();
    private $binds = array();

    public function in($field, $values) {
        if(!$values) {
            return;
        }
        $wens = array();
        foreach ($values as $value) {
            $wens[] = "?";
        }
        $whereStr = "`".$field."` IN(".implode(",", $wens).")";
        $this->bind($whereStr, $values);
    }

    public function bind($whereStr, $value=null) {
        if(!$whereStr) {
            throw new Exception\SqlException("Where bind whereStr can not null");
        }
        $this->whereStrs[] = $whereStr;
        if($value!==null) {
            if(is_array($value)) {
                foreach ($value as $item) {
                    Sql::checkValue($item);
                    $this->binds[] = $item;
                }
            } else {
                Sql::checkValue($value);
                $this->binds[] = $value;
            }
        }
        return $this;
    }

    public function binds() {
        return $this->binds;
    }

    public function whereStr() {
        return $this->whereStrs ? " WHERE ".implode(" AND ", $this->whereStrs):"";
    }

}