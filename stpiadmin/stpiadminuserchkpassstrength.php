<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/security/clscryption.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	$objCryption = new clsCryption();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	$objTexte = $objCryption->stpi_getObjTexte();
	
	if (!$nbScore = $objCryption->stpi_chkPasswordStrength($_GET["strPassword"]))
	{
		print("<span style=\"color:#FF0000;\"><b>" . $objTexte->stpi_getArrTxt("weak") . "</b></span>\n");
		exit;
	}
	
	if ($nbScore >= 70 && $nbScore < 80)
	{
		print("<span style=\"color:#d08f1d;\"><b>" . $objTexte->stpi_getArrTxt("accepted") . "</b></span>\n");
	}
	else if ($nbScore >= 80 && $nbScore < 90)
	{
		print("<span style=\"color:#8aa714;\"><b>" . $objTexte->stpi_getArrTxt("good") . "</b></span>\n");
	}
	else if ($nbScore >= 90 && $nbScore < 100)
	{
		print("<span style=\"color:#008000;\"><b>" . $objTexte->stpi_getArrTxt("excellent") . "</b></span>\n");
	}
	else if ($nbScore >= 100)
	{
		print("<span style=\"color:#008000;\"><b>" . $objTexte->stpi_getArrTxt("strong") . "</b></span>\n");
	}	
?>