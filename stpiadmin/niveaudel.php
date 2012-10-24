<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/niveau/clsniveau.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNiveau= new clsniveau();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if (!$objNiveau->stpi_setNbID($_GET["nbNiveauID"]))
		{
			exit;
		}
		
		if ($objNiveau->stpi_delete())
		{
			print("redirect");
		}
	}
	else
	{
		$objNiveau->stpi_affDelete();
	}
?>