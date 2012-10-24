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
		if (!$objSousItem->stpi_getObjAttribut()->stpi_setNbID($_GET["nbAttributID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($arrNbSousItemID = $objSousItem->stpi_getObjAttribut()->stpi_selNbSousItemID())
			{
				$ok = false;
				$objSousItem->stpi_getObjAttribut()->stpi_affNoDelete();
				foreach($arrNbSousItemID as $nbSousItemID)
				{
					if ($objSousItem->stpi_setNbID($nbSousItemID))
					{
						if ($arrNbAttributID = $objSousItem->stpi_selNbAttributID())
						{
							print("<a href=\"./itemsousitem.php?l=" . LG . "&amp;nbSousItemID=" . $nbSousItemID . "\">(" . $objSousItem->stpi_getStrItemCode() . ")");
							foreach($arrNbAttributID as $nbAttributID)
							{
								if ($objSousItem->stpi_getObjAttribut()->stpi_setNbID($nbAttributID))
								{
									if ($objSousItem->stpi_getObjAttribut()->stpi_setObjAttributLgFromBdd())
									{
										print(" - " . $objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrName());
									}
								}
							}
							print("</a><br/>");
						}
					}
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if ($ok)
				{
					if (!$objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_deleteAttributId($_GET["nbAttributID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objSousItem->stpi_getObjAttribut()->stpi_delete($_GET["nbAttributID"]))
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
		$objSousItem->stpi_getObjAttribut()->stpi_affDelete();
	}
?>