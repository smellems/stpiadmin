<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clscommandesousitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objCommandeSousItem = new clscommandesousitem();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if ($objBdd->stpi_startTransaction())
		{
			if (!$objCommandeSousItem->stpi_delete($_GET["nbCommandeID"], $_GET["nbSousItemID"]))
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbCommandeID=" . $_GET["nbCommandeID"]);
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
		$objCommandeSousItem->stpi_affDelete();
	}
?>