<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clstypecommande.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeCommande = new clstypecommande();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/commandes");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeCommandeID = $objTypeCommande->stpi_getObjTypeCommandeLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeCommandeID as $nbTypeCommandeID)
		{
			if ($objTypeCommande->stpi_setNbID($nbTypeCommandeID))
			{
				if ($objTypeCommande->stpi_setObjTypeCommandeLgFromBdd())
				{
					print("<a href=\"./commandetypecommande.php?l=" . LG);
					print("&amp;nbTypeCommandeID=" . $objBdd->stpi_trsBddToHTML($nbTypeCommandeID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeCommande->stpi_getObjTypeCommandeLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>