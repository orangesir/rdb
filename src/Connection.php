<?php
namespace Rdb;

/**
 * Base DB Object:use PDO to use DDL/DML
 * @throw \PDOException 
 */
class Connection {

	private $pdo;
	private $name;
	private $statements;
	private $lastStatement;
	private $config;
	
	public function __construct($dbname) {
		$this->name = $dbname;

		$config = Config::get($dbname);

		$pdo = new \PDO($config["dsn"], $config["username"], $config["password"]);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES,true);

		$this->pdo = $pdo;
		$this->config = $config;
	}

	/**
	 * @param $stateSql sql语句:select * from info where id=?
	 * @param $varList array($param1, $param2)
	 * @return bool 成功true,失败false
	 */
	public function execute(Sql $sql, $throwException=true) {
		$statement = $this->getStatement($sql);
		$statement->closeCursor();
		$this->lastStatement = $statement;
		$result = $statement->execute($sql->binds());
		if($throwException) {
			throw new Exception\DbException($sql->__toString()." : execute failure!");
		}
		return $result;
	}

	/**
	 * sql对应的statement上一次执行数
	 */
	public function rowCount(Sql $sql) {
		$statement = $this->getStatement($sql);
		return $statement->rowCount();
	}

	/**
	 * 主键不自增时返回 0
	 * 主键自增,返回最后插入行的ID或序列值
	 */
	public function lastInsertId() {
		return $this->pdo->lastInsertId();
	}

	/**
	 * @param string $colName:查询结果中的某列名
	 * @return
	 *   执行失败 false
	 *   查询结果为空 array()
	 *   查询结果:
	 *      array(
	 *         $colValue => array(
	 *             filed => $value,
	 *             ...
	 *         )
	 *      )
	 *      $colValue 查询结果中对应的$colName列的值
	 *      参数$colName不传时，查询结果数组键值为0,1,2...
	 */
	public function getRows(SelectSql $sql, $colName=null) {
		$this->execute($sql);
		$rows = $this->lastStatement->fetchAll(\PDO::FETCH_ASSOC);
		if($colName) {
			if(!$sql->hasCol($colName)) {
				throw new Exception\DbException($sql->getTableName()." col {$colName} notExist!");
			}
			$filterRows = array();
			foreach ($rows as $row) {
				$filterRows[$row[$key]] = $row;
			}
			$rows = $filterRows;
		}
		return $rows;
	}

	public function getRow(SelectSql $sql) {
		$sql->setLimit(1);
		$this->execute($sql);
		$row = $this->lastStatement->fetch(\PDO::FETCH_ASSOC);
		return $row ? : array();
	}

	/**
	 * 获取列组成list返回
	 */
	public function getList(SelectSql $sql) {
		$this->execute($sql);
		return $this->lastStatement->fetchAll(\PDO::FETCH_COLUMN);
	}

	/**
	 * 查询所有第一行的第一个字段
	 */
	public function getOne(SelectSql $sql) {
		$sql->setLimit(1);
		$this->execute($sql);
		$row = $this->lastStatement->fetch(\PDO::FETCH_NUM);
		return $row ? $row[0] : null;
	}

	private function getStatement(Sql $sql) {
		$sqlStr = trim($sql->sqlStr());
		if(!isset($this->statements[$sqlStr])) {
			$this->statements[$sqlStr] = $this->pdo->prepare($sqlStr);
		}
		return $this->statements[$sqlStr];
	}

	public function __toString() {
		return $this->config["dsn"]."<|>".$this->config["username"]."<|>".$this->config["password"];
	}

}