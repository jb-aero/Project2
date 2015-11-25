<?php
session_start();
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Exit Message</title>
    <link rel='stylesheet' type='text/css' href='../css/standard.css'/>
	</head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
	    <div class="statusMessage">
	    <?php		
			if($_GET["stat"] == "co"){
				echo "You have completed your sign-up for an advising appointment.";
			}
			elseif($_SESSION["stat"] == "n"){
				echo "You did not sign up for an advising appointment.";
			}
			if($_SESSION["stat"] == "ca"){
				echo "You have cancelled your advising appointment.";
			}
			if($_SESSION["stat"] == "r"){
				echo "You have changed your advising appointment.";
			}
			if($_SESSION["stat"] == "k"){
				echo "No changes have been made to your advising appointment.";
			}
		?>
        </div>
		<form action="02StudHome.php" method="post" name="complete">
	    <div class="returnButton">
			<input type="submit" name="return" class="button large go" value="Return to Home">
	    </div>
		</div>
		</form>
  </body>
</html>