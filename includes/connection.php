<?php
//define constants
define("DB_SERVER", "mysqlhost");
define("DB_USER", "baskerville");
define("DB_PASSWORD", "#Voll3yb4ll");
define("DB_NAME", "baskerville");

class Connection {

	//attributes
	private $mysqli;

	public function __construct() {
		$this->mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	}

	//Close connection
	public function close() {
		$this->mysqli->close();
	}

	//execute the passed in query and return result
	public function query($sql) {

		//execute query
		
		$result = $this->mysqli->query($sql);
		return $result;
	}

	// fetch a row from result_set as an associative array
	public function fetchArray($resultSet) {
		return $resultSet->fetch_array(MYSQLI_ASSOC);
	}

	public function getInsertID(){
		return $this->mysqli->insert_id;
	}

    public function escape($sValue){
    	return $this->mysqli->real_escape_string($sValue);
    }
}