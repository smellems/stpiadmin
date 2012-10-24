<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/news/clstypenews.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeNews = new clstypenews();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/newss");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeNewsID = $objTypeNews->stpi_getObjTypeNewsLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeNewsID as $nbTypeNewsID)
		{
			if ($objTypeNews->stpi_setNbID($nbTypeNewsID))
			{
				if ($objTypeNews->stpi_setObjTypeNewsLgFromBdd())
				{
					// print($objBdd->stpi_trsBddToHTML($objTypeNews->stpi_getObjTypeNewsLg()->stpi_getDtEntryDate()) . " - ");
					print("<a href=\"./newstypenews.php?l=" . LG);
					print("&amp;nbTypeNewsID=" . $objBdd->stpi_trsBddToHTML($nbTypeNewsID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeNews->stpi_getObjTypeNewsLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>
