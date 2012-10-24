<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clseventadresse.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objAdresse = new clseventadresse();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	if (!$objAdresse->stpi_setStrEndroit($_POST["strEndroit"]))
	{
		$ok = false;
	}
	if (!$objAdresse->stpi_setStrAdresse($_POST["strAdresse"]))
	{
		$ok = false;
	}
	if (!$objAdresse->stpi_setStrVille($_POST["strVille"]))
	{
		$ok = false;
	}
	
	list($strProvinceID, $strCountryID) = explode("-", $_POST["strProvinceID"]);
	if ($objAdresse->stpi_setStrProvinceID($strProvinceID))
	{
		if ($objAdresse->stpi_getObjCountry()->stpi_setStrId($strCountryID) AND $objAdresse->stpi_getObjCountry()->stpi_chkProvinceInCountry($strProvinceID))
		{
			if (!$objAdresse->stpi_setStrCountryID($strCountryID))
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
		if (!$objAdresse->stpi_setStrCountryID($_POST["strCountryID"]) OR !$objAdresse->stpi_setStrProvinceID("isNULL"))
		{
			$ok = false;
		}
	}
	
	if (!$objAdresse->stpi_setStrCodePostal($_POST["strCodePostal"]))
	{
		$ok = false;
	}
	
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$objAdresse->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbAdresseID=" . $objAdresse->stpi_getNbID());
				}
				else
				{
					$objBdd->stpi_rollback();
				}
			}
			else
			{
				$objBdd->stpi_rollback();
			}
		}
	}
?>