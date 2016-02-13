<?php
class Database {
	private $host = "127.0.0.1";
	private $port = "3306";
	private $user = "root";
	private $database = "case_based_reasoning";
	private $password = "";
	private $connection = null;
	private $lastQuery = "";
	private $mysqliResult;
	function __construct() {
		$this->connection = new mysqli ( $this->host, $this->user, $this->password, $this->database, $this->port );
	}
	public function query($query) {
		$mysqliResult = $this->connection->query ( $query );
		$this->lastQuery = $query;
		$this->mysqliResult = $mysqliResult;
	}
	public function getMysqliArray($returnType = MYSQL_NUM) {
		return $this->mysqliResult->fetch_array ( $returnType );
	}
	public function getLastQuery() {
		return $this->lastQuery;
	}
	public function close() {
		$this->connection->close ();
	}
}