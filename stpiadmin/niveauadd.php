<?php
	
	require_once("./includes/includes.php");
	require_once("./includes/classes/niveau/clsniveau.php");
	require_once("./includes/classes/security/clslock.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	$objNiveau = new clsniveau();
	
	if (!$objNiveau->stpi_setStrName($_GET["strName"]))
	{
		exit;
	}
	
	if ($objNiveau->stpi_insert())
	{
		print("redirect-nbNiveauID=" . $objNiveau->stpi_getNbID());
	}

?>