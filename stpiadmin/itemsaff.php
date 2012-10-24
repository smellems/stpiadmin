<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objItem = new clsitem();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbItemID = $objItem->stpi_getObjItemLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbItemID as $nbItemID)
		{
			if ($objItem->stpi_setNbID($nbItemID))
			{
				if ($objItem->stpi_setObjItemLgFromBdd())
				{
					print("<a href=\"./item.php?l=" . LG);
					print("&amp;nbItemID=" . $objBdd->stpi_trsBddToHTML($nbItemID) . "\">");
					if ($objItem->stpi_getObjTypeItem()->stpi_setNbID($objItem->stpi_getNbTypeItemID()))
					{
						if ($objItem->stpi_getObjTypeItem()->stpi_setObjTypeItemLgFromBdd())
						{
							print($objBdd->stpi_trsBddToHTML($objItem->stpi_getObjTypeItem()->stpi_getObjTypeItemLg()->stpi_getStrName()) . " - ");
						}
					}					
					print($objBdd->stpi_trsBddToHTML($objItem->stpi_getObjItemLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>