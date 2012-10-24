<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/motd/clsmotd.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if ($objBdd->stpi_startTransaction())
		{
			$ok = true;
			if (!$objMotd->stpi_getObjMotdLg()->stpi_deleteMotdId($_GET["nbMotdID"]))
			{
				$ok = false;
			}
			if ($ok)
			{
				if (!$objMotd->stpi_delete($_GET["nbMotdID"]))
				{
					$ok = false;
				}
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
		$objMotd->stpi_affDelete();
	}
?>