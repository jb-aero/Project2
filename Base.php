<?php
require_once 'CommonMethods.php';
include_once 'Conversion.php';
class Base {
	// All these are meant to be PRIVATE!! DON'T MESS WITH THEM!
	var $COMMON = null; // Don't create COMMON until necessary
	var $info = array();
	var $recordExists = false;
	
	//** THIS FUNCTION IS NOT MEANT TO BE INSTANTIATED **\\
	// Pull outs the record in the database with the given value ($id)
	// in the column specified by $field, from the table specified by $table
	// Can pass array to $id to create from already queried row
	function Base($common, $id, $table, $field='id', $field2='password', $pass=null) {
		$this->COMMON = $common;
		if (is_array($id)) {
			// Given info array from some other query
			$this->info = $id;
			$this->recordExists = true;
			return;
		}

		if ($pass == null) {
			$rs = $this->COMMON->executeQuery("SELECT * FROM `$table` WHERE `$field`='$id'", $_SEVER["PHP_SELF"]);
		} else {
			$rs = $this->COMMON->executeQuery("SELECT * FROM `$table` WHERE `$field`='$id' AND `$field2`='$pass'", $_SEVER["PHP_SELF"]);
		}
		
		
		// Query database for record from table with id
		if ($rs && mysql_num_rows($rs) > 0) {
			// Successful query - set info and cache
			$this->info = mysql_fetch_assoc($rs);
			$this->recordExists = true;
		}
	}
	
	// Get a specific piece of information
	function getInfo($key) {
		if (isset($this->info[$key])) {
			return $this->info[$key];
		} else {
			// Requested key not in info
			return false;
		}
	}
	
	function getID() {
		return $this->getInfo('id');
	}
	
	// Whether the given record exists in the database or not
	function exists() {
		return $this->recordExists;
	}
	
	// Do a query using COMMON
	function doQuery($query, $COMMON=null) {
		if (isset($this)) {
			$COMMON = $this->COMMON;
		}
		// Execute query using common
		return $COMMON->executeQuery($query, $_SERVER['PHP_SELF']);
	}
}
?>