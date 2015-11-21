<?php
session_start();
include("Conversion.php");
include("../CommonMethods.php");
$debug = false;
$COMMON = new Common($debug);

// See if student is in database
$student = new Student($COMMON, strtoupper($_POST["studID"]));
if (!$student->exists()) {
	// Student does not exist, so create in database
	Student::createStudent($common, $_POST["firstN"], $_POST["lastN"], $_POST["studID"], $_POST["emai"], NameToAbb($_POST["major"]));
}

// Save student ID for session
$_SESSION["studID"] = strtoupper($_POST["studID"]);

header('Location: 02StudHome.php');
?>