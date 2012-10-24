<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/commande/clsmethodpaye.php");
	require_once("./stpiadmin/includes/classes/commande/clsinfocarte.php");

	$objBdd = clsbdd::singleton();
	$objMethodPaye = new clsmethodpaye();
	$objInfoCarte = new clsinfocarte();
	
	if ($objMethodPaye->stpi_setNbID($_GET["nbMethodPayeID"]))
	{
		if ($objMethodPaye->stpi_setObjMethodPayeLgFromBdd())
		{
			$objMethodPayeLg =& $objMethodPaye->stpi_getObjMethodPayeLg();
			$strDesc = $objMethodPayeLg->stpi_getStrDesc();
			if (!empty($strDesc))
			{
				print("<p>\n");
				print($objBdd->stpi_trsBddToHTML($strDesc));
				print("</p>\n");			
			}
			if ($objMethodPaye->stpi_getBoolCarte())
			{
				$objInfoCarte->stpi_affAddPublic();
			}
		}
	}
?>