<?php
require_once 'Base.php';
class Advisor extends Base {
	function Advisor($common, $id, $pass=null) {
		// Get record from Proj2Advisors table with the given id
		if ($pass == null)
		{
			// Already signed in
			parent::Base($common, $id, 'Proj2Advisors', 'Username');
		}
		else
		{
			// sign in screen
			parent::Base($common, $id, 'Proj2Advisors', 'Username', 'Password', $pass);
		}
		
	}
		
	/* Raw table data functions */
	
	function getFirstName() {
		return $this->getInfo('FirstName');
	}
	
	function getLastName() {
		return $this->getInfo('LastName');
	}
	
	function getUsername() {
		return $this->getInfo('Username');
	}
	
	function getPassword() {
		return $this->getInfo('Password');
	}
	
	function getOffice() {
		return $this->getInfo('Office');
	}
	
	/* Conversion functions */
	function convertFullName() {
		return $this->getInfo('FirstName') . ' ' . $this->getInfo('LastName');
	}
	/* Static functions */
	
	// Creates a new advisor in the database. Returns false if advisor already exists with
	// the given first name, last name, and user name. Returns true on successful insert.
	function createAdvisor($common, $firstName, $lastName, $username, $password, $office) {
		// Check if advisor already exists
		$rs = $this->doQuery("SELECT `id` FROM Proj2Advisors WHERE `FirstName`='$firstName' AND
		`LastName`='$lastName' AND `Username`='$username'", $common);
		if (mysql_num_rows($rs) > 0) {
			// Advisor already exists
			return false;
		} else {
			// Advisor does not exist, so create it
			$this->doQuery("INSERT INTO Proj2Advisors (`FirstName`, `LastName`, `Username`, `Password`, `Office`
			VALUES ('$firstName', '$lastName', '$username', '$password', '$office')", $common);
			return true;
		}
	}
}
?>