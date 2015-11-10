<?php
class Base {
	private static $debug = false;
	private static $COMMON = null;
	private static $cache = array();
	private $info = array();

	protected __construct($id, $table) {
		// Check for this item in the cache
		if (isset(self::$cache[$table][$id)) {
			// In cache, so use cached version
			$info = self::$cache[$table][$id];
		} else {
			// Not in cache, so query database for record from table with id
			$rs = self::$COMMON->executeQuery("SELECT * FROM $table WHERE `id`=$id", $_SEVER["PHP_SELF"]);
			if ($rs) {
				// Successful query - set info and cache
				$info = mysqli_fetch_assoc($rs);
				self::$cache[$table][$id] = $info;
			}
		}
	}
	
	// Get a specific piece of information
	public getInfo($key) {
		if (isset($info[$key])) {
			return $info[$key];
		} else {
			// Requested key not in info
			return false;
		}
	}
	
	public getID() {
		return $this->getInfo('id');
	}
	
	// Do a query using COMMON
	protected doQuery($query) {
		if (self::$COMMON == null) {
			// Common has not been initialized yet, so create and connect to ben38 database
			self::$COMMON = new Common($debug);
			self::$COMMON->connect('ben38');
		}
		// Execute query using common
		return self::$COMMON->executeQuery($query, $_SERVER['PHP_SELF']);
	}
	
	// Set debug status
	public static setDebug($debug) {
		// Set debug status, and update common, if it's been initalized
		self::$debug = $debug;
		if (self::$COMMON !== null) {
			self::$COMMON->$debug = $debug
		}
	}
}
?>