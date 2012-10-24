<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/lien/clstypelien.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeLien = new clstypelien();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/liens");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeLienID = $objTypeLien->stpi_getObjTypeLienLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeLienID as $nbTypeLienID)
		{
			if ($objTypeLien->stpi_setNbID($nbTypeLienID))
			{
				if ($objTypeLien->stpi_setObjTypeLienLgFromBdd())
				{
					print("<a href=\"./lientypelien.php?l=" . LG);
					print("&amp;nbTypeLienID=" . $objBdd->stpi_trsBddToHTML($nbTypeLienID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeLien->stpi_getObjTypeLienLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>