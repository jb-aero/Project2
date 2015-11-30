<?php 
session_start();
$debug = false;
include('../CommonMethods.php');
include('../Student.php');
include('../Appointment.php');
include('../Advisor.php');
$COMMON = new Common($debug);

// Get student information from database
$student = new Student($COMMON, $_SESSION["studID"]);
$major = $student->getMajor();
?>

<html>
<head>
<title>Student Find Next Appointment</title>
<link rel='stylesheet' type='text/css' href='../css/standard.css'/>
 </head>
 <body>
 	<div id="login">
 		<h1>Next Available Appointment:</h1><br>
<?php
//gets rows in Proj2Appoitments after the current time and date with an enrollednumber less than the max value it can be and containing the student's major
$sql = "select * from `Proj2Appointments` where `Time` > '".date('Y-m-d H:i:s')."'and `EnrolledNum` < `Max` and`Major` like '%$major%'order by `Time` ASC Limit 2";

//gets the first row from the query
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
$row = mysql_fetch_row($rs);
//if the row isn't empty and an appointment was found
if ($row)
  {
    //if the advisor id = 0, it is a group appointment
    if($row[2] == 0)
      {
      $advName = "Group";
      }
    //else get the advisor's name using function getAdvisorName
    else
      {
	$advName = getAdvisorName($row); 
      }
    //places the appointment information into a table
    $found = "<tr><td>". date('l, F d, Y g:i A', strtotime($row[1]))."</td>".
      "<td>". $advName."</td>". 
      "<td>". fullMajor($row[3]). "</td></tr>";
  }
//if no appointemnt was found
if(empty($row))
  {
  echo "No results found.<br><br>";
  }
//else print out the found important information
else
  {
  echo("<table border='1' align='center'><th colspan='3'>Appointments Available</th>\n");
  echo("<tr><td width='50%'>Time:</td><td>Advisor</td><td>Major</td></tr>\n");
  echo($found."\n");
  echo("</table>");
  }
?>
<form action="02StudHome.php" method="link">
      <div class="nextButton">
  <input type="submit" name="done" class="button large go" value="Done">
  </div>
  </div>
  </form>
 </body>
</html>
<?php
function getAdvisorName($row)
{
  global $debug; global $COMMON;
  $sql2 = "select * from Proj2Advisors where `id` = '$row[2]'";
  $rs2 = $COMMON->executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
  $row2 = mysql_fetch_row($rs2);
  return $row2[1] ." ". $row2[2];
}
function fullMajor($majors)
{
	$majorArr = explode(" ", $majors);
	$r ="";
	foreach ($majorArr as $m)
	{
		$r .= AbbToName($m) . " ";
	}
	return $r;
}
?>
