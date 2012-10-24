<?php
	require_once("./includes/includes.php");
	
	unset($_SESSION["stpiObjUser"]);

	Header("Location: ../index.php?l=" . LG);
?>