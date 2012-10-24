<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/ship/clszone.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objZone = new clszone();
	$objUnitRange = $objZone->stpi_getObjUnitRange();
	
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if (!$objBdd->stpi_startTransaction())
	{
		exit;
	}
	
	if (!$objZone->stpi_setNbID($_POST["nbZoneID"]))
	{
		exit;
	}
	
	if (!$objZone->stpi_setBoolTaxable($_POST["boolTaxable"]))
	{
		exit;
	}
	
	if (!$objZone->stpi_setNbDefaultUnitPrice($_POST["nbDefaultUnitPrice"]))
	{
		exit;
	}
	
	if (!$objZone->stpi_update())
	{
		$objBdd->stpi_rollback();
		exit;
	}
		
	if (!$objZone->stpi_setArrObjZoneLgFromBdd())
	{
		exit;
	}
	
	foreach ($objZone->stpi_getArrObjZoneLg() as $strLg => $objZoneLg)	
	{
		if (!$objZoneLg->stpi_setStrLg($strLg))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objZoneLg->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objZoneLg->stpi_setStrDesc($_POST["strDesc" . $strLg]))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objZoneLg->stpi_setNbZoneID($objZone->stpi_getNbID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objZoneLg->stpi_update())
		{
			$objBdd->stpi_rollback();
			exit;
		}
	}
	
	$arrNbUnitRangeID = array();
	
	$arrNbUnitRangeID = $objUnitRange->stpi_selAllUnitRange();
	
	foreach ($arrNbUnitRangeID as $nbUnitRangeID)
	{
		if (!$objUnitRange->stpi_setNbID($nbUnitRangeID, $objZone->stpi_getNbID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objUnitRange->stpi_setNbPrix($_POST["nbPrix" . $nbUnitRangeID]))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objUnitRange->stpi_update())
		{
			$objBdd->stpi_rollback();
			exit;
		}
	}
	
	if ($objBdd->stpi_commit())
	{
		print("redirect-nbZoneID=" . $objZone->stpi_getNbID());
	}
	else
	{
		$objBdd->stpi_rollback();
		exit;
	}
?>