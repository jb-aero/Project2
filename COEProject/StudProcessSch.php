<?php
session_start();
$debug = false;
include('../CommonMethods.php');
include('../Appointment.php');
include('../Student.php');
include('../Advisor.php');
$COMMON = new Common($debug);

if($_POST["finish"] == 'Cancel'){
	// Will just go home
}
else{
	// Get student info from database
	$studid = $_SESSION["studID"];
	$student = new Student($COMMON, $studid);
	// Get new appointment info from database
	$newApptID = $_POST['appID'];
	$newAppt = new Appointment($COMMON, $newApptID);
	// Get new advisor ID
	$advisorID = $newAppt->getAdvisorID();

	if(debug) { echo("Advisor -> $advisor<br>\n"); }


	// ************************ Lupoli 9-1-2015
	// we have to check to make sure someone did not steal that spot just before them!! (deadlock)
	// if the spot was taken, need to stop and reset
	if( isStillAvailable($_POST['appID']) ) // then good, take that spot
	{ } 
	else // spot was taken, tell them to pick another
	{
		if($debug == false) 
		{
			header('Location: 13StudDenied.php');
			return;
		}
	}
	
	if($_POST["finish"] == 'Reschedule'){
		// Get info from database about old appointment
		$oldApptID = $_POSET["oldAppID"];
		$oldAppt = new Appointment($COMMON, $oldApptID);
		//remove stud from EnrolledID
		$newIDs = str_replace($studid, "", $oldAppt->getEnrolledID());
		
		$sql = "update `Proj2Appointments` set `EnrolledNum` = EnrolledNum-1, `EnrolledID` = '$newIDs' where `id`='$oldApptID'";
		$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	}
	// Schedule new app
	// Add new student ID to list of ID's and update enrollment total
	$newIDs = $newAppt->getEnrolledID() . " $studid";
	$sql = "update `Proj2Appointments` set `EnrolledNum` = EnrolledNum+1, `EnrolledID` = '$newIDs' where `id`='$newApptID'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

	//update stud status to ''
	$sql = "update `Proj2Students` set `Status` = '' where `StudentID` = '$studid'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

}
if($debug == false) { header('Location: 12StudExit.php'); }



function isStillAvailable($appID)
{
	// advisor could be "Group"
	global $debug; global $COMMON;
	$sql "select `EnrolledNum`, `Max` from `Proj2Appointments` where `id`='$appID'";  }
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	$row = mysql_fetch_row($rs);

	// if max [1] =< EnrolledNum[0], then the spot was indeed taken
	if($row[1] > $row[0]) // then all good
	{ 
		if($debug) { echo("spot available\n<br>"); }
		return true; 
	}
	else // spot was taken
	{
		if($debug) { echo("spot NOT available\n<br>"); }	
		return false; 
	}

}

?>


