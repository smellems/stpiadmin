<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clsmethodpaye.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMethodPaye = new clsmethodpaye();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/commandes");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbMethodPayeID = $objMethodPaye->stpi_getObjMethodPayeLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbMethodPayeID as $nbMethodPayeID)
		{
			if ($objMethodPaye->stpi_setNbID($nbMethodPayeID))
			{
				if ($objMethodPaye->stpi_setObjMethodPayeLgFromBdd())
				{
					print("<a href=\"./commandemethodpaye.php?l=" . LG);
					print("&amp;nbMethodPayeID=" . $objBdd->stpi_trsBddToHTML($nbMethodPayeID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objMethodPaye->stpi_getObjMethodPayeLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>