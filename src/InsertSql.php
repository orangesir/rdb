<?php
namespace Rdb;

class InsertSql extends Sql {
    
    protected $sets;

    public function set($filed, $value) {
        if(!$filed) {
            throw new Exception\SqlException("insert set filed is null or null string");
        }
        if(!is_string($filed)) {
            throw new Exception\SqlException("insert set filed is not a string");
        }
        if($value===null) {
            throw new Exception\SqlException("insert set value is null");
        }
        if(is_object($value)) {
            throw new Exception\SqlException("insert set value is a object");
        }
        if(is_array($value)) {
            throw new Exception\SqlException("insert set value is a array");
        }
        if(is_bool($value)) {
            throw new Exception\SqlException("insert set value is a bool");
        }
        $this->sets[$filed] = $value;
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