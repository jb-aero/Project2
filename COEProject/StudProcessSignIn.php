<?php
session_start();
include("Conversion.php");

$_SESSION["firstN"] = strtoupper($_POST["firstN"]);
$_SESSION["lastN"] = strtoupper($_POST["lastN"]);
$_SESSION["studID"] = strtoupper($_POST["studID"]);
$_SESSION["email"] = $_POST["email"];
$_SESSION["major"] = NameToAbb($_POST["major"]);

header('Location: 02StudHome.php');
?>