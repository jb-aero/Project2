<?php
session_start();
$debug = false;
include('../CommonMethods.php');
include('../Advisor.php');
$COMMON = new Common($debug);

$studID = $_SESSION["studID"];
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>View Appointment</title>
	<link rel='stylesheet' type='text/css' href='../css/standard.css'/>
  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
		<h1>View Appointment</h1>
	    <div class="field">
	    <?php
			$sql = "select * from Proj2Appointments where `EnrolledID` like '%$studID%'";
			$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
			// if for some reason there really isn't a match, (something got messed up, tell them there really isn't one there)
			$num_rows = mysql_num_rows($rs);

			if($num_rows > 0)
			{
				$row = mysql_fetch_row($rs); // get legit data
				$advisorID = $row[2];
				$datephp = strtotime($row[1]);
				
				if($advisorID != 0){
					// Individual advisor - Get advisor info from database
					$advisor = new Advisor($COMMON, $advisorID);
					$advisorOffice = $advisor->getOffice();
					$advisorName = $advisor->convertFullName();
				}
				else{
					// Group advisor
					$advisorName = "Group";
				}
			
				echo "<label for='info'>";
				echo "Advisor: ", $advisorName, "<br>";
				// If individual advisor, display office location
				if (isset($advisorOffice)) {
					echo "Office: ", $advisorOffice, "<br>";
				}
				echo "Appointment: ", date('l, F d, Y g:i A', $datephp), "<br>";
				// Display appointment location
				echo "Meeting Location: ", $row[7], "</label>";
			}
			else // something is up, and there DB table needs to be fixed
			{
				echo("No appointment was detected. It may have been cancelled. Please make another appointment.");
				$sql = "update `Proj2Students` set `Status` = 'N' where `StudentID` = '$studID'";
				$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
			}
	

		?>
        </div>
	    <div class="finishButton">
			<button onclick="location.href = '02StudHome.php'" class="button large go" >Return to Home</button>
	    </div>
		</div>
		</form>
  </body>
</html>