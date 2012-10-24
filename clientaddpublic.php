<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/client/clsclient.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/img/clscaptcha.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTexte = new clstexte("./texte/client");	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_pageEncrypted();
	
	$objClient = new clsclient();
	$objUser = new clsuser();
	$objCountry =& $objClient->stpi_getObjCountry();
	$objProvince =& $objCountry->stpi_getObjProvince();
	$objCaptcha = new clscaptcha();

	if (!$objClient->stpi_setStrCourriel($_POST["strCourriel"]))
	{
		exit;
	}
	
	if ($_POST["strPassword"] != $_POST["strPasswordConfirm"])
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("passworddifferent") . "</span><br/>\n");
		exit;
	}
	else
	{
		if (!$objClient->stpi_setStrPassword($_POST["strPassword"]))
		{
			exit;
		}
	}
	
	if (!$objClient->stpi_setStrNom($_POST["strNom"]))
	{
		exit;
	}
	if (!$objClient->stpi_setStrPrenom($_POST["strPrenom"]))
	{
		exit;
	}
	if (!$objClient->stpi_setStrCie($_POST["strCie"]))
	{
		exit;
	}
	if (!$objClient->stpi_setStrTel($_POST["strTel"]))
	{
		exit;
	}
	if (!$objClient->stpi_setStrAdresse($_POST["strAdresse"]))
	{
		exit;
	}
	if (!$objClient->stpi_setStrVille($_POST["strVille"]))
	{
		exit;
	}
	
	if (!$objCountry->stpi_setStrID($_POST["strCountryID"]))
	{
		exit;
	}
	
	if (!$objClient->stpi_setStrCountryID($_POST["strCountryID"]))
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
		if (!$objClient->stpi_setStrProvinceID($strProvince))
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
		if (!$objClient->stpi_setStrProvinceID(""))
		{
			exit;
		}
	}
		
	if (!$objClient->stpi_setStrCodePostal($_POST["strCodePostal"]))
	{
		exit;
	}
	
	if (!$objClient->stpi_setStrLangID(LG))
	{
		exit;
	}
	
	if (empty($_POST["strCaptcha"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("pascaptcha") . "</span><br/>\n");
		exit;
	}
	
	if (!$objCaptcha->stpi_chkCaptcha($_POST["strCaptcha"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("captchainvalide") . "</span><br/>\n");
		exit;
	}
	
	if ($objClient->stpi_insert())
	{
		$objUser->stpi_setNbID($objClient->stpi_getNbID());
		$objUser->stpi_setNbTypeUserID(2);
		$objUser->stpi_setNbIP();
		$objUser->stpi_setObjUserToSession();
		
		print("redirect");
	}
?>