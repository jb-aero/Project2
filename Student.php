<?php
require_once 'Base.php';
class Student extends Base {
	function Student($common, $id) {
		// Get the information from the student table for this student
		// Student ID used as unique identifier for students
		parent::Base($common, $id, 'Proj2Students', 'StudentID');
	}
	
	/* Raw table data functions */
	function getFirstName() {
		return $this->getInfo('FirstName');
	}
	
	function getLastName() {
		return $this->getInfo('LastName');
	}
	
	function getStudentID() {
		return $this->getInfo('StudentID');
	}
	
	function getEmail() {
		return $this->getInfo('Email');
	}
	
	function getMajor() {
		// Convert the major acronym to the fully spelled out version
		return $this->getInfo('Major');
	}
	
	function getStatus() {
		return $this->getInfo('Status');
	}
	
	/* Conversion functions */
	// Gets the fully spelled out version of the major
	function getConvertMajor() {
		return AbbToName($this->getMajor());
	}

	/* Static functions */
	// Creates new student with given first name, last name, student ID, email, and major. The major
	// should be an acronym. The student by default is given a status of 'N' for no appointment
	function createStudent($common, $firstName, $lastName, $studentID, $email, $major) {
		parent::doQuery("INSERT INTO `Proj2Students` (`FirstName`, `LastName`, `StudentID`, `Email`, `Major`, `Status`) 
		VALUES ('$firstName', '$lastName', '$studentID', '$email', '$major', 'N'", $common);
	}
}
?>