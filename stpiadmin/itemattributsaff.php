<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsattribut.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objAttribut = new clsattribut();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbAttributID = $objAttribut->stpi_getObjAttributLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbAttributID as $nbAttributID)
		{
			if ($objAttribut->stpi_setNbID($nbAttributID))
			{
				if ($objAttribut->stpi_setObjAttributLgFromBdd())
				{
					print("<a href=\"./itemattribut.php?l=" . LG);
					print("&amp;nbAttributID=" . $objBdd->stpi_trsBddToHTML($nbAttributID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objAttribut->stpi_getObjAttributLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>