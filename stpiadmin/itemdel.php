<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objItem = new clsitem();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objItem->stpi_setNbID($_GET["nbItemID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				$nbImageID = $objItem->stpi_getNbImageID();
				if ($nbImageID != 0 AND !$objItem->stpi_getObjImg()->stpi_delete($nbImageID))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objItem->stpi_getObjItemLg()->stpi_deleteItemId($_GET["nbItemID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objItem->stpi_delete($_GET["nbItemID"]))
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
	}
	else
	{
		$objItem->stpi_affDelete();
	}
?>