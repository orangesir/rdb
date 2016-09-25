<?php
namespace Rdb;

abstract class Sql {

    protected $where;
    protected $sqlString = "";
    protected $bindValues = array();
    protected $tableName;
    protected $isBuild = true;

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    public function getTableName() {
        return $this->tableName;
    }

    abstract public function build();

    /**
     * @return array :array($value...)
     */
    abstract public function binds();

    /**
     * @return string :sql string with ?
     */
    abstract public function sqlStr();

    /**
     * @return string :sql after replace ? to $value
     */
    public function __toString() {
        $binds = $this->binds();
        foreach ($binds as $key => $value) {
            if(is_string($value)) {
                $binds[$key] = "'".$value."'";
            }
        }
        return str_replace(array_pad(array(), count($binds), "?"), $binds, $sthis->sqlStr);
    }

    public function setWhere(Where $where) {
        $this->where = $where;
    }

    public function getWhere() {
        return $this->where;
    }
}