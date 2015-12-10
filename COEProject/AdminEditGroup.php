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
		  <h2>Select an appointment to change</h2>
		  <div class="field">
          <?php
            $debug = false;
            include('../CommonMethods.php');
			include('../Appointment.php');
            $COMMON = new Common($debug);

			// Get all group appointments from database
			$appointments = Appointment::searchAppointments($COMMON, 0, null, null, null, true, -1, '');
			//first item in row
            if(count($appointments) > 0){
              echo("<form action=\"AdminProcessEditGroup.php\" method=\"post\" name=\"Confirm\">");
			  echo("<table border='1px'>\n<tr>");
			  echo("<tr><td width='320px'>Time</td><td>Majors</td><td>Seats Enrolled</td><td>Total Seats</td></tr>\n");

			  // Display each appointment
              foreach ($appointments as $appt) {
                echo("<tr><td><label for='".$appt->getID()."'><input type=\"radio\" id='".$appt->getID()."' name=\"GroupApp\" 
                  required value=\"".$appt->getID()."\">");
                echo(date('l, F d, Y g:i A', strtotime($appt->getTime())). "</label></td>");
                if($appt->getMajor()){
                  echo("<td>".$appt->convertMajor()."</td>"); 
                }
                else{
                  echo("<td>Available to all majors</td>"); 
                }

                echo("<td>".$appt->getEnrolledNum()."</td><td>".$appt->getMax());
				echo("</label>");
                echo("</td></tr>");
                
              }

			  echo("</table>");

              echo("<div class=\"nextButton\">");
              echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Delete Appointment\">");
              echo("<input style=\"margin-left: 10px\" type=\"submit\" name=\"next\" class=\"button large go\" value=\"Edit Appointment\">");
              echo("</div>");
			  echo("</form>");
			  echo("<form method=\"link\" action=\"AdminUI.php\">");
              echo("<input type=\"submit\" name=\"next\" class=\"button large\" value=\"Cancel\">");
              echo("</form>");
            }
            else{
              echo("<br><b>There are currently no group appointments scheduled.</b>");
              echo("<br><br>");
              echo("<form method=\"link\" action=\"AdminUI.php\">");
              echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Return to Home\">");
              echo("</form>");
            }
          ?>
  </div>
		</div>
		<?php
		  include('../Footer.html');
	    ?>
      </div>
	<?php include('./workOrder/workButton.php'); ?>
  </div>
  </body>
  
</html>
