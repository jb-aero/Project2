<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Create New Admin</title>
   <link rel='stylesheet' type='text/css' href='../css/standard.css'/>
  </head>
  <body>
  	<?php 
  	$_SESSION["PassCon"]=false;
  	if ($_POST["PassW"] != $_POST["ConfP"])
  	{
		$_SESSION["PassCon"] = true;
		header('Location: AdminCreateNewAdv.php');
	}
	?>
    <div id="login">
      <div id="form">
        <div class="top">
		<?php
			$first = $_POST["firstN"];
			$last = $_POST["lastN"];
			$office = $_POST["Office"];
			$meeting = $_POST["Meeting"];
			$user = $_POST["UserN"];
			$pass = md5($_POST["PassW"]);

			include('../CommonMethods.php');
			include('../Advisor.php');
			$debug = false;
			$Common = new Common($debug);

	$userN = $_SESSION["User_ID"];
	$adv = new Advisor($Common,$userN);
      if ($adv->createAdvisor($Common,$first,$last,$user,$pass,$office,$meeting))
      {
        echo("<h3>Advisor $first $last created successfully</h3>");
      }
      else{
      	echo("<h3>Advisor $first $last already exists</h3>");
      }
		?>
		<form method="link" action="AdminUI.php">
			<input type="submit" name="next" class="button large go" value="Return to Home">
		</form>
		</div>
		<?php
		  include('../Footer.html');
	    ?>
      </div>
    </div>
  </body>
  
</html>
