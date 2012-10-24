<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/niveau/clsniveau.php");
	require_once("./includes/classes/security/clslock.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNiveau = new clsniveau();
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if (!$objNiveau = new clsniveau($_GET["nbNiveau"]))
	{
		exit;
	}
	
	$objNiveau->stpi_affEdit();
	
?>