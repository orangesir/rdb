<?php
namespace Rdb;

class DeleteSql extends Sql {
    
    public function build() {
        $this->bindValues = array();
        if(!$this->getTableName()) {
            throw new Exception\SqlException("no table name");
        }

        if(!$this->getWhere()) {
            throw new Exception\SqlException("update no set where");
        }

        $this->sqlString = "DELETE FROM `".$this->getTableName()."`".$this->getWhere()->whereStr();
        return $this->sqlString;
    }
}