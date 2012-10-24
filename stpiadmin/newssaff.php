<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/news/clsnews.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNews = new clsnews();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/newss");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbNewsID = $objNews->stpi_getObjNewsLg()->stpi_selSearchTitre($_GET["strTitre"]))
	{
		foreach ($arrNbNewsID as $nbNewsID)
		{
			if ($objNews->stpi_setNbID($nbNewsID))
			{
				if ($objNews->stpi_setObjNewsLgFromBdd())
				{
					print("<a href=\"./news.php?l=" . LG);
					print("&amp;nbNewsID=" . $objBdd->stpi_trsBddToHTML($nbNewsID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objNews->stpi_getDtEntryDate()) . "</a> : ");
					print($objBdd->stpi_trsBddToHTML($objNews->stpi_getObjNewsLg()->stpi_getStrTitre()) . "<br/>\n");
				}
			}
		}
	}	
?>