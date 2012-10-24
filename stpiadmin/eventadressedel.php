<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clsevent.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objEvent = new clsevent();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objEvent->stpi_getObjAdresse()->stpi_setNbID($_GET["nbAdresseID"]))
		{
			$ok = false;
		}
		if ($ok)
		{	
			if ($arrNbEventID = $objEvent->stpi_getObjAdresse()->stpi_selNbEventID())
			{
				$ok = false;
				$objEvent->stpi_getObjAdresse()->stpi_affNoDelete();
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
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objEvent->stpi_getObjAdresse()->stpi_delete($_GET["nbAdresseID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-");
					}
					else
					{
						$objBdd->stpi_rollback();
					}
				}
				else
				{
					$objBdd->stpi_rollback();
				}
			}
		}
	}
	else
	{
		$objEvent->stpi_getObjAdresse()->stpi_affDelete();
	}
?>