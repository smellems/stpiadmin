<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/item/clsattribut.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objAttribut = new clsattribut();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre6") . "</h1>\n");
		
		if ($objAttribut->stpi_setNbID($_GET["nbAttributID"]))
		{
			if ($objAttribut->stpi_setArrObjAttributLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbAttributID\" value=\"" . $objAttribut->stpi_getNbID() . "\" />\n");
				$objAttribut->stpi_affJsDelete();
				$objAttribut->stpi_affJsEdit();
				print("<p><a href=\"./itemtypeattribut.php?l=" . LG . "&amp;nbTypeAttributID=" . $objAttribut->stpi_getNbTypeAttributID() . "\">" . $objTexte->stpi_getArrTxt("backtotypeattribut") . "</a></p>");
				$objAttribut->stpi_affEdit();
			}
		}			
	?>		
	</div>
	</body>
</html>