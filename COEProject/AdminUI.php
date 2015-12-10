<?php 
session_start();
$debug = false;

if($debug) { echo("Session variables-> ".var_dump($_SESSION)); }

include('../CommonMethods.php');
include('../Advisor.php');
$COMMON = new Common($debug);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Admin Home</title>
	<link rel='stylesheet' type='text/css' href='../css/standard.css'/>
  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
	<h2> Hello 
	<?php

	if(!isset($_SESSION["UserID"])) // someone landed this page by accident
	{
		return;
	}		

		$UserID = $_SESSION["UserID"];
		// Get advisor info from database
		$advisor = new Advisor($COMMON, $UserID);

		echo $advisor->getFirstName();
	?>
	</h2>
	
	<form action="AdminProcessUI.php" method="post" name="UI">
  
		<input type="submit" name="next" class="button large selection" value="Schedule appointments"><br>
		<input type="submit" name="next" class="button large selection" value="Print schedule for a day"><br>
		<input type="submit" name="next" class="button large selection" value="Edit appointments"><br>
		<input type="submit" name="next" class="button large selection" value="Search for an appointment"><br>
		<input type="submit" name="next" class="button large selection" value="Create new Admin Account"><br>
	
	</form>
	<br>
        
		</div>
		<?php
		  include('../Footer.html')
	    ?>
      </div>
	

	<?php include('./workOrder/workButton.php'); ?>
</div>
</body>
  
</html>
