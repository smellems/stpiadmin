<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clseventdateheure.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objDateHeure = new clseventdateheure();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if ($objBdd->stpi_startTransaction())
		{
			if (!$objDateHeure->stpi_delete($_GET["nbDateHeureID"]))
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-");
				}
				else
				{
					$objBdd->stpi_rollback();
				}
			}
			else
			{
				$objBdd->stpi_rollback();
			}
		}
	}
	else
	{
		$objDateHeure->stpi_affDelete();
	}
?>