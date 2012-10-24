<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/banniere/clsbanniere.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/bannieres");
	$objJavaScript = new clsjavascript();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objMenu = new clsmenu($strPage);
	$objRestrictedMenu = new clsrestrictedmenu($strPage);
	$objLock = new clslock($strPage);
	$objFooter = new clsfooter();
	
	$objLock->stpi_run();
	
	$objBanniere = new clsbanniere();
	$objTypeBanniere =& $objBanniere->stpi_getObjTypeBanniere();
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
		print("<a href=\"./bannieres.php?l=" . LG . "#typebanniere\">" . $objTexte->stpi_getArrTxt("titre2") . "</a> - ");
		print("<a href=\"./bannieres.php?l=" . LG . "#banniere\">" . $objTexte->stpi_getArrTxt("titre1") . "</a>");
		print("</p>\n");
		print("<h2>" . $objTexte->stpi_getArrTxt("search") . "</h2>\n");
		$objTypeBanniere->stpi_affJsSearch();
		$objTypeBanniere->stpi_affSearch();
		$objBanniere->stpi_affJsSearch();
		$objBanniere->stpi_affSearch();

		print("<a name=\"typebanniere\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypebanniere") . "</h2>\n");
		$objTypeBanniere->stpi_affJsAdd();
		$objTypeBanniere->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./bannieres.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"banniere\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addbanniere") . "</h2>\n");
		$objBanniere->stpi_affJsAdd();
		$objBanniere->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./bannieres.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
	?>
	</div>	
	</body>
</html>