<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/page/clspagepublic.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objPage = new clspagepublic();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/pages");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbPageID = $objPage->stpi_getObjPageLg()->stpi_selSearchTitre($_GET["strTitre"]))
	{
		foreach ($arrNbPageID as $nbPageID)
		{
			if ($objPage->stpi_setNbID($nbPageID))
			{
				if ($objPage->stpi_setObjPageLgFromBdd())
				{
					print("<a href=\"./page.php?l=" . LG);
					print("&amp;nbPageID=" . $objBdd->stpi_trsBddToHTML($nbPageID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objPage->stpi_getObjPageLg()->stpi_getStrTitre()) . "</a><br/>\n");
				}
			}
		}
	}	
?>
