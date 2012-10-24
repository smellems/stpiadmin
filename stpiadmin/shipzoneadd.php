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
	
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
		
	if (!$objBdd->stpi_startTransaction())
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
	
	if (!$objZone->stpi_insert())
	{
		$objBdd->stpi_rollback();
		exit;	
	}
	
	$objZoneLg = $objZone->stpi_getObjZoneLg();
	
	foreach ($arrLang as $strLg)	
	{
		if (!$objZoneLg->stpi_setStrLg($strLg))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objZoneLg->stpi_setNbZoneID($objZone->stpi_getNbID()))
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
		if (!$objZoneLg->stpi_insert())
		{
			$objBdd->stpi_rollback();
			exit;
		}
	}
	
	$arrUnitRangeID = array();
	
	$arrUnitRangeID = $objUnitRange->stpi_selAllUnitRange();
	
	foreach ($arrUnitRangeID as $nbUnitRangeID)	
	{
		if (!$objUnitRange->stpi_setNbUnitRangeID($nbUnitRangeID))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objUnitRange->stpi_setNbZoneID($objZone->stpi_getNbID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objUnitRange->stpi_setNbPrix($_POST["nbPrix" . $nbUnitRangeID]))
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