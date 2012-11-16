<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/lien/clslien.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/lien");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageNotEncrypted();
	
	$objLien = new clslien();
	$objTypeLien = $objLien->stpi_getObjTypeLien();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();
	if (!$arrNbTypeLienID = $objTypeLien->stpi_selAllPublic())
	{
		$arrNbTypeLienID = array();
	}
	
	foreach ($arrNbTypeLienID as $nbTypeLienID)
	{
		$objTypeLien->stpi_setNbID($nbTypeLienID);
		
		$objTypeLien->stpi_setObjTypeLienLgFromBdd();
		
		$objTypeLienLg = $objTypeLien->stpi_getObjTypeLienLg();
		
		print("<h2>" . $objBdd->stpi_trsBddToHTML($objTypeLienLg->stpi_getStrName()) . "</h2>\n");
		
		$strDesc = $objTypeLienLg->stpi_getStrDesc();
		if(!empty($strDesc))
		{
			print("<p>\n");
			print($objBdd->stpi_trsBddToBBCodeHTML($objTypeLienLg->stpi_getStrDesc()) . "\n");
			print("</p>\n");
		}
		
		print("<br/>\n");
		
		if (!$arrNbLienID = $objTypeLien->stpi_selNbLienID())
		{
			$arrNbLienID = array();
		}
		
		foreach ($arrNbLienID as $nbLienID)
		{
			$objLien->stpi_setNbID($nbLienID);
			
			$objLien->stpi_setObjLienLgFromBdd();
			
			$objLienLg = $objLien->stpi_getObjLienLg();
			
			print("<table border=\"0\">\n");
			print("<tr>\n");					
			$nbImageID = $objLien->stpi_getNbImageID();
			if (!empty($nbImageID))
			{
				print("<td style=\"text-align: center; vertical-align: middle;  width:" .  $objBdd->stpi_trsBddToHTML($objLien->stpi_getNbImgWidthMax()) . "px; height:" .  $objBdd->stpi_trsBddToHTML($objLien->stpi_getNbImgHeightMax()) . "px;\">\n");
				print("<img alt=\"" .  $objBdd->stpi_trsBddToHTML($objLien->stpi_getNbImageID()) . "\" src=\"./lienimgaff.php?nbImageID=" .  $objBdd->stpi_trsBddToHTML($objLien->stpi_getNbImageID()) . "\" />\n");
				print("</td>\n");
			}

			print("<td style=\"vertical-align: top;\">\n");
			print("<h3>" . $objBdd->stpi_trsBddToHTML($objLienLg->stpi_getStrName()) . "</h3>\n");
			$strDesc = $objLienLg->stpi_getStrDesc();
			print("<p>\n");
			if (!empty($strDesc))
			{						
				print($objBdd->stpi_trsBddToBBCodeHTML($objLienLg->stpi_getStrDesc()) . "<br/>\n");
			}
			print("<a target=\"_blank\" href=\"" . $objBdd->stpi_trsBddToHTML($objLienLg->stpi_getStrLien()) . "\">" . $objBdd->stpi_trsBddToHTML($objLienLg->stpi_getStrLien()) . "</a><br/>\n");
			print("</p>\n");
			print("</td>\n");
			print("</tr>\n");
			print("</table>\n");
			print("<br/>\n");
		}
	}
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
