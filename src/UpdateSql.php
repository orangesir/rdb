<?php
namespace Rdb;

class UpdateSql extends Sql {

    protected $sets = array();

    public function set($field, $value) {
        Sql::checkField($field);
        Sql::checkValue($value);
        $this->sets[$field] = $value;
        return $this;
    }

    public function getSets () {
        return $this->sets;
    }

    public function build() {
        $this->bindValues = array();
        if(!$this->getTableName()) {
            throw new Exception\SqlException("update sql no table name");
        }

        if(!$this->getSets()) {
            throw new Exception\SqlException("update sql no set");
        }

        if(!$this->getWhere()) {
            throw new Exception\SqlException("update sql no where");
        }

        $setStrs = array();
        foreach ($this->getSets() as $field => $value) {
            $setStrs[] = "`".$field."`=?";
            $this->bindValues[] = $value;
        }
        $setStr = " SET ".implode(",", $setStrs);
        
        $whereStr = $this->getWhere()->whereStr();
        $this->bindValues = array_merge($this->bindValues, $this->getWhere()->binds());

        $this->sqlString = "UPDATE `".$this->getTableName()."`".$setStr.$whereStr;
        return $this;
    }
    
}