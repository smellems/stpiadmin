<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/area/clscountry.php");
	require_once("./stpiadmin/includes/classes/client/clsclient.php");
	require_once("./stpiadmin/includes/classes/commande/clsadresse.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	
	$objCountry = new clscountry();
	$objCommandeSession = new clscommandesession();
	$objTexte = new clstexte("./texte/checkout");
	$objAdresseSession = new clsadressesession();
	$objAdresse = new clsadresse();
	$objRegistre = new clsregistre();
	$objClient = new clsclient();
	
	$objProvince =& $objCountry->stpi_getObjProvince();	
	

	if (!isset($_SESSION["stpiObjCommandeRegistreSession"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (!$objCommandeSession = $objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession())
	{
		exit;
	}
	
	if (!$objRegistre->stpi_setNbID($objCommandeSession->stpi_getNbRegistreID()))
	{
		exit;
	}
	
	$arrObjAdresseSession = $objCommandeSession->stpi_getArrObjAdresseSession();
			
	if (!$objAdresse->stpi_setNbTypeAdresseID(2))
	{
		exit;
	}
	
	if (!isset($_POST["nbDeliveryID"]))
	{
		exit;
	}
	
	if ($_POST["nbDeliveryID"] == 0)
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
	elseif ($_POST["nbDeliveryID"] == 1)
	{
		if (!$objClient->stpi_setNbID(1))
		{
			exit;
		}
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
	elseif ($_POST["nbDeliveryID"] == 2)
	{
		if (!$objClient->stpi_setNbID($objRegistre->stpi_getNbClientID()))
		{
			exit;
		}
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
	
	if (!$objCommandeSession->stpi_setObjCommandeRegistreSessionToSession())
	{
		exit;
	}
	
	print("redirect");
	
?>