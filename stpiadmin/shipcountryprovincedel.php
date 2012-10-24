<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/ship/clszone.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objZone = new clszone();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if (isset($_GET["strProvinceID"]))
		{
			if (!$objZone->stpi_deleteCountryProvince($_GET["nbZoneID"], $_GET["strCountryID"], $_GET["strProvinceID"]))
			{
				exit;
			}	
		}
		else
		{
			if (!$objZone->stpi_deleteCountryProvince($_GET["nbZoneID"], $_GET["strCountryID"]))
			{
				exit;
			}	
		}
		
		print("redirect");
	}
	else
	{
		if (isset($_GET["strProvinceID"]))
		{
			$objZone->stpi_affDeleteCountryProvince($_GET["nbZoneID"], $_GET["strCountryID"], $_GET["strProvinceID"]);	
		}
		else
		{
			$objZone->stpi_affDeleteCountryProvince($_GET["nbZoneID"], $_GET["strCountryID"]);
		}		
	}
?>