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
		if (!$objItem->stpi_getObjTypeItem()->stpi_setNbID($_GET["nbTypeItemID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($arrNbItemID = $objItem->stpi_getObjTypeItem()->stpi_selNbItemID())
			{
				$ok = false;
				$objItem->stpi_getObjTypeItem()->stpi_affNoDelete();
				foreach ($arrNbItemID as $nbItemID)
				{
					if ($objItem->stpi_setNbID($nbItemID))
					{
						if ($objItem->stpi_setObjItemLgFromBdd())
						{
							print("<a href=\"./item.php?l=" . LG);
							print("&amp;nbItemID=" . $objBdd->stpi_trsBddToHTML($nbItemID) . "\">");
							print($objBdd->stpi_trsBddToHTML($objItem->stpi_getObjItemLg()->stpi_getStrName()) . "</a><br/>\n");
						}
					}
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				$nbImageID = $objItem->stpi_getObjTypeItem()->stpi_getNbImageID();
				if ($nbImageID != 0 AND !$objItem->stpi_getObjTypeItem()->stpi_getObjImg()->stpi_delete($nbImageID))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objItem->stpi_getObjTypeItem()->stpi_getObjTypeItemLg()->stpi_deleteTypeItemId($_GET["nbTypeItemID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objItem->stpi_getObjTypeItem()->stpi_delete($_GET["nbTypeItemID"]))
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
		$objItem->stpi_getObjTypeItem()->stpi_affDelete();
	}
?>