<?php
session_start();
//ini_set('display_errors','1');
//ini_set('display_startup_errors','1');
//error_reporting (E_ALL);

$debug = false;
include('../CommonMethods.php');
include('../Appointment.php');
include('../Student.php');
include('../Advisor.php');
$COMMON = new Common($debug);
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Search for Appointment</title>
	<link rel='stylesheet' type='text/css' href='../css/standard.css'/>
  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
		<h1>Search Results</h1>
		<h3>Showing open appointments only</h3>
	    <div class="field">
			<p>Showing results for: </p>
			<?php
				// Get student data from database
				$student = new Student($COMMON, $_SESSION["studID"]);
				$major = $student->getMajor();
				$date = $_POST["date"];
				$times = $_POST["time"];
				$advisor = $_POST["advisor"];
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
					// Display individual advisor's name
					echo "Advisor: ", getAdvisorName($advisor);
				}
				?>
				<br><br><label>
				<?php
				// Get search resulsts for student
				$appts = Appointment::searchAppointments($COMMON, $advisor, $major, $date, $times);
				// Add results to array
				foreach ($appts as $appointment) {
					$found = "<tr><td>". date('l, F d, Y g:i A', strtotime($appointment->getTime()))."</td>".
									"<td>". getAdvisorName($appointment->getAdvisorID()) ."</td>". 
									"<td>". $appointment->convertMajor() . "</td></tr>";
					array_push($results, $found);
				}
				// Display results on screen
				if(empty($results)){
					echo "No results found.<br><br>";
				}
				else{
					echo("<table border='1'><th colspan='3'>Appointments Available</th>\n");
					echo("<tr><td width='60px'>Time:</td><td>Advisor</td><td>Major</td></tr>\n");

					foreach($results as $r){ echo($r."\n"); }

					echo("</table>");
				}
			?>
			</label>
        </div>
		<form action="02StudHome.php" method="link">
	    <div class="nextButton">
			<input type="submit" name="done" class="button large go" value="Done">
	    </div>
	    </form>
	    	<form action="09StudSearchApp.php" method="link">
	    	<div class="nextButton">
	    		<input type="submit" name="return" class="button large go" value="New Search">
	    	</div>
		</form>
		</div>
		<div class="bottom">
		<p>If the Major category is followed by a blank, then it is open for all majors.</p>
		</div>
		<?php
		  include('../Footer.html');
	    ?>
      </div>
    </div>
  </body>
</html>

<?php


// More code reduction by Lupoli - 9/1/15
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
