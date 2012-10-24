<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clstypeprix.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypePrix = new clstypeprix();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypePrixID = $objTypePrix->stpi_getObjTypePrixLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypePrixID as $nbTypePrixID)
		{
			if ($objTypePrix->stpi_setNbID($nbTypePrixID))
			{
				if ($objTypePrix->stpi_setObjTypePrixLgFromBdd())
				{
					print("<a href=\"./itemtypeprix.php?l=" . LG);
					print("&amp;nbTypePrixID=" . $objBdd->stpi_trsBddToHTML($nbTypePrixID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypePrix->stpi_getObjTypePrixLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>