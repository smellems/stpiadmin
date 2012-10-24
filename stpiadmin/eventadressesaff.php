<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clseventadresse.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objAdresse = new clseventadresse();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/events");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbAdresseID = $objAdresse->stpi_selSearchAdresse($_GET["strAdresse"]))
	{
		foreach ($arrNbAdresseID as $nbAdresseID)
		{
			if ($objAdresse->stpi_setNbID($nbAdresseID))
			{
				print("<a href=\"./eventadresse.php?l=" . LG);
				print("&amp;nbAdresseID=" . $objBdd->stpi_trsBddToHTML($nbAdresseID) . "\">");
				print($objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrEndroit()) . " - " . $objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrAdresse()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrProvinceID()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrCountryID()) . "</a><br/>\n");
			}
		}
	}
?>