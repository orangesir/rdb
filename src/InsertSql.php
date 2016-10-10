<?php
namespace Rdb;

class InsertSql extends Sql {
    
    protected $sets;

    public function set($field, $value) {
        Sql::checkField($field);
        Sql::checkValue($value);
        $this->sets[$field] = $value;
        return $this;
    }

    public function getSets() {
        return $this->sets;
    }

    public function build() {
        $this->bindValues = array();
        if(!$this->getTableName()) {
            throw new Exception\SqlException("insert sql no table name");
        }

        if(!$this->getSets()) {
            throw new Exception\SqlException("insert set values not set");
        }

        $fileds = array();
        $values = array();
        foreach ($this->getSets() as $filed => $value) {
            $fileds[] = "`{$filed}`";
            $values[] = "?";
            $this->bindValues[] = $value;
        }
        $filedStr = implode(",", $fileds);
        $valueStr = implode(",", $values);

        $this->sqlString = "INSERT INTO `".$this->getTableName()."`(".$filedStr.") VALUES(".$valueStr.")";
        return $this;
    }
}