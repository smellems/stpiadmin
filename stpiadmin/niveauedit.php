<?php
	
	require_once("./includes/includes.php");
	require_once("./includes/classes/niveau/clsniveau.php");
	require_once("./includes/classes/security/clslock.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	$objNiveau = new clsniveau();
	
	$objNiveau->stpi_chkNiveauSectionIntegrity();	
	
	if (!$objNiveau->stpi_setNbID($_POST["nbNiveauID"]))
	{
		exit;
	}

	if (!$objNiveau->stpi_setStrName($_POST["strName"]))
	{
		exit;
	}
	
	
	$arrNbSectionID = array();
	
	foreach ($_POST as $k => $v)
	{
		if ($k == "sid" || $k == "nbNiveauID" || $k == "strName")
		{
			continue;
		}
		
		$arrExploded = explode("_", $k);
		
		if (isset($arrNbSectionID[$arrExploded[0]]))
		{
			$arrNbTypePageID = $arrNbSectionID[$arrExploded[0]];
		}
		else
		{
			$arrNbTypePageID = array();
		}
			
		$arrNbTypePageID[$arrExploded[1]] = $v;
		
		$arrNbSectionID[$arrExploded[0]] = $arrNbTypePageID;
	}
	
	if ($objNiveau->stpi_update($arrNbSectionID))
	{
		print("redirect");
	}

?>