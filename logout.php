<?php
	require_once("./stpiadmin/includes/includes.php");
	
	if (isset($_SESSION["stpiObjUser"]))
	{
		unset($_SESSION["stpiObjUser"]);
	}
	Header("Location: login.php?l=" . LG);
?>