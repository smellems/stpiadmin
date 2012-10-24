<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/ship/clszone.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objZone = new clszone();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if (isset($_POST["strProvinceID"]))
	{
		if (!$objZone->stpi_insertCountryProvince($_POST["nbZoneID"], $_POST["strCountryID"], $_POST["strProvinceID"]))
		{
			exit;
		}	
		print("redirect");
	}
	else
	{
		if (!$objZone->stpi_insertCountryProvince($_POST["nbZoneID"], $_POST["strCountryID"]))
		{
			exit;
		}	
		print("redirect");	
	}
?>