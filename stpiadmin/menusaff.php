<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/menu/clsmenupublic.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMenuPublic = new clsmenupublic();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/liens");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();

	if ($arrNbMenuID = $objMenuPublic->stpi_getObjMenuLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbMenuID as $nbMenuID)
		{
			if ($objMenuPublic->stpi_setNbID($nbMenuID))
			{
				if ($objMenuPublic->stpi_setObjMenuLgFromBdd())
				{
					print("<a href=\"./menu.php?l=" . LG);
					print("&amp;nbMenuID=" . $objBdd->stpi_trsBddToHTML($nbMenuID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objMenuPublic->stpi_getObjMenuLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>
