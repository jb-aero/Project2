<?php
session_start();
$debug = false;

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Edit Group Appointment</title>
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
		<div class="field">
        <?php
		  // Deletion only if edit hidden field is not set
          $delete = !isset($_POST["edit"]);
		  // Get appointment ID from post or get
		  if (isset($_POST["GroupApp"])) {
		  	$appID = $_POST["GroupApp"];
		  } else {
		  	$appID = $_GET["app"];
		  }
 
          include('../CommonMethods.php');
		  include('../Appointment.php');
		  include('../Student.php');
          $COMMON = new Common($debug);

		  // Get appointment info
		  $appt = new Appointment($COMMON, $appID);
          if($delete == true){
            echo("<h1>Removed Appointment</h1><br>");

            $stds = $appt->getEnrolledID();
	   		$stds = trim($stds); // had some side white spaces sometimes
	   		$stds = split(" ", $stds);

            if($debug) { var_dump("\n<BR>EMAILS ARE: $stds \n<BR>"); }
		// foreach($stds as $element) { echo("->".$element."\n"); }

            if($stds[0])
	    	{


              foreach($stds as $element){
                $element = trim($element);
				$sql = "UPDATE `Proj2Students` SET `Status`='C' WHERE `StudentID` = '$element'";
                $rs = $COMMON->executeQuery($sql, "Advising Appointments");
				// Get student info from database
				$student = new Student($COMMON, $element);

                $eml = $student->getEmail();
                $message = "The following group appointment has been deleted by the adminstration of your advisor: " . "\r\n" .
                "Time: ". $appt->getTime() . "\r\n" . 
                "To schedule for a new appointment, please log back into the UMBC COEIT Engineering and Computer Science Advising webpage." . "\r\n" .
				"http://coeadvising.umbc.edu  -> COEIT Advising Scheduling \r\n Reminder, this is only accessible on campus."; 

                mail($eml, "Your COE Advising Appointment Has Been Deleted", $message);
              }
            }

            $sql = "DELETE FROM `Proj2Appointments` WHERE `id`='$appID'";
            $rs = $COMMON->executeQuery($sql, "Advising Appointments");

            echo("Time: ". date('l, F d, Y g:i A', strtotime($appt->getTime())). "<br>");
            echo("Majors included: ");

            if($appt->getMajor()){ echo($appt->convertMajor(', ')."<br>"); }
            else{ echo("Available to all majors<br>"); }

            echo("Number of students enrolled: ".$appt->getEnrolledNum()."<br>");
            echo("Student limit: ".$appt->getMax());
            echo("<br><br>");
            echo("<form method=\"link\" action=\"AdminUI.php\">");
            echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Return to Home\">");
            echo("</form>");
            echo("</div>");
            echo("<div class=\"bottom\">");
            if($stds[0]){
              echo "<p style='color:red'>Students have been notified of the cancellation.</p>";
            }
          }
          else{
            echo("<h1>Changed Appointment</h1><br>");
			echo("<h2>Previous Appointment:</h2>");
            echo("Time: ". date('l, F d, Y g:i A', strtotime($appt->getTime())). "<br>");
            echo("Majors included: ");
            if($appt->getMajor()){
              echo($appt->convertMajor(', ')."<br>"); 
            }
            else{
              echo("Available to all majors<br>"); 
            }
            echo("Number of students enrolled: ".$appt->getEnrolledNum()."<br>");
            echo("Student limit: ".$appt->getMax());
            echo("<h2>Updated Appointment:</h2>");
            $limit = $_POST["stepper"];
            echo("<b>Time: ". date('l, F d, Y g:i A', strtotime($appt->getTime())). "</b><br>");
            echo("<b>Majors included: ");
            if($appt->getMajor()){
              echo($appt->convertMajor(', ')."</b><br>"); 
            }
            else{
              echo("Available to all majors</b><br>"); 
            }
            echo("<b>Number of students enrolled: ".$appt->getEnrolledNum()." </b><br>");
            echo("<b>Student limit: $limit</b>");

            $sql = "UPDATE `Proj2Appointments` SET `Max`='$limit' WHERE `id`='$appID'";
            $rs = $COMMON->executeQuery($sql, "Advising Appointments"); 

            echo("<br><br>");
            echo("<form method=\"link\" action=\"AdminUI.php\">");
            echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Return to Home\">");
            echo("</form>");
          }
        ?>
	</div>
	</div>
	</div>
	</form>
  </body>
  
</html>
