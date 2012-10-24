<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clssousitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSousItem = new clssousitem();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if ($objBdd->stpi_startTransaction())
		{
			$ok = true;
			
			if (!$objSousItem->stpi_setNbID($_GET["nbSousItemID"]))
			{
				$ok = false;
			}
			$nbItemID = $objSousItem->stpi_getNbItemID();
			
			if ($ok)
			{
				if (!$objSousItem->stpi_getObjPrix()->stpi_deleteSousItemID($_GET["nbSousItemID"]))
				{
					$ok = false;
				}
			}
			if ($ok)
			{
				if (!$objSousItem->stpi_delete($_GET["nbSousItemID"]))
				{
					$ok = false;
				}
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbItemID=" . $nbItemID);
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
		$objSousItem->stpi_affDelete();
	}
?>