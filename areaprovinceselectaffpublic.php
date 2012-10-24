<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/area/clscountry.php");
	
	$objCountry = new clscountry();
	
	if (!$objCountry->stpi_setStrID($_GET["strCountryID"]))
	{
		exit;
	}
	
	$objCountry->stpi_affSelectProvinceShippable();
?>