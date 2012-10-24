<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/client/clsclient.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	$objClient = new clsclient();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objClient->stpi_setNbID($_GET["nbClientID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objClient->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{
			if ($objClient->stpi_delete($_GET["nbClientID"]))
			{
				print("redirect");
			}
		}
	}
	else
	{
		$objClient->stpi_affDelete();
	}
?>