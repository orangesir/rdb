<?php
namespace Rdb;

class CustomSql extends Sql {

    private $customSqlStr;
    private $customValues;

    public function set($sqlStr, $values=null) {
        if(!$sqlStr) {
            throw new Exception\SqlException("custom sql sqlstr is null or null string");
        }
        if(!is_string($sqlStr)) {
            throw new Exception\SqlException("custom sql sqlstr is not a string");
        }
        $this->customSqlStr = $sqlStr;
        $this->customValues = array();
        if($values!==null) {
            if(!is_array($values)) {
                throw new Exception\SqlException("custom sql values is not a array");
            }
            foreach ($values as $value) {
                Sql::checkValue($value);
                $this->customValues[] = $value;
            }
        }
        return $this;
    }

    public function build() {
        if($this->customSqlStr===null) {
            throw new Exception\SqlException("custom sql have not set sqlstr");
        }
        $this->sqlString = $this->customSqlStr;
        $this->bindValues = $this->customValues;
        return $this;
    }
}