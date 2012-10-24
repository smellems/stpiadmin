<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clsstatutcommande.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objStatutCommande = new clsstatutcommande();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/commandes");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbStatutCommandeID = $objStatutCommande->stpi_getObjStatutCommandeLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbStatutCommandeID as $nbStatutCommandeID)
		{
			if ($objStatutCommande->stpi_setNbID($nbStatutCommandeID))
			{
				if ($objStatutCommande->stpi_setObjStatutCommandeLgFromBdd())
				{
					print("<a href=\"./commandestatutcommande.php?l=" . LG);
					print("&amp;nbStatutCommandeID=" . $objBdd->stpi_trsBddToHTML($nbStatutCommandeID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objStatutCommande->stpi_getObjStatutCommandeLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>