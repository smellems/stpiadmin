<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clstypeadresse.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeAdresse = new clstypeadresse();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/commandes");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeAdresseID = $objTypeAdresse->stpi_getObjTypeAdresseLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeAdresseID as $nbTypeAdresseID)
		{
			if ($objTypeAdresse->stpi_setNbID($nbTypeAdresseID))
			{
				if ($objTypeAdresse->stpi_setObjTypeAdresseLgFromBdd())
				{
					print("<a href=\"./commandetypeadresse.php?l=" . LG);
					print("&amp;nbTypeAdresseID=" . $objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeAdresse->stpi_getObjTypeAdresseLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>