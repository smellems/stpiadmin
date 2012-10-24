<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/client/clsclient.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objClient= new clsclient();
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if (!$objClient->stpi_setNbID($_GET["nbClientID"]))
		{
			exit;
		}
		
		if (!$objClient->stpi_resetPassword())
		{
			exit;
		}
	}
	else
	{
		$objClient->stpi_affPassReset();
	}
?>