<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/lien/clslien.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objLien = new clslien();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/liens");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbLienID = $objLien->stpi_getObjLienLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbLienID as $nbLienID)
		{
			if ($objLien->stpi_setNbID($nbLienID))
			{
				if ($objLien->stpi_setObjLienLgFromBdd())
				{
					print("<a href=\"./lien.php?l=" . LG);
					print("&amp;nbLienID=" . $objBdd->stpi_trsBddToHTML($nbLienID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objLien->stpi_getObjLienLg()->stpi_getStrName()) . "</a> - " . $objBdd->stpi_trsBddToHTML($objLien->stpi_getObjLienLg()->stpi_getStrLien()) . "<br/>\n");
				}
			}
		}
	}
?>