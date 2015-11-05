<?php
require_once 'CommonMethods.php';
class Advisor {
	private static $debug = false;
	static $COMMON = null;
	private $info = array();
	public Advisor($id) {
		// Query database for advisor with id
		$rs = self::$COMMON->executeQuery("SELECT * FROM Proj2Advisors WHERE `id`=$id", $_SEVER["PHP_SELF"]);
		if ($rs) {
			// Successful query
			$info = mysqli_fetch_assoc($rs);
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
	
	public getFirstName() {
		return $this->getInfo('FirstName');
	}
	
	public getLastName() {
		return $this->getInfo('LastName');
	}
	
	public getUsername() {
		return $this->getInfo('Username');
	}
	
	public getPassword() {
		return $this->getInfo('Password');
	}
	
	public getOffice() {
		return $this->getInfo('Office');
	}
	
	// Initialize COMMON for database methods
	public static initStatic() {
		$COMMON = new $COMMON(self::$debug);
	}
	
	// Creates a new advisor in the database. Returns false if advisor already exists with
	// the given first name, last name, and user name. Returns true on successful insert.
	public static createAdvisor($firstName, $lastName, $username, $password, $office) {
		// Check if advisor already exists
		$rs = $COMMON->executeQuery("SELECT `id` FROM Proj2Advisors WHERE `FirstName`=$firstName AND
		`LastName`=$lastName AND `Username`=$username", $_SERVER['PHP_SELF']);
		if (mysqli_num_rows($rs) > 0) {
			// Advisor already exists
			return false;
		} else {
			// Advisor does not exist, so create it
			$COMMON->executeQuery("INSERT INTO Proj2Advisors (`FirstName`, `LastName`, `Username`, `Password`, `Office`
			VALUES ($firstName, $lastName, $username, $password, $office", $_SERVER['PHP_SELF']);
			return true;
		}
	}
}

Advisor::initStatic();
?>