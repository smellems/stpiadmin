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
		if (!$objEvent->stpi_setNbID($_GET["nbEventID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				$nbImageID = $objEvent->stpi_getNbImageID();
				if ($nbImageID != 0)
				{
					if (!$objEvent->stpi_getObjImg()->stpi_delete($nbImageID))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objEvent->stpi_getObjDateHeure()->stpi_deleteEventID($_GET["nbEventID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objEvent->stpi_getObjEventLg()->stpi_deleteEventId($_GET["nbEventID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objEvent->stpi_delete($_GET["nbEventID"]))
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
		$objEvent->stpi_affDelete();
	}
?>
