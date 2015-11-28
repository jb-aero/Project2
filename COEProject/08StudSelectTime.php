<?php
session_start();
$debug = false;

if(isset($_POST["advisor"])){
	// Came from selecting individual advisor
	$localAdvisor = $_POST["advisor"];
} else {
	// Selected group advisor
	$localadvisor = 0;
}

include('../CommonMethods.php');
include('../Student.php');
include('../Advisor.php');
include('../Appointment.php');
$COMMON = new Common($debug);

$student = new Student($COMMON, $_SESSION['studID']);
if ($localAdvisor != 0) {
	// Get information for individual advisor
	$advisor = new Advisor($COMMON, $localAdvisor);
}
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Select Appointment</title>
	<link rel='stylesheet' type='text/css' href='../css/standard.css'/>

  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
		<h1>Select Appointment Time</h1>
	    <div class="field">
		<form action = "10StudConfirmSch.php" method = "post" name = "SelectTime">
	    <?php
			// Hidden form field for advisor ID
			echo "<input type='hidden' name='advisor' value='$localAdvisor'>";

// http://php.net/manual/en/function.time.php fpr SQL statements below
// Comparing timestamps, could not remember. 

			$curtime = time();

			// Search for open appointments with given advisor, student's major, and that are in the future
			$appointments = Appointment::searchAppointments($COMMON, $localAdvisor, $student->getMajor());
			// Display title
			if ($localAdvisor != 0)  // for individual conferences only
			{ 
				echo "<h2>Individual Advising</h2><br>";
				echo "<label for='prompt'>Select appointment with ",$advisor->convertFullName(),":</label><br>";
			}
			else // for group conferences
			{
				echo "<h2>Group Advising</h2><br>";
				echo "<label for='prompt'>Select appointment:</label><br>";
			}
			// Display all appointment options on screen
			foreach($appointments as $appt){
				$datephp = strtotime($appt->getTime());
				echo "<label for='",$appt->getID(),"'>";
				echo "<input id='",$appt->getID(),"' type='radio' name='appTime' required value='", $appt->getTime(), "'>", date('l, F d, Y g:i A', $datephp) ,"</label><br>\n";
			}
		?>
        </div>
		<?php 
		// Do not allow continuing if there are no appointments for this advisor
		if (count($appointments) > 0): ?>
	    <div class="nextButton">
			<input type="submit" name="next" class="button large go" value="Next">
	    </div>
		<?php endif; ?>
		</form>
		<div>
		<form method="link" action="02StudHome.php">
		<input type="submit" name="home" class="button large" value="Cancel">
		</form>
		</div>
		<div class="bottom">
		<p>Note: Appointments are maximum 30 minutes long.</p>
		<p style="color:red">If there are no more open appointments, contact your advisor or click <a href='02StudHome.php'>here</a> to start over.</p>
		</div>
  </body>
</html>