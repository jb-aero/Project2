<?php
  session_start();
  include('../CommonMethods.php');
  include('../Advisor.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Create New Admin</title>
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
        <?php
	$_SESSION["PassCon"] = false;
	$first = $_POST["firstN"];
	$last = $_POST["lastN"];
	$user = strtoupper($_POST["UserN"]);
	$pass = md5($_POST["PassW"]);
	$office = $_POST["Office"];
	$meeting = $_POST["Meeting"];
	echo($first);
	$debug = false;
	$COMMON = new Common($debug);
	$userN = $_SESSION["UserID"];
	$adv = new Advisor($COMMON,$userN);
	if ($_POST["PassW"] != $_POST["ConfP"]) {
		$_SESSION["PassCon"] = true;
		header('Location: AdminCreateNewAdv.php');
	}
	elseif ($_POST["PassW"] == $_POST["ConfP"]) {
			
      	if ($adv->createAdvisor($COMMON, $first, $last, $user, $pass, $office, $meeting)) {
		echo("<h2>New Advisor has been created:</h2>");
        	echo ("<h3>$first $last<h3>");
      }
     else {
       echo("<h3>Advisor $first $last already exists</h3>");
    	}
    }
		?>
		<form method="link" action="AdminUI.php">
			<input type="submit" name="next" class="button large go" value="Return to Home">
		</form>
	</div>
	</div>
	</div>
	</form>
  </body>
  
</html>
