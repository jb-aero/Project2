<?php
session_start();
$debug = false;
include('../CommonMethods.php');
include('../Appointment.php');
include('../Advisor.php');
$COMMON = new Common($debug); 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Search Appointments</title>
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
			<h1>Search results</h1>
			<div class="field">
			<p>Showing results for: </p>
			<?php
				$date = $_POST["date"];
				$times = $_POST["time"];
				$advisor = $_POST["advisor"];
				$studID = $_POST["studID"];
				$studLN = $_POST["studLN"];
				$filter = $_POST["filter"];
				$results = array();
				
				if($date == ''){ echo "Date: All"; }
				else{ 
					echo "Date: ",$date;
					$date = date('Y-m-d', strtotime($date));
				}
				echo "<br>";
				if(empty($times)){ echo "Time: All"; }
				else{
					$i = 0;
					echo "Time: ";
					foreach($times as $t){
						echo ++$i, ") ", date('g:i A', strtotime($t)), " ";
					}
				}
				echo "<br>";
				if($advisor == ''){ echo "Advisor: All appointments"; }
				elseif($advisor == 'I'){ echo "Advisor: All individual appointments"; }
				elseif($advisor == '0'){ echo "Advisor: All group appointments"; }
				else{
					// Get advisor info from database
					$advisor = new Advisor($COMMON, $advisor);
					echo "Advisor: ", $advisor->convertFullName();
				}
				echo "<br>";
				if($studID == '' && $studLN == ''){	echo "Student: All"; }
				else{
					$studLN = strtoupper($studLN);
					$studID = strtoupper($studID);
					$sql = "select `LastName`, `StudentID` from Proj2Students where `StudentID` = '$studID' or `LastName` = '$studLN'";
					$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
					$row = mysql_fetch_row($rs);
					$studLN = $row[0];
					$studID = $row[1];
					echo "Student: ", $studID, " ", $studLN;
				}
				echo "<br>";
				if($filter == ''){ echo "Filter: All appointments"; }
				elseif($filter == 0){ echo "Filter: Open appointments"; }
				elseif($filter == 1){ echo "Filter: Closed appointments"; }
				?>
				<br><br><label>
				<?php
				// Search for appointments
				$appts = Appointment::searchAppointments($COMMON, $advisor, null, $date, $times, false, null, $filter, $studID);
				// Format results for display
				foreach ($appts as $appointment) {
					// Get advisor's name
					$advName = getAdvisorName($appointment->getAdvisorID());
					$found = "Time: ". date('l, F d, Y g:i A', strtotime($appointment->getTime())). 
							"<br>Advisor: ". $advName. 
							"<br>Major: ". $appointment->convertMajor(). 
							"<br>Enrolled Students: ". $appointment->getEnrolledID(). 
							"<br>Number of enrolled student(s): ". $appointment->getEnrolledNum(). 
							"<br>Maximum number of students allowed: ". $appointment->getMax(). "<br><br>";
					array_push($results, $found);
				}
				/*if(empty($times)){
					if($advisor == 'I'){
						if($filter == 1){
							$sql = "select * from Proj2Appointments where `Time` like '%$date%' and 
								`AdvisorID` != 0 and 
								`EnrolledID` like '%$studID%' and 
								`EnrolledNum` >= 1 order by `Time` ASC";
						}
						else{
							$sql = "select * from Proj2Appointments where `Time` like '%$date%' and 
								`AdvisorID` != 0 and 
								`EnrolledID` like '%$studID%' and 
								`EnrolledNum` like '%$filter%' order by `Time` ASC";
						}
					}
					else{
						if($filter == 1){
							$sql = "select * from Proj2Appointments where `Time` like '%$date%' and 
								`AdvisorID` like '%$advisor%' and 
								`EnrolledID` like '%$studID%' and 
								`EnrolledNum` >= 1 order by `Time` ASC";
						}
						else{
							$sql = "select * from Proj2Appointments where `Time` like '%$date%' and 
								`AdvisorID` like '%$advisor%' and 
								`EnrolledID` like '%$studID%' and 
								`EnrolledNum` like '%$filter%' order by `Time` ASC";
						}
					}
					$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
					$row = mysql_fetch_row($rs);
					$rsA = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
					if($row){
						while($row = mysql_fetch_row($rsA)){
							if($row[2] == 0){
								$advName = "Group";
							}
							else{
								$sql2 = "select * from Proj2Advisors where `id` = '$row[2]'";
								$rs2 = $COMMON->executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
								$row2 = mysql_fetch_row($rs2);
								$advName = $row2[1] ." ". $row2[2];
							}
							$found = "Time: ". date('l, F d, Y g:i A', strtotime($row[1])). 
									"<br>Advisor: ". $advName. 
									"<br>Major: ". $row[3]. 
									"<br>Enrolled Students: ". $row[4]. 
									"<br>Number of enrolled student(s): ". $row[5]. 
									"<br>Maximum number of students allowed: ". $row[6]. "<br><br>";
							array_push($results, $found);
						}
					}
				}
				else{
					if($advisor == 'I'){
						foreach($times as $t){
							if($filter == 1){
								$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` like '%$t%' and 
									`AdvisorID` != 0 and 
									`EnrolledID` like '%$studID%' and
									`EnrolledNum` >= 1 order by `Time` ASC";
							}
							else{
								$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` like '%$t%' and 
									`AdvisorID` != 0 and 
									`EnrolledID` like '%$studID%' and
									`EnrolledNum` like '%$filter%' order by `Time` ASC";
							}
							$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
							$row = mysql_fetch_row($rs);
							$rsA = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
							if($row){
								while($row = mysql_fetch_row($rsA)){
									if($row[2] == 0){
										$advName = "Group";
									}
									else{
										$sql2 = "select * from Proj2Advisors where `id` = '$row[2]'";
										$rs2 = $COMMON->executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
										$row2 = mysql_fetch_row($rs2);
										$advName = $row2[1] ." ". $row2[2];
									}
									$found = "Time: ". date('l, F d, Y g:i A', strtotime($row[1])). 
											"<br>Advisor: ". $advName. 
											"<br>Major: ". $row[3]. 
											"<br>Enrolled Students: ". $row[4]. 
											"<br>Number of enrolled student(s): ". $row[5]. 
											"<br>Maximum number of students allowed: ". $row[6]. "<br><br>";
									array_push($results, $found);
								}
							}
						}
					}
					else{
						foreach($times as $t){
							if ($filter == 1){
								$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` like '%$t%' and 
									`AdvisorID` like '%$advisor%' and 
									`EnrolledID` like '%$studID%' and 
									`EnrolledNum` >= 1 order by `Time` ASC";
							}
							else{
								$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` like '%$t%' and 
									`AdvisorID` like '%$advisor%' and 
									`EnrolledID` like '%$studID%' and 
									`EnrolledNum` like '%$filter%' order by `Time` ASC";
							}
							$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
							$row = mysql_fetch_row($rs);
							$rsA = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
							if($row){
								while($row = mysql_fetch_row($rsA)){
									if($row[2] == 0){
										$advName = "Group";
									}
									else{
										$sql2 = "select * from Proj2Advisors where `id` = '$row[2]'";
										$rs2 = $COMMON->executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
										$row2 = mysql_fetch_row($rs2);
										$advName = $row2[1] ." ". $row2[2];
									}
									$found = "Time: ". date('l, F d, Y g:i A', strtotime($row[1])). 
											"<br>Advisor: ". $advName. 
											"<br>Major: ". $row[3]. 
											"<br>Enrolled Students: ". $row[4]. 
											"<br>Number of enrolled student(s): ". $row[5]. 
											"<br>Maximum number of students allowed: ". $row[6]. "<br><br>";
									array_push($results, $found);
								}
							}
						}
					}
				}*/
				if(empty($results)){
					echo "No results found.<br><br>";
				}
				else{
					foreach($results as $r){
					echo $r;
					}
				}
				?>
				</label>
		<form method="link" action="AdminUI.php" name="home">
			<input type="submit" name="next" class="button large go" value="Return to Home">
		</form>
	</div>
	</div>
	<div class="bottom">
		<p>If the Major category is followed by a blank, then it is open for all majors.</p>
	</div>
		<?php
		  include('../Footer.html');
	      include('./workOrder/workButton.php');
		?>
      </div>
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