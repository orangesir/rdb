<?php
namespace Rdb;

class DeleteSql extends Sql {
    
    public function build() {
        $this->bindValues = array();
        if(!$this->getTableName()) {
            throw new Exception\SqlException("update sql no table name");
        }

        if(!$this->getWhere()) {
            throw new Exception\SqlException("update sql no set where");
        }

        $this->sqlString = "DELETE FROM `".$this->getTableName()."`".$this->getWhere()->whereStr();
        $this->bindValues = $this->getWhere()->binds();
    }
}