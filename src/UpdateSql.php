<?php
namespace Rdb;

class UpdateSql extends Sql {

    protected $sets = array();

    public function set($filed, $value) {
        if(!$filed) {
            throw new Exception\SqlException("update set filed is null or null string");
        }
        if(!is_string($filed)) {
            throw new Exception\SqlException("update set filed is not a string");
        }
        if($value===null) {
            throw new Exception\SqlException("update set value is null");
        }
        if(is_object($value)) {
            throw new Exception\SqlException("update set value is a object");
        }
        if(is_array($value)) {
            throw new Exception\SqlException("update set value is a array");
        }
        if(is_bool($value)) {
            throw new Exception\SqlException("update set value is a bool");
        }
        $this->setValues[$filed] = $value;
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
            throw new Exception\SqlException("update set values not set");
        }

        if(!$this->getWhere()) {
            throw new Exception\SqlException("update no set where");
        }

        $setStrs = array();
        foreach ($this->getSets() as $filed => $value) {
            $setStrs[] = "`".$filed."`=?";
            $this->bindValues[] = $value;
        }
        $setStr = " SET ".implode(",", $setStrs);
        
        $whereStr = $this->getWhere()->whereStr();
        $this->bindValues = array_merge($this->bindValues, $this->getWher()->binds());

        $this->sqlString = "UPDATE `".$this->getTableName()."`".$setStr.$whereStr;
        return $this;
    }
    
}