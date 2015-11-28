<?php
session_start();
$debug = false;
include('../CommonMethods.php');
include('../Student.php');
include('../Appointment.php');
$COMMON = new Common($debug);

if($_POST["cancel"] == 'Cancel'){
	// Get student info from database
	$studid = $_SESSION["studID"];
	$student = new Student($COMMON, $studid);
	
	// Find student's appointment
	$appointments = Appointment::searchAppointments($COMMON, null, null, null, null, null, null, '', $studid);
	//remove stud from EnrolledID
	$appt = $appointments[0];
	$apptID = $appt->getID();
	$newIDs = str_replace($studid, "", $appt->getEnrolledID());
	
	$sql = "update `Proj2Appointments` set `EnrolledNum` = EnrolledNum-1, `EnrolledID` = '$newIDs' where `id`='$apptID'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	
	//update stud status to noApp
	$sql = "update `Proj2Students` set `Status` = 'N' where `StudentID` = '$studid'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	
	$stat = 'ca';
}
else{
	$stat = 'k';
}
header("Location: 12StudExit.php?stat=$stat");
?>