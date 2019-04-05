<?php

class Database{

	public $connection;

	function __construct(){
		$this->open_db_connection();
	}

	public function open_db_connection(){
		try {
			//create PDO connection
			$this->connection = new PDO("mysql:host=".DB_HOST.";port=3306;dbname=".DB_NAME, DB_USER, DB_PASS);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			//show error
			echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			exit;
		}
	}

	public function query($sql){
		$result = $this->connection->query($sql);
		$this->confirm_query($result);
		return $this->connection->lastInsertId();;
	}

	private function confirm_query($result){
		if(!$result){
			die ("Query Failed" . $this->connection->error);
		}
	}

	public function escape_string($string){
		$escaped_string = $this->connection->quote($string);
		return $escaped_string;
	}

	public function the_insert_id(){
		return $this->connection->lastInsertId();
	}

} // End of class Database

$database = new Database();

?>