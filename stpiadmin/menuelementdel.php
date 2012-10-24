<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/menu/clsmenuelement.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMenuElement = new clsmenuelement();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objMenuElement->stpi_setNbID($_GET["nbMenuElementID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($arrNbMenuElementID = $objMenuElement->stpi_selNbParentID())
			{
				$objMenuElement->stpi_affNoDelete();
				foreach($arrNbMenuElementID as $nbMenuElementID)
				{
					if ($objMenuElement->stpi_setNbID($nbMenuElementID))
					{
						if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbMenuElementID($nbMenuElementID))
						{
							if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbID($objMenuElement->stpi_getObjMenuElementLg()->stpi_selNbMenuElementIDLG()))
							{
								print("<a href=\"./menuelement.php?l=" . LG);
								print("&amp;nbMenuElementID=" . $objBdd->stpi_trsBddToHTML($nbMenuElementID) . "\">");
								print($objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</a><br />\n");
							}
						}
					}
				}
				$ok = false;
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if ($ok)
				{
					if (!$objMenuElement->stpi_getObjMenuElementLg()->stpi_deleteMenuElementId($_GET["nbMenuElementID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objMenuElement->stpi_delete($_GET["nbMenuElementID"]))
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
		$objMenuElement->stpi_affDelete();
	}
?>
