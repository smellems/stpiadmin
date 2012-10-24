<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/area/clscountry.php");
	
	$objCountry = new clscountry();
	
	if (!$objCountry->stpi_setStrID($_GET["strCountryID"]))
	{
		exit;
	}
	
	$objCountry->stpi_affSelectProvince();
?>