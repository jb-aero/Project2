<?php
require_once 'Base.php';
class Appointment extends Base {
	public __construct($id) {
		base::__construct($id, 'Proj2Appointments');
	}
	
	/* Raw Table data functions */
	public getTime() {
		return $this->getInfo('Time');
	}
	
	public getAdvisorID() {
		return $this->getInfo('AdvisorID');
	}
	
	public getMajor() {
		return $this->getInfo('Major');
	}
	
	public getEnrolledID() {
		return $this->getInfo('EnrolledID');
	}
	
	public getEnrolledNum() {
		return $this->getInfo('EnrolledNum');
	}
	
	public getMax() {
		return $this->getInfo('Max');
	}
	
	public getMeeting() {
		return $this->getInfo('Meeting');
	}

	/* Conversion functions */
	/* TODO: Add function to get full majors */
	
	/* Static functions */
	// Creates new appointment with given time, advisor ID, major, and student capacity.
	// Returns false if advisor already exists, or true for successful insert
	public static function createAppointment($time, $advisorID, $major, $max) {
		// Check for already existing appointment
		$rs = $this->doQuery("SELECT * FROM `Proj2Appointments` WHERE `Time` = '$time' AND `AdvisorID`='$advisorID'");
		if (mysql_num_rows($rs) > 0) {
			// Appointment already exists
			return false;
		} else {
			// Create new appointment
			$this->doQuery("INSERT INTO `Proj2Appointment` (`Time`, `AdvisorID`, `Major`, `Max`)
			VALUES ('$time', '$advisorID', '$major', '$max'");
		}
	}
	
	/* Search functions */
	public static function searchAppointments($date, $times, $advisorID, $major, $openOnly=true) {
		// Construct query string based on requested search criteria
		$query = "SELECT * FROM `Proj2Appointments` WHERE `Date` LIKE '$date'";
	}
}
?>