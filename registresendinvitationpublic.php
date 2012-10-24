<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
		
	$objTexte = new clstexte("./texte/registre");
	$objLock = new clslock($strPage, "login.php");
	$objBdd = clsbdd::singleton();
	
	$objLock->stpi_run();
	
	$objUser = new clsuser();
	$objRegistre = new clsregistre();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objEmail =& $objClient->stpi_getObjEmail();
	
	$strEmailFrom = STR_EMAIL_FROM;
	$strEmailSubject = "";
	$strMessage = "";
	
	if (!$objRegistre->stpi_setNbID($_POST["nbRegistreID"]))
	{
		exit;
	}
	
	if (!$objRegistre->stpi_chkIfActif())
	{
		exit;
	}
	
	if (!$objRegistre->stpi_chkIfNotExpired())
	{
		exit;
	}
	
	if (!$objUser = $objUser->stpi_getObjUserFromSession())
	{
		exit;
	}
	
	if ($objUser->stpi_getNbTypeUserID() != 2)
	{
		exit;
	}
	
	if (!$objClient->stpi_setNbID($objUser->stpi_getNbID()))
	{
		exit;			
	}
	
	if ($objUser->stpi_getNbID() != $objRegistre->stpi_getNbClientID())
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notyours") . "</span><br/>\n");
		exit;
	}
	
	if (!$arrStrEmail = explode(",", $_POST["strEmails"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptyemails") . "</span><br/>\n");
		exit;
	}
	
	if (empty($arrStrEmail))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptyemails") . "</span><br/>\n");
		exit;
	}
	
	foreach ($arrStrEmail as $k => $strEmail)
	{
		$strEmail = trim($strEmail);
		$arrStrEmail[$k] = $strEmail;
		
		if (!$objEmail->stpi_chkStrEmail($strEmail))
		{
			print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($strEmail) . "</span><br/>\n");
			exit;	
		}		
	}
	
	$strEmailSubject = STR_NOM_ENT . " " . $objTexte->stpi_getArrTxt("welcome") . " " . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode());
	
	$strMessage .= "<h2>" . $objTexte->stpi_getArrTxt("registrede") . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrNom()) . " (" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . ")</h2>\n";
	
	$strMessage .= "<p>" . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrNom()) . " " . $objTexte->stpi_getArrTxt("emailmessage") . " " . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrMessage()) . "</p>\n";
	
	$strMessage .= "<h3><a href=\"shopregistre.php?l" . LG . "&amp;strRegistreCode=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . "\" >" . $objTexte->stpi_getArrTxt("emaillink") . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrNom()) . "</a></h3>\n";
	
	$objEmail->stpi_setStrFromEmail($strEmailFrom);
	$objEmail->stpi_setStrSubject($strEmailSubject);
	$objEmail->stpi_setStrMessage($strMessage);
	
	foreach ($arrStrEmail as $k => $strEmail)
	{
		$objEmail->stpi_setStrEmail($strEmail);
			
		if (!$objEmail->stpi_Send())
		{
			print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($strEmail) . "</span><br/>\n");
		}
	}
	
	print("<span style=\"color:#008000;\">" .  $objTexte->stpi_getArrTxt("emailsent") . "</span><br/>\n");
	
?>