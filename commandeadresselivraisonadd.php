<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/area/clscountry.php");
	require_once("./stpiadmin/includes/classes/client/clsclient.php");
	require_once("./stpiadmin/includes/classes/commande/clsadresse.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	
	$objCountry = new clscountry();
	$objCommandeSession = new clscommandesession();
	$objTexte = new clstexte("./texte/checkout");
	$objAdresseSession = new clsadressesession();
	$objAdresse = new clsadresse();
	
	$objProvince =& $objCountry->stpi_getObjProvince();
	
	$objClient = new clsclient(1);
	

	if (!isset($_SESSION["stpiObjCommandeSession"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (!$objCommandeSession = $objCommandeSession->stpi_getObjCommandeSessionFromSession())
	{
		exit;
	}
	
	$arrObjAdresseSession = $objCommandeSession->stpi_getArrObjAdresseSession();
			
	if (!$objAdresse->stpi_setNbTypeAdresseID(2))
	{
		exit;
	}
	
	if (!isset($_POST["boolInStorePickup"]))
	{
		exit;
	}
	
	if ($_POST["boolInStorePickup"])
	{
		if (!$objAdresse->stpi_setStrNom($objClient->stpi_getStrNom()))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrPrenom($objClient->stpi_getStrPrenom()))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrCie($objClient->stpi_getStrCie()))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrAdresse($objClient->stpi_getStrAdresse()))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrVille($objClient->stpi_getStrVille()))
		{
			exit;
		}	
		
		if (!$objCountry->stpi_setStrID($objClient->stpi_getStrCountryID()))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrCountryID($objClient->stpi_getStrCountryID()))
		{
			exit;
		}
		
		$strProvince = $objClient->stpi_getStrProvinceID();
		if (!empty($strProvince))
		{
			if (!$objProvince->stpi_chkStrProvinceID($strProvince))
			{
				exit;			
			}
			if (!$objCountry->stpi_chkProvinceInCountry($strProvince))
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("countryprovince") . "</span><br/>\n");
				exit;
			}
			if (!$objAdresse->stpi_setStrProvinceID($strProvince))
			{
				exit;
			}		
		}
		else
		{
			if (!$objCountry->stpi_chkProvinceInCountry($strProvince))
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("countryprovince") . "</span><br/>\n");
				exit;
			}
			if (!$objAdresse->stpi_setStrProvinceID(""))
			{
				exit;
			}
		}
	
		if (!$objAdresse->stpi_setStrCodePostal($objClient->stpi_getStrCodePostal()))
		{
			exit;
		}
	}
	else
	{
		if (!$objAdresse->stpi_setStrNom($_POST["strNom"]))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrPrenom($_POST["strPrenom"]))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrCie($_POST["strCie"]))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrAdresse($_POST["strAdresse"]))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrVille($_POST["strVille"]))
		{
			exit;
		}	
		
		if (!$objCountry->stpi_setStrID($_POST["strCountryID"]))
		{
			exit;
		}
		
		if (!$objAdresse->stpi_setStrCountryID($_POST["strCountryID"]))
		{
			exit;
		}
		
		if (isset($_POST["strProvinceID"]))
		{
			$strProvince = $_POST["strProvinceID"];
		}
		else
		{
			$strProvince = "";
		}
			
		if (!empty($strProvince))
		{
			if (!$objProvince->stpi_chkStrProvinceID($strProvince))
			{
				exit;			
			}
			if (!$objCountry->stpi_chkProvinceInCountry($strProvince))
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("countryprovince") . "</span><br/>\n");
				exit;
			}
			if (!$objAdresse->stpi_setStrProvinceID($strProvince))
			{
				exit;
			}		
		}
		else
		{
			if (!$objCountry->stpi_chkProvinceInCountry($strProvince))
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("countryprovince") . "</span><br/>\n");
				exit;
			}
			if (!$objAdresse->stpi_setStrProvinceID(""))
			{
				exit;
			}
		}
	
		if (!$objAdresse->stpi_setStrCodePostal($_POST["strCodePostal"]))
		{
			exit;
		}
	}
	
	$objAdresseSession->stpi_setNbTypeAdresseID(2);
	$objAdresseSession->stpi_setStrNom($objAdresse->stpi_getStrNom());
	$objAdresseSession->stpi_setStrPrenom($objAdresse->stpi_getStrPrenom());
	$objAdresseSession->stpi_setStrCie($objAdresse->stpi_getStrCie());
	$objAdresseSession->stpi_setStrAdresse($objAdresse->stpi_getStrAdresse());
	$objAdresseSession->stpi_setStrVille($objAdresse->stpi_getStrVille());
	$objAdresseSession->stpi_setStrCountryID($objAdresse->stpi_getStrCountryID());
	$objAdresseSession->stpi_setStrProvinceID($objAdresse->stpi_getStrProvinceID());
	$objAdresseSession->stpi_setStrCodePostal($objAdresse->stpi_getStrCodePostal());
	
	$arrObjAdresseSession[2] = $objAdresseSession;
	
	$objCommandeSession->stpi_setArrObjAdresseSession($arrObjAdresseSession);
	
	if (!$objCommandeSession->stpi_setObjCommandeSessionToSession())
	{
		exit;
	}
	
	print("redirect");
	
?>