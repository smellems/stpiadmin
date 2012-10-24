<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/news/clsnews.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNews = new clsnews();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/newss");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		print("<p>\n");
		print("<a href=\"./newss.php?l=" . LG . "#typenews\">" . $objTexte->stpi_getArrTxt("titre4") . "</a> - ");
		print("<a href=\"./newss.php?l=" . LG . "#news\">" . $objTexte->stpi_getArrTxt("titre1") . "</a>");
		print("</p>\n");
		print("<h2>" . $objTexte->stpi_getArrTxt("search") . "</h2>\n");
		$objNews->stpi_getObjTypeNews()->stpi_affJsSearch();
		$objNews->stpi_getObjTypeNews()->stpi_affSearch();
		$objNews->stpi_affJsSearch();
		$objNews->stpi_affSearch();

		print("<a name=\"typenews\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypenews") . "</h2>\n");
		$objNews->stpi_getObjTypeNews()->stpi_affJsAdd();
		$objNews->stpi_getObjTypeNews()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./newss.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");

		print("<a name=\"news\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addnews") . "</h2>\n");
		$objNews->stpi_affJsAdd();
		$objNews->stpi_affAdd();
	?>
	</div>	
	</body>
</html>
