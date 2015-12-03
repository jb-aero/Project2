<?php

/* Had to make sure sessions was enabled. Some help here:

https://wiki.umbc.edu/pages/viewpage.action?pageId=46563550

cd /afs/umbc.edu/public/web/sites/coeadvising/prod/php/session/

/usr/bin/fs sa /afs/umbc.edu/public/web/sites/coeadvising/prod/php/session/ web.coeadvising all


then edit .htaccess file here in the same directory

*/


session_start();

include('../CommonMethods.php');
include('../Advisor.php');
$debug = false;
$Common = new Common($debug);

$admin = new Advisor($Common, strtoupper($_POST["UserN"]), md5($_POST["PassW"]));

if($admin->exists()){
	// Save away user ID
	$_SESSION["UserID"] = strtoupper($_POST["UserN"]);
	header('Location: AdminUI.php');
}
else{
	$_SESSION["UserID"] = -1;
	header('Location: AdminSignIn.php'); 
}

?>