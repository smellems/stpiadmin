<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clsevent.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objEvent = new clsevent();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/events");
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if ($arrNbEventID = $objEvent->stpi_getObjEventLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbEventID as $nbEventID)
		{
			if ($objEvent->stpi_setNbID($nbEventID))
			{
				if ($objEvent->stpi_setObjEventLgFromBdd())
				{
					print("<a href=\"./event.php?l=" . LG);
					print("&amp;nbEventID=" . $objBdd->stpi_trsBddToHTML($nbEventID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objEvent->stpi_getObjEventLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>