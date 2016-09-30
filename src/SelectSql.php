<?php
namespace Rdb;

class SelectSql extends Sql {

    protected $fileds = array();
    protected $offset;
    protected $limit;
    protected $orders = array();

    public function setFileds(array $fileds) {
        $this->fileds = $fileds;
    }

    public function appendFiled($filed) {
        $this->fileds[] = $filed;
    }

    public function appendFileds(array $fileds) {
        $this->fileds = array_merge($this->fileds, $fileds);
    }

    public function getFileds() {
        return $this->fileds;
    }

    public function ascFiled($filed) {
        $this->orders[] = "`".$filed."` asc";
    }

    public function descFiled($filed) {
        $this->orders[] = "`".$filed."` desc";
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
            throw new Exception\SqlException("no table name");
        }
        if($this->getFileds()) {
            $filedStr = "`".implode("`,`", $this->getFileds())."`";
        } else {
            $filedStr = "*";
        }

        $whereStr = "";
        if($where = $this->getWhere()) {
            $whereStr = $where->whereStr();
            $this->bindValues = array_merge($this->bindValues, $where->binds())
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
        $this->sqlString = "SELECT ".$filedStr." FROM `".$this->getTableName()."`".$whereStr.$orderStr.$limitStr;
        return $this;
    }

}