<?php

class Db_object {

	public static function find_all(){
		return static::find_by_query("SELECT * FROM " . static::$db_table);
	}


	public static function find_by_id($id){
		$the_result_array = static::find_by_query("SELECT * FROM " . static::$db_table . " WHERE id = $id LIMIT 1");
		return !empty($the_result_array) ? array_shift($the_result_array) : false;
	}

	public static function find_by_query($sql){

		global $database;

		try {
			$st = $database->connection->prepare($sql);
			$st->execute();
			$results = $st->fetchAll(PDO::FETCH_ASSOC);

			foreach ($results as $row){
				$the_object_array[] = static::instantiation($row);
			}

		} catch(PDOException $e) {
			echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}

		if (isset($the_object_array)) {
			return $the_object_array;
		} else {
			return false;
		}
	}

	public static function instantiation($the_record){

		$calling_class = get_called_class();

		$the_object = new $calling_class;

		foreach ($the_record as $the_attribule => $value) {
			if($the_object->has_the_attribute($the_attribule)){
				$the_object->$the_attribule = $value;
			}
		}

		return $the_object;

	}

	private function has_the_attribute($the_attribule){
		$object_properties = get_object_vars($this);
		return array_key_exists($the_attribule, $object_properties);
	}

}


?>