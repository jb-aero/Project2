<?php
session_start();


if ($_POST["next"] == "Delete Appointment"){
	header('Location: AdminConfirmEditGroup.php?app='.$_POST["GroupApp"]);
}
elseif ($_POST["next"] == "Edit Appointment"){
	header('Location: AdminProceedEditGroup.php?app='.$_POST["GroupApp"]);
}

?>