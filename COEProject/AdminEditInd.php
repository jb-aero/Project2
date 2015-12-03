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
          <h2>Select which appointment you would like to change: </h2>
		  <div class="field">
		  
          <?php
            $debug = false;
            include('../CommonMethods.php');
			include('../Appointment.php');
			include('../Advisor.php');
			include('../Student.php');
            $COMMON = new Common($debug);

			// Get all individual appointments
			$appointments = Appointment::searchAppointments($COMMON, 'I', null, null, null, true, -1, '');
			// Check if there are appointments
            if(count($appointments) > 0){
              echo("<form action=\"AdminConfirmEditInd.php\" method=\"post\" name=\"Confirm\">");
              

			  echo("<table border='1px'>\n<tr>");
			  echo("<tr><td width='320px'>Time</td><td>Majors</td><td>Enrolled</td></tr>\n");
              
			  // Display all items
              foreach ($appointments as $appt) {
                $advisorname = getAdvisorName($appt->getAdvisorID());

                if($appt->getEnrolledID()){
                  $student = new Student($COMMON, trim($appt->getEnrolledID()));
                }

                echo("<tr><td><label for='".$appt->getID()."'><input type=\"radio\" id='".$appt->getID()."' name=\"IndApp\" 
                  required value=\"".$appt->getID()."\">");
                echo(date('l, F d, Y g:i A', strtotime($appt->getTime())). "</label></td>");
                if($appt->getMajor()){
                  echo("<td>".$appt->convertMajor()."</td>"); 
                }
                else{
                  echo("Available to all majors"); 
                }

                

                if($appt->getEnrolledId()){
                  echo("<td>".$student->getFirstName()." ".$student->getLastName()."</td>");
                }
                else{
                  echo("<td>Empty</td>");
                }
				echo("</tr>\n");
		
                
				
              }
              echo("</table>");

              echo("<div class=\"nextButton\">");
              echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Delete Appointment\">");
              echo("</div>");
			  echo("</form>");
			  echo("<form method=\"link\" action=\"AdminUI.php\">");
              echo("<input type=\"submit\" name=\"next\" class=\"button large\" value=\"Cancel\">");
              echo("</form>");
            }
            else{
              echo("<br><b>There are currently no individual appointments scheduled.</b>");
              echo("<br><br>");
			  echo("</td</tr>");
              echo("<form method=\"link\" action=\"AdminUI.php\">");
              echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Return to Home\">");
              echo("</form>");
            }
          ?>
		  
	</div>
	</div>
	<div class="bottom">
		<p style='color:red'>Please note that individual appointments can only be removed from schedule.</p>
	</div>
	</div>
	<?php include('./workOrder/workButton.php'); ?>

	</div>
  </body>
  
</html>

<?php
// just getting the advisor's name - use a cache
function getAdvisorName($id)
{
	global $debug; global $COMMON;
	static $cache = array();
	if ($id == 0) {
		// Group advising
		return 'Group';
	}
	// Check if it's in the cache
	else if (!isset($cache[$id])) {
		// Not in cache, so fetch from database and store in cache
		$cache[$id] = new Advisor($COMMON, $id);
	}
	// Return advisor's name from cache
	return $cache[$id]->convertFullName();
}
?>
