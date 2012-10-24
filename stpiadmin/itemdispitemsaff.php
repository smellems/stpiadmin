<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsdispitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objDispItem = new clsdispitem();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbDispItemID = $objDispItem->stpi_getObjDispItemLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbDispItemID as $nbDispItemID)
		{
			if ($objDispItem->stpi_setNbID($nbDispItemID))
			{
				if ($objDispItem->stpi_setObjDispItemLgFromBdd())
				{
					print("<a href=\"./itemdispitem.php?l=" . LG);
					print("&amp;nbDispItemID=" . $objBdd->stpi_trsBddToHTML($nbDispItemID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objDispItem->stpi_getObjDispItemLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>