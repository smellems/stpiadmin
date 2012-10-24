<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clstypeattribut.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeAttribut = new clstypeattribut();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeAttributID = $objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeAttributID as $nbTypeAttributID)
		{
			if ($objTypeAttribut->stpi_setNbID($nbTypeAttributID))
			{
				if ($objTypeAttribut->stpi_setObjTypeAttributLgFromBdd())
				{
					print("<a href=\"./itemtypeattribut.php?l=" . LG);
					print("&amp;nbTypeAttributID=" . $objBdd->stpi_trsBddToHTML($nbTypeAttributID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>