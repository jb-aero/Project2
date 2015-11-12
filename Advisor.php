<?php
require_once 'Base.php';
class Advisor extends Base {
	public __construct($id) {
		// Get reocrd from Proj2Advisors table with the given id
		parent::__construct($id, 'Proj2Advisors');
	}
		
	/* Raw table data functions */
	
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
	
	/* Static functions */
	
	// Creates a new advisor in the database. Returns false if advisor already exists with
	// the given first name, last name, and user name. Returns true on successful insert.
	public static createAdvisor($firstName, $lastName, $username, $password, $office) {
		// Check if advisor already exists
		$rs = $this->doQuery("SELECT `id` FROM Proj2Advisors WHERE `FirstName`='$firstName' AND
		`LastName`='$lastName' AND `Username`='$username'");
		if (mysql_num_rows($rs) > 0) {
			// Advisor already exists
			return false;
		} else {
			// Advisor does not exist, so create it
			$this->doQuery("INSERT INTO Proj2Advisors (`FirstName`, `LastName`, `Username`, `Password`, `Office`
			VALUES ('$firstName', '$lastName', '$username', '$password', '$office')");
			return true;
		}
	}
}
?>