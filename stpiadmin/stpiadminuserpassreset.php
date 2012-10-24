<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/stpiadminuser/clsstpiadminuser.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSTPIAdminUser= new clsstpiadminuser();
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if (!$objSTPIAdminUser->stpi_setNbID($_GET["nbUserID"]))
		{
			exit;
		}
		
		if (!$objSTPIAdminUser->stpi_resetPassword())
		{
			exit;
		}
		
	}
	else
	{
		$objSTPIAdminUser->stpi_affPassReset();
	}
?>