<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clscatitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objCatItem = new clscatitem();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbCatItemID = $objCatItem->stpi_getObjCatItemLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbCatItemID as $nbCatItemID)
		{
			if ($objCatItem->stpi_setNbID($nbCatItemID))
			{
				if ($objCatItem->stpi_setObjCatItemLgFromBdd())
				{
					print("<a href=\"./itemcatitem.php?l=" . LG);
					print("&amp;nbCatItemID=" . $objBdd->stpi_trsBddToHTML($nbCatItemID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objCatItem->stpi_getObjCatItemLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>