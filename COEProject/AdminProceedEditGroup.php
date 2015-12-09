<?php
session_start();
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
          <h1>Edit Group Appointment</h1>
		  <div class="field">
          <?php
            $debug = false;
            include('../CommonMethods.php');
			include('../Appointment.php');
            $COMMON = new Common($debug);

            $appID = $_GET["app"];
			// Get appointment info from database
            $appt = new Appointment($COMMON, $appID);

            echo("<form action=\"AdminConfirmEditGroup.php\" method=\"post\" name=\"Edit\">");
			// Hidden field to mark it is not deletion
			echo("<input type=\"hidden\" name=\"edit\" value=\"edit\">");
			// Hidden field for appointment ID
			echo("<input type=\"hidden\" name=\"GroupApp\" value=\"".$appt->getID()."\">");
            echo("Time: ". date('l, F d, Y g:i A', strtotime($appt->getTime())). "<br>");
            echo("Majors included: ");
            if($appt->getMajor()){
              echo($appt->convertMajor(', ')."<br>"); 
            }
            else{
              echo("Available to all majors<br>"); 
            }
            echo("Number of students enrolled: ".$appt->getEnrolledNum()." <br>");
            echo("Location: ");
            echo($appt->getMeeting());
            echo("<br>");
            echo("Student limit: ");
            echo("<input type=\"number\" id=\"stepper\" name=\"stepper\" min=\"".$appt->getEnrolledNum()."\" max=\"".$appt->getMax()."\" value=\"".$appt->getMax()."\" />");
            echo("<br><br>");

            echo("<div class=\"nextButton\">");
            echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Submit\">");
            echo("</div>");
            echo("</div>");
            echo("<div class=\"bottom\">");
            if($appt->getEnrolledNum() > 0){
              echo "<p style='color:red'>Note: There are currently ".$appt->getNumEnrolled()." students enrolled in this appointment. <br>
                    The student limit cannot be changed to be under this amount.</p>";
            }
            echo("</div>");
          ?>
		  </div>
  </div>
  </div>
  </form>
  </body>
  
</html>
