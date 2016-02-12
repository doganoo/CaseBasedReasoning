<?php
class Database {
	private $host = "127.0.0.1";
	private $port = "3306";
	private $user = "root";
	private $database = "case_based_reasoning";
	private $password = "";
	private $connection = null;
	private $lastQuery = "";
	function __construct() {
		$this->connection = mysqli_connect ( $this->host, $this->user, $this->password, $this->database, $this->port );
	}
	public function query($query) {
		$mysqliResult = $this->connection->query ( $query );
		$this->lastQuery = $query;
		return $mysqliResult;
	}
	public function getLastQuery() {
		return $this->lastQuery;
	}
}