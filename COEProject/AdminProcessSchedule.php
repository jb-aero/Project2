<?php
session_start();

if ($_POST["next"] == "Group"){
	header('Location: AdminScheduleGroup.php');
}
elseif ($_POST["next"] == "Individual"){
	header('Location: AdminScheduleInd.php');
}

?>