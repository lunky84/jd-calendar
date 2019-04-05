<?php

class Event extends Db_object{

	protected static $db_table = "events";
	protected static $db_table_fields = array('title', 'start_date');
	public $id;
	public $title;
	public $start_date;


	public static function find_by_dates($start, $end){

		return static::find_by_query("SELECT id, title, start_date FROM " . static::$db_table . " WHERE start_date BETWEEN '$start' AND '$end'");


	}



} // End of class Event

?>