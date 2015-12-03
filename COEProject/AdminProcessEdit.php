<?php
session_start();

if ($_POST["next"] == "Group"){
	header('Location: AdminEditGroup.php');
}
elseif ($_POST["next"] == "Individual"){
	header('Location: AdminEditInd.php');
}

?>