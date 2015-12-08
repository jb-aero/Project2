<?php
session_start();
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Confirm Appointment</title>
	<link rel='stylesheet' type='text/css' href='../css/standard.css'/>  </head>
  <body>
	<div id="login">
      <div id="form">
        <div class="top">
		<h1>Confirm Appointment</h1>
	    <div class="field">
		<form action = "StudProcessSch.php" method = "post" name = "SelectTime">
	    <?php
			$debug = false;
			include('../CommonMethods.php');
			include('../Student.php');
			include('../Advisor.php');
			include('../Appointment.php');
			$COMMON = new Common($debug);
			
			// Get student info from database
			$studid = $_SESSION["studID"];
			$student = new Student($COMMON, $studid);
			
			// Check if student already has appointment
			$appointments = Appointment::searchAppointments($COMMON, null, null, null, null, null, null, '', $studid);
			$reschedule = count($appointments) > 0;
			if($reschedule){
				// Student was already scheduled for appointment
				$appt = $appointments[0];
				$oldApptID = $appt->getID();
				$oldAdvisorID = $appt->getAdvisorID();
				$oldDatephp = strtotime($appt->getTime());
				
				if($oldAdvisorID != 0){
					// Individual advisor - get info from database
					$oldAdvisor = new Advisor($COMMON, $oldAdvisorID);
					$oldAdvisorName = $oldAdvisor->convertFullName();
					$oldAdvisorOffice = $oldAdvisor->getMeeting();
				}
				else{
					// Group adivising
					$oldAdvisorName = "Group";
				}
				
				echo "<h2>Previous Appointment</h2>";
				echo "<label for='info'>";
				echo "Advisor: ", $oldAdvisorName, "<br>";
				// Display advisor office for individual advisor
				if (isset($oldAdvisorOffice)) {
					echo "Office: ", $oldAdvisorOffice, "<br>";
				}
				echo "Appointment: ", date('l, F d, Y g:i A', $oldDatephp), "<br>";
				// Display meeting location
				echo "Meeting Location: ", $appt->getMeeting(), "</label><br>";
				// Hidden input for old appointment ID
				echo "<input type='hidden' name='oldAppID' value='$oldApptID'>";
			}
			
			$currentAdvisorName;
			$currentAdvisorID = $_POST["advisor"];
			$currentDatephp = strtotime($_POST["appTime"]);
			
			// Get appointment information for current appointment, even if it's taken
			$appointments = Appointment::searchAppointments($COMMON, $currentAdvisorID, $student->getMajor(), $_POST["appTime"], null, false, 1, '');
			$currentAppt = $appointments[0];
			$currentApptID = $currentAppt->getID();
			
			if($currentAdvisorID != 0){
				// Individual advisor, so get info from database
				$currentAdvisor = new Advisor($COMMON, $currentAdvisorID);
				$currentAdvisorName = $currentAdvisor->convertFullName();
				$currentAdvisorOffice = $currentAdvisor->getMeeting();
			}
			else{
				// Group advising appointment
				$currentAdvisorName = "Group";
			}
			
			echo "<h2>Current Appointment</h2>";
			echo "<label for='newinfo'>";
			echo "Advisor: ",$currentAdvisorName,"<br>";
			// Display office for individual advisor
			if (isset($currentAdvisorOffice)) {
				echo "Office: ", $currentAdvisorOffice, "<br>";
			}
			echo "Appointment: ",date('l, F d, Y g:i A', $currentDatephp),"<br>";
			echo "Meeting Location: ", $currentAppt->getMeeting(), "</label>";
			// Hidden input for appointment id
			echo "<input type='hidden' name='appID' value='$currentApptID'>";
		?>
        </div>
	    <div class="nextButton">
		<?php
			if($reschedule){
				echo "<input type='submit' name='finish' class='button large go' value='Reschedule'>";
			}
			else{
				echo "<input type='submit' name='finish' class='button large go' value='Submit'>";
			}
		?>
			<input style="margin-left: 50px" type="submit" name="finish" class="button large" value="Cancel">
	    </div>
		</form>
		</div>
		<?php
		  include('../Footer.html')
	    ?>
      </div>
    </div>
  </body>
</html>
