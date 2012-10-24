<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/ship/clszone.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objZone = new clszone();
	$objUnitRange = $objZone->stpi_getObjUnitRange();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/ship");
	$objJavaScript = new clsjavascript();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objMenu = new clsmenu($strPage);
	$objRestrictedMenu = new clsrestrictedmenu($strPage);
	$objLock = new clslock($strPage);
	$objFooter = new clsfooter();
	
	$objLock->stpi_run();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php
		$objHead->stpi_affSTPIAdminHead();
	?>
	<body>
	<?php
		$objJavaScript->stpi_affArrLang();
		$objJavaScript->stpi_affNoAjax();
		$objJavaScript->stpi_affCreateXmlHttp();
		
		print("<div id=\"menulang\">\n");
		$objMenu->stpi_affSTPIAdminMenuLang();
		print("</div>\n");		
	?>
	<div id="gauche">
	<?php
		print("<div id=\"menu\">\n");
		$objRestrictedMenu->stpi_affSTPIAdminMenu();
		print("</div>\n");					

		print("<div id=\"footer\">\n");
		$objFooter->stpi_affSTPIAdminFooter();
		print("</div>\n");
	?>
	</div>
	<div id="droite">
	<?php
		$objJavaScript->stpi_affNoJavaScript();
		print("<h1>" . $objTexte->stpi_getArrTxt("titre3") . "</h1>\n");
		
		if ($objZone->stpi_setNbID($_GET["nbZoneID"]))
		{
			print("<h2>" . $objTexte->stpi_getArrTxt("titre5") . "</h2>\n");
			
			$objZone->stpi_affJsDeleteCountryProvince();
			
			$objZone->stpi_affAllCountryProvince();
		
			print("<h2>" . $objTexte->stpi_getArrTxt("titre6") . "</h2>\n");
			
			$objZone->stpi_affJsAddCountryProvince();
			
			$objZone->stpi_affAddCountryProvince();
		
			print("<h2>" . $objTexte->stpi_getArrTxt("titre7") . "</h2>\n");
			
			$objZone->stpi_affJsDelete();
			$objZone->stpi_affJsEdit();
			
			$objZone->stpi_affEdit();
		}
	?>		
	</div>
	</body>
</html>