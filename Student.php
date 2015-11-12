<?php
require_once 'Base.php';
class Student extends Base {
	public __construct($id) {
		// Get the information from the student table for this student
		// Student ID used as unique identifier for students
		parent::__construct($id, 'Proj2Students', 'StudentID');
	}
	
	/* Raw table data functions */
	public getFirstName() {
		return $this->getInfo('FirstName');
	}
	
	public getLastName() {
		return $this->getInfo('LastName');
	}
	
	public getStudentID() {
		return $this->getInfo('StudentID');
	}
	
	public getEmail() {
		return $this->getInfo('Email');
	}
	
	public getMajor() {
		// Convert the major acronym to the fully spelled out version
		return $this->getInfo('Major');
	}
	
	public getStatus() {
		return $this->getInfo('Status');
	}
	
	/* Conversion functions */
	// Gets the fully spelled out version of the major
	public getConvertMajor() {
		return AbbToName($this->getMajor());
	}

	/* Static functions */
	// Creates new student with given first name, last name, student ID, email, and major. The major
	// should be an acronym. The student by default is given a status of 'N' for no appointment
	public static createStudent($firstName, $lastName, $studentID, $email, $major) {
		$this->doQuery("INSERT INTO `Proj2Students` (`FirstName`, `LastName`, `StudentID`, `Email`, `Major`, `Status`) 
		VALUES ('$firstName', '$lastName', '$studentID', '$email', '$major', 'N'")
	}
}
?>