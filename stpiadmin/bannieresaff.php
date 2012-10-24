<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/banniere/clsbanniere.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBanniere = new clsbanniere();
		
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbBanniereID = $objBanniere->stpi_getObjBanniereLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbBanniereID as $nbBanniereID)
		{
			if ($objBanniere->stpi_setNbID($nbBanniereID))
			{
				if ($objBanniere->stpi_setObjBanniereLgFromBdd())
				{
					print("<a href=\"./banniere.php?l=" . LG);
					print("&amp;nbBanniereID=" . $objBdd->stpi_trsBddToHTML($nbBanniereID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objBanniere->stpi_getObjBanniereLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>