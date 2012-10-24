<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/client/clsclient.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objClient = new clsclient();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	$ok = true;
	
	if (!$objClient->stpi_setNbID($_POST["nbClientID"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrCourriel($_POST["strCourriel"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrNom($_POST["strNom"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrPrenom($_POST["strPrenom"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrCie($_POST["strCie"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrTel($_POST["strTel"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrAdresse($_POST["strAdresse"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrVille($_POST["strVille"]))
	{
		$ok = false;
	}
	
	list($strProvinceID, $strCountryID) = explode("-", $_POST["strProvinceID"]);
	if ($objClient->stpi_setStrProvinceID($strProvinceID))
	{
		if ($objClient->stpi_getObjCountry()->stpi_setStrId($strCountryID) AND $objClient->stpi_getObjCountry()->stpi_chkProvinceInCountry($strProvinceID))
		{
			if (!$objClient->stpi_setStrCountryID($strCountryID))
			{
				$ok = false;
			}
		}
		else
		{
			$ok = false;
		}
	}
	else
	{
		if (!$objClient->stpi_setStrCountryID($_POST["strCountryID"]) OR !$objClient->stpi_setStrProvinceID(""))
		{
			$ok = false;
		}
	}
	
	if (!$objClient->stpi_setStrCodePostal($_POST["strCodePostal"]))
	{
		$ok = false;
	}
	if (!$objClient->stpi_setStrLangID($_POST["strLangID"]))
	{
		$ok = false;
	}	

	if ($ok)
	{
		if ($objClient->stpi_update())
		{
			print("redirect-nbClientID=" . $objClient->stpi_getNbID());
		}
	}
?>