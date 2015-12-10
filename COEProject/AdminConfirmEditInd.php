<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Edit Individual Appointment</title>
    <script type="text/javascript">
    function saveValue(target){
	var stepVal = document.getElementById(target).value;
	alert("Value: " + stepVal);
    }
    </script>
	<link rel='stylesheet' type='text/css' href='../css/standard.css'/>  
  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
          <h1>Removed Appointment</h1><br>
		  <div class="field">
          <?php
            $debug = false;
            include('../CommonMethods.php');
			include('../Appointment.php');
			include('../Student.php');
			include('../Advisor.php');
            $COMMON = new Common($debug);
            $indID = $_POST["IndApp"];
			// Get appointment info from the database
            $appt = new Appointment($COMMON, $indID);

            $adv = $appt->getAdvisorID();
			$advisor = new Advisor($COMMON, $adv);

            if($appt->getEnrolledID()){
			  $student = new Student($COMMON, trim($appt->getEnrolledID()));
              $std = $student->getFirstName() . " " . $student->getLastName();
              $eml = $student->getEmail();
            }

            $sql = "DELETE FROM `Proj2Appointments` WHERE `id`='$indID'";
            $rs = $COMMON->executeQuery($sql, "Advising Appointments");

            echo("Time: ". date('l, F d, Y g:i A', strtotime($appt->getTime())). "<br>");
            echo("Advisor: ".$advisor->convertFullName()."<br>");
            echo("Majors included: ");
            if($appt->getMajor()){
              echo($appt->convertMajor()."<br>"); 
            }
            else{
              echo("Available to all majors<br>"); 
            }
            echo("Enrolled: ");
            if($appt->getEnrolledID()){
              echo("$std</b>");
              $sql = "UPDATE `Proj2Students` SET `Status`='C' WHERE `StudentID` = '".trim($appt->getEnrolledID())."'";
              $rs = $COMMON->executeQuery($sql, "Advising Appointments");
              $message = "The following appointment has been deleted by the adminstration of your advisor: " . "\r\n" .
                "Time: " . $appt->getTime() . "\r\n" . 
                "Advisor: " . $advisor->convertFullName() . "\r\n" . 
                "Student: $std" . "\r\n" . 
                "To schedule for a new appointment, please log back into the UMBC COEIT Engineering and Computer Science Advising webpage." . "\r\n" .
		"http://coeadvising.umbc.edu  -> COEIT Advising Scheduling \r\n Reminder, this is only accessible on campus."; 
              mail($eml, "Your Advising Appointment Has Been Deleted", $message); 
            }
            else{
              echo("Empty");
            }
			?>
			<br><br>
			<form method="link" action="AdminUI.php">
				<input type="submit" name="home" class="button large go" value="Return to Home">
			</form>
		</div>
    </div>
	<div class="bottom">
		<?php
		if($appt->getEnrolledID()){
              echo "<p style='color:red'>$std has been notified of the cancellation.</p>";
        }
		?>
	</div>
		<?php
		  include('../Footer.html');
	    ?>
      </div>
    </div>
  </body>
  
</html>
