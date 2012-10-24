<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/ship/clszone.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objZone = new clszone();
	$objUnitRange = $objZone->stpi_getObjUnitRange();
	
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if (!$objBdd->stpi_startTransaction())
		{
			exit;
		}
		
		$objZoneLg = $objZone->stpi_getObjZoneLg();
		
		if (!$objZoneLg->stpi_deleteZoneID($_GET["nbZoneID"]))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		
		if ($objBdd->stpi_chkExists($_GET["nbZoneID"], "nbZoneID", "stpi_ship_UnitRange"))
		{
			if (!$objUnitRange->stpi_deleteZoneID($_GET["nbZoneID"]))
			{
				$objBdd->stpi_rollback();
				exit;
			}			
		}
		
		if (!$objZone->stpi_delete($_GET["nbZoneID"]))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		
		if ($objBdd->stpi_commit())
		{
			print("redirect");
		}
		else
		{
			$objBdd->stpi_rollback();
			exit;
		}
	}
	else
	{
		$objZone->stpi_affDelete();
	}
?>