<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clstypeevent.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeEvent = new clstypeevent();
		
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/events");
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($arrNbTypeEventID = $objTypeEvent->stpi_getObjTypeEventLg()->stpi_selSearchName($_GET["strName"]))
	{
		foreach ($arrNbTypeEventID as $nbTypeEventID)
		{
			if ($objTypeEvent->stpi_setNbID($nbTypeEventID))
			{
				if ($objTypeEvent->stpi_setObjTypeEventLgFromBdd())
				{
					print("<a href=\"./eventtypeevent.php?l=" . LG);
					print("&amp;nbTypeEventID=" . $objBdd->stpi_trsBddToHTML($nbTypeEventID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objTypeEvent->stpi_getObjTypeEventLg()->stpi_getStrName()) . "</a><br/>\n");
				}
			}
		}
	}
?>