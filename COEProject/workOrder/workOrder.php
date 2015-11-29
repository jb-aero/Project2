<?php
session_start();
$debug = false;

ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting (E_ALL);

if(isset($_POST['description'])) // then stage 2, enter data into DB table
{
	if($debug) { echo("stage2"); }
	stage2($_POST);
}
else // they have not entered anything
{
	if($debug) { echo("stage1"); }
	stage1($_GET);
}


// **********************************************************************


function stage1($_GET)
{
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Work order</title>
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
	<h2>Work order form for:</h2> 
	<center><font size = 3px color="red"><?php echo($_GET['url']); ?></center></font>
	<br>
	<br>
	<br>
    <div id="form">
    <div class="top">

	<form action="workOrder.php" method='post'>
	<b><font size = 2px>Description:</font></b> <br><br><textarea name='description' id='description' rows="6" cols="100"></textarea><br><br>
	<b><font size = 2px>Priority:</b></font> <br>
			<input type="radio" name="priority" value="1" checked>1 (Highest)<br>
			<input type="radio" name="priority" value="2">2<br>
			<input type="radio" name="priority" value="3">3<br>
			<input type="radio" name="priority" value="4">4<br>
			<input type="radio" name="priority" value="5">5 (Lowest)<br>
	
	<input type="hidden" name="url" value='<?php echo($_GET["url"]); ?>'>

	<input type="submit" name="next" class="button large go" value="Submit">
		<div>
	</form>
		<form method="link" action="">
		<input type="submit" name="home" class="button large" value="Cancel" onClick="window.close()">
		</form>
		</div>


     </div>
     </div>
     </div>
  </body>
  
</html>

<?php
}


function stage2($_POST)
{
	global $debug;

	include('../CommonMethods.php');
	$COMMON = new Common($debug);

      $sql = "insert into `work_orders` (`id`, `url`, `description`, `priority`, `author`, `time_entered`) values (null, '".$_POST['url']."', '".$_POST['description']."', '".$_POST['priority']."', '".$_SESSION['UserN']."', CURRENT_TIMESTAMP)";
      $rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Work order</title>
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
	<h2>Your work order has been submitted successfully</h2>
	<form action="">
	<input type="submit" name="home" class="button large" value="Close" onClick="window.close()">
	</form>
	</div>
     </div>
  </body>
  
</html>



<?php

	        $message =  "From: ".$_SESSION['userN']."\n\r Priority: ".$_POST['priority']."\n\r ".$_POST['description'];
                mail("slupoli@umbc.edu", "Work Order for COE Advising", $message);

}
?>
