<?php
session_start();
$debug = false;
include('../CommonMethods.php');
include('../Student.php');
include('../Appointment.php');
include('../Advisor.php');
$COMMON = new Common($debug);
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Cancel Appointment</title>
    <link rel='stylesheet' type='text/css' href='../css/standard.css'/>
	</head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
		<h1>Cancel Appointment</h1>
	    <div class="field">
	    <?php
			// Get student info from database
			$studid = $_SESSION["studID"];
			$student = new Student($COMMON, $studid);
			
			// Get student's appointment from database
			$appointments = Appointment::searchAppointments($COMMON, null, null, null, null, null, null, '', $studid);
			$appt = $appointments[0];
			$oldAdvisorID = $appt->getAdvisorID();
			$oldDatephp = strtotime($appt->getTime());				
				
			if($oldAdvisorID != 0){
				// Individual advisor, so get advisor info
				$advisor = new Advisor($COMMON, $oldAdvisorID);					
				$oldAdvisorName = $advisor->convertFullName();
				$oldAdvisoOffice = $advisor->getOffice();
			}
			else{
				// Group advisor
				$oldAdvisorName = "Group";
			}
			
			// Display current appointment info
			echo "<h2>Current Appointment</h2>";
			echo "<label for='info'>";
			echo "Advisor: ", $oldAdvisorName, "<br>";
			// Display office for individual advisor
			if (isset($oldAdvisorOffice)) {
				echo "Office: ", $oldAdvisorOffice, "<br>";
			}
			echo "Appointment: ", date('l, F d, Y g:i A', $oldDatephp), "<br>";
			echo "Meeting Location: ", $appt->getMeeting(), "</label><br>";
		?>		
        </div>
	    <div class="finishButton">
			<form action = "StudProcessCancel.php" method = "post" name = "Cancel">
			<input type="submit" name="cancel" class="button large go" value="Cancel">
			<input type="submit" name="cancel" class="button large" value="Keep">
			</form>
	    </div>
		</div>
		<div class="bottom">
			<p>Click "Cancel" to cancel appointment. Click "Keep" to keep appointment.</p>
		</div>
		<?php
		  include('../Footer.html')
	    ?>
      </div>
    </div>
  </body>
</html>