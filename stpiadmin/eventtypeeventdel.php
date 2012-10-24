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
		if (!$objEvent->stpi_getObjTypeEvent()->stpi_setNbID($_GET["nbTypeEventID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objEvent->stpi_getObjTypeEvent()->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{	
			if ($arrNbEventID = $objEvent->stpi_getObjTypeEvent()->stpi_selNbEventID())
			{
				$ok = false;
				$objEvent->stpi_getObjTypeEvent()->stpi_affNoDelete();
				foreach ($arrNbEventID as $nbEventID)
				{
					if ($objEvent->stpi_setNbID($nbEventID))
					{
						if ($objEvent->stpi_setObjEventLgFromBdd())
						{
							print("<a href=\"./event.php?l=" . LG);
							print("&amp;nbEventID=" . $objBdd->stpi_trsBddToHTML($nbEventID) . "\">");
							print($objBdd->stpi_trsBddToHTML($objEvent->stpi_getObjEventLg()->stpi_getStrName()) . "</a>");
						}
					}
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objEvent->stpi_getObjTypeEvent()->stpi_getObjTypeEventLg()->stpi_deleteTypeEventId($_GET["nbTypeEventID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objEvent->stpi_getObjTypeEvent()->stpi_delete($_GET["nbTypeEventID"]))
					{
						$ok = false;
					}
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
		$objEvent->stpi_getObjTypeEvent()->stpi_affDelete();
	}
?>