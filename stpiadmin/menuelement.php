<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/menu/clsmenuelement.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMenuElement = new clsmenuelement();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/menus");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		
		if ($objMenuElement->stpi_setNbID($_GET["nbMenuElementID"]))
		{
			if ($objMenuElement->stpi_setArrObjMenuElementLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbMenuElementID\" value=\"" . $objMenuElement->stpi_getNbID() . "\" />\n");
				$objMenuElement->stpi_affJsDelete();
				$objMenuElement->stpi_affJsEdit();
				print("<p><a href=\"./menu.php?l=" . LG . "&amp;nbMenuID=" . $objMenuElement->stpi_getNbMenuID() . "\">" . $objTexte->stpi_getArrTxt("backtomenu") . "</a></p>");
				if ($objMenuElement->stpi_getNbParentID() > 0)
				{
					print("<p><a href=\"./menuelement.php?l=" . LG . "&amp;nbMenuElementID=" . $objMenuElement->stpi_getNbParentID() . "\">" . $objTexte->stpi_getArrTxt("backtoparent") . "</a></p>");
				}

				$objMenuElement->stpi_affEdit();
			}
		}			
	?>		
	</div>
	</body>
</html>
