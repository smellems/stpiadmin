<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clstypeitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeItem = new clstypeitem();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeItemID = $objTypeItem->stpi_getObjTypeItemLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeItemID as $nbTypeItemID)
		{
			if ($objTypeItem->stpi_setNbID($nbTypeItemID))
			{
				if ($objTypeItem->stpi_setObjTypeItemLgFromBdd())
				{
					print("<a href=\"./itemtypeitem.php?l=" . LG);
					print("&amp;nbTypeItemID=" . $objBdd->stpi_trsBddToHTML($nbTypeItemID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>