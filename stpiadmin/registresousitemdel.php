<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/registre/clsregistresousitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objRegistreSousItem = new clsregistresousitem();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if ($objBdd->stpi_startTransaction())
		{
			if (!$objRegistreSousItem->stpi_delete($_GET["nbRegistreID"], $_GET["nbSousItemID"]))
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbRegistreID=" . $_GET["nbRegistreID"]);
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
		$objRegistreSousItem->stpi_affDelete();
	}
?>
