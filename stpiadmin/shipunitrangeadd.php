<?php
	
	require_once("./includes/includes.php");
	require_once("./includes/classes/ship/clsunitrange.php");
	require_once("./includes/classes/ship/clszone.php");
	require_once("./includes/classes/security/clslock.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	$objUnitRange = new clsunitrange();
	$objZone = new clszone();
	
	if (!$objUnitRange->stpi_chkNbUnitRangeID($_POST["nbUnits"]))
	{
		exit;
	}
	
	if (!$objBdd->stpi_startTransaction())
	{
		exit;
	}
	
	foreach ($objZone->stpi_selAll() as $nbZoneID)
	{
		if (!$objUnitRange->stpi_setNbUnitRangeID($_POST["nbUnits"]))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objUnitRange->stpi_setNbZoneID($nbZoneID))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objUnitRange->stpi_setNbPrix("0.0"))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objUnitRange->stpi_insert())
		{
			$objBdd->stpi_rollback();
			exit;
		}	
	}
	
	if (!$objBdd->stpi_commit())
	{
		$objBdd->stpi_rollback();
		exit;
	}
	else
	{
		print("redirect");
	}

?>