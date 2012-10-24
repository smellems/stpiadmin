<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/stpiadminuser/clsstpiadminuser.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSTPIAdminUser = new clsstpiadminuser();
		
	$objBdd = clsbdd::singleton();
	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if (!$arrNbSTPIAdminID = $objSTPIAdminUser->stpi_selSearchUsername($_GET["strUsername"]))
	{
		exit;
	}
	
	foreach ($arrNbSTPIAdminID as $nbSTPIAdminID)
	{
		if ($objSTPIAdminUser->stpi_setNbID($nbSTPIAdminID))
		{
			print("<a href=\"./stpiadminuser.php?l=" . LG);
			print("&nbUserID=" . $objBdd->stpi_trsBddToHTML($nbSTPIAdminID) . "\">");
			print($objBdd->stpi_trsBddToHTML($objSTPIAdminUser->stpi_getStrUsername()) . "</a> : ");
			print($objBdd->stpi_trsBddToHTML($objSTPIAdminUser->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objSTPIAdminUser->stpi_getStrNom()) . "<br/>\n");
		}
	}	
?>