<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/banniere/clstypebanniere.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeBanniere = new clstypebanniere();
		
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeBanniereID = $objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeBanniereID as $nbTypeBanniereID)
		{
			if ($objTypeBanniere->stpi_setNbID($nbTypeBanniereID))
			{
				if ($objTypeBanniere->stpi_setObjTypeBanniereLgFromBdd())
				{
					print("<a href=\"./bannieretypebanniere.php?l=" . LG);
					print("&amp;nbTypeBanniereID=" . $objBdd->stpi_trsBddToHTML($nbTypeBanniereID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>