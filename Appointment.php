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
	
	// Convert major acronym list to full values
	// separator: delimiter to separate full major names in final string
	function convertMajor($separator=' ') {
		// Separate the major acronym string into an array of acronyms
		$majors = explode(' ', $this->getMajor());
		// Change all acronyms to major names
		for ($i = 0; $i < count($majors); $i++) {
			$majors[$i] = AbbToName($majors[$i]);
		}
		
		// Convert array back into string using separator
		return implode($separator, $majors);
	}
	
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
	// date: day for appointment
	// times: array of times for the appointments
	// advisorID: 0 = group, 1+ = specific individual advisor, I = all individual advisors
	// major: acronym of major that appointment must be available for
	// limit: maximum number of appointments to get; -1 = all
	// futureOnly: only get appointments after the current date and time
	// filter: '' = all appointment statuses, 0 = only open appointments, 1 = only closed appointments
	// studentID: the student ID that must be in the enrolled list; Empty = any students
	public static function searchAppointments($date, $times, $advisorID, $major, $limit=30,  $futureOnly=true, $filter=0, $studentID='') {
		// Construct query string based on requested search criteria
		// Empty major means all majors
		$query = "SELECT * FROM `Proj2Appointments` WHERE `Time` LIKE '$date' AND (`Major` LIKE '%$major%' OR `Major` = '') AND 
			AND `EnrolledID` LIKE '%$studentID%' AND `AdvisorID` ";
		if ($advisorID == 'I') {
			// All individual appointments
			$query .= "!= '0'"
		} else {
			// Group or specific advisor
			$query .= "= '$advisorID'";
		}
		if ($filter != '') {
			// Status filter applied
			$query .= " AND `EnrolledNum` "
			if ($filter == 0) {
				// Open appointments only
				$query .= "<";
			} else {
				// Closed appointments only
				$query .= "="
			}
			$query .= " `Max`";
		}
		if ($futureOnly) {
			// Only include appointments after current date and time
			$query .= " AND `Time` > '".date('Y-m-d H:i:s')."'";
		}
		
		// Add times to query
		if (count($times) > 0) {
			// Add first time
			$query .= " AND (`Time` LIKE '" . $times[0] . "'";
			// Add all times after first (use or because it can be any of the given times)
			for ($i = 0; $i < count($times); $i++) {
				$query .= " OR `Time` LIKE '" . $times[$i] . "'";
			}
			$query .= ")";
		}
		
		// Add ordering by time
		$query .= " ORDER BY `Time` ASC";
		if ($limit != -1) {
			// Limit to maximum number of results
			$query .= " LIMIT $limit";
		}
		
		// Execute query
		$rs = $this->doQuery($query);
		
		// Create array of appointments
		$retArray = array();
		while ($appt = mysql_fetch_assoc($rs)) {
			$retArray[] = new Appointment($appt);
		}
		
		return $retArray;
	}
}
?>