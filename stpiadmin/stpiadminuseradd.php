<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/stpiadminuser/clsstpiadminuser.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSTPIAdminUser = new clsstpiadminuser();
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	$objTexte = new clstexte("./texte/stpiadminuser");
	
	if (!$objSTPIAdminUser->stpi_setNbNiveauID($_POST["nbNiveau"]))
	{
		exit;
	}
	if (!$objSTPIAdminUser->stpi_setStrUserName($_POST["strUsername"]))
	{
		exit;
	}
	if ($_POST["strPassword"] != $_POST["strPasswordConfirm"])
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("passworddifferent") . "</span><br/>\n");
		exit;
	}
	if (!$objSTPIAdminUser->stpi_setStrPassword($_POST["strPassword"]))
	{
		exit;
	}
	if (!$objSTPIAdminUser->stpi_setStrNom($_POST["strNom"]))
	{
		exit;
	}
	if (!$objSTPIAdminUser->stpi_setStrPrenom($_POST["strPrenom"]))
	{
		exit;
	}
	if (!$objSTPIAdminUser->stpi_setStrEmail($_POST["strEmail"]))
	{
		exit;
	}
		
	if ($objSTPIAdminUser->stpi_insert())
	{
		print("redirect-nbUserID=" . $objSTPIAdminUser->stpi_getNbID());
	}
?>