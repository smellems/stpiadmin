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
		$ok = true;
		if (!$objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_setNbID($_GET["nbTypePrixID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objSousItem->stpi_getObjPrix()->stpi_deleteTypePrixID($_GET["nbTypePrixID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_deleteTypePrixId($_GET["nbTypePrixID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_delete($_GET["nbTypePrixID"]))
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
		$objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_affDelete();
	}
?>