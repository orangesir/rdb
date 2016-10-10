<?php
namespace Rdb;

class SelectSql extends Sql {

    protected $fields = array();
    protected $offset;
    protected $limit;
    protected $orders = array();

    public function appendFields(array $fields) {
        foreach ($fields as $field) {
            Sql::checkField($field);
            $this->fields[] = $field;
        }
    }

    public function appendField($field) {
        $this->fields[] = $field;
    }

    public function getFields() {
        return $this->fields;
    }

    public function ascField($field) {
        $this->orders[] = "`".$field."` asc";
    }

    public function descField($field) {
        $this->orders[] = "`".$field."` desc";
    }

    public function getOrders() {
        return $this->orders;
    }

    public function setOffset($offset) {
        $this->offset = $offset;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function build() {
        
        $this->bindValues = array();

        if(!$this->getTableName()) {
            throw new Exception\SqlException("select sql no table name");
        }
        if($this->getFields()) {
            $fieldstr = "`".implode("`,`", $this->getFields())."`";
        } else {
            $fieldstr = "*";
        }

        $whereStr = "";
        if($where = $this->getWhere()) {
            $whereStr = $where->whereStr();
            $this->bindValues = array_merge($this->bindValues, $where->binds());
        }

        $orderStr = "";
        if($this->getOrders()) {
            $orderStr = " order by ".implode(",", $this->getOrders());
        }

        $limitStr = "";
        if($this->getLimit()) {
            $limitParams = array();
            if($this->getOffset()) {
                $limitParams[] = "?";
                $this->bindValues[] = $this->getOffset();
            }
            $limitParams[] = "?";
            $this->bindValues[] = $this->getLimit();
            $limitStr = " limit ".implode(",", $limitParams);
        }
        $this->sqlString = "SELECT ".$fieldstr." FROM `".$this->getTableName()."`".$whereStr.$orderStr.$limitStr;
        return $this;
    }

}