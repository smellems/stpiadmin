<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/event/clsevent.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objEvent = new clsevent();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/events");
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
		print("<a name=\"top\"></a>");
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		print("<p>\n");
		print("<a href=\"./events.php?l=" . LG . "#typeevent\">" . $objTexte->stpi_getArrTxt("titre2") . "</a> - ");
		print("<a href=\"./events.php?l=" . LG . "#adresse\">" . $objTexte->stpi_getArrTxt("titre3") . "</a> - ");
		print("<a href=\"./events.php?l=" . LG . "#event\">" . $objTexte->stpi_getArrTxt("titre1") . "</a>");
		print("</p>\n");
		print("<h2>" . $objTexte->stpi_getArrTxt("search") . "</h2>\n");
		$objEvent->stpi_getObjTypeEvent()->stpi_affJsSearch();
		$objEvent->stpi_getObjTypeEvent()->stpi_affSearch();
		$objEvent->stpi_getObjAdresse()->stpi_affJsSearch();
		$objEvent->stpi_getObjAdresse()->stpi_affSearch();
		$objEvent->stpi_affJsSearch();
		$objEvent->stpi_affSearch();

		print("<a name=\"typeevent\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypeevent") . "</h2>\n");
		$objEvent->stpi_getObjTypeEvent()->stpi_affJsAdd();
		$objEvent->stpi_getObjTypeEvent()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./events.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"adresse\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addadresse") . "</h2>\n");
		$objEvent->stpi_getObjAdresse()->stpi_affJsAdd();
		$objEvent->stpi_getObjAdresse()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./events.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"event\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addevent") . "</h2>\n");
		$objEvent->stpi_affJsAdd();
		$objEvent->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./events.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
	?>
	</div>	
	</body>
</html>