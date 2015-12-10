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
				echo("<h2>You have completed your sign-up for an advising appointment.</h2>");
			}
			elseif($_GET["stat"] == "n"){
				echo ("<h2>You did not sign up for an advising appointment.</h2>");
			}
			if($_GET["stat"] == "ca"){
				echo ("<h2>You have cancelled your advising appointment.</h2>");
			}
			if($_GET["stat"] == "r"){
				echo ("<h2>You have changed your advising appointment.</h2>");
			}
			if($_GET["stat"] == "k"){
				echo ("<h2>No changes have been made to your advising appointment.</h2>");
			}
		?>
        </div>
		<form action="02StudHome.php" method="post" name="complete">
	    <div class="returnButton">
			<input type="submit" name="return" class="button large go" value="Return to Home">
	    </div>
		</form>
		</div>
		<?php
		  include('../Footer.html');
	    ?>
      </div>
    </div>
  </body>
</html>
