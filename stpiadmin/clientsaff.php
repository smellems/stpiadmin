<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/client/clsclient.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objClient = new clsclient();
		
	$objBdd = clsbdd::singleton();
	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	if (!$arrNbClientID = $objClient->stpi_selSearchClient($_GET["strClient"]))
	{
		exit;
	}
	
	foreach ($arrNbClientID as $nbClientID)
	{
		if ($objClient->stpi_setNbID($nbClientID))
		{
			print("<a href=\"./client.php?l=" . LG);
			print("&nbClientID=" . $objBdd->stpi_trsBddToHTML($nbClientID) . "\">");
			print($objBdd->stpi_trsBddToHTML($objClient->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrNom()) . "</a><br/>");
		}
	}	
?>