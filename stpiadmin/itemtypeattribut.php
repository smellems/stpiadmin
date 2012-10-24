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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre5") . "</h1>\n");
		
		if ($objAttribut->stpi_getObjTypeAttribut()->stpi_setNbID($_GET["nbTypeAttributID"]))
		{
			if ($objAttribut->stpi_getObjTypeAttribut()->stpi_setArrObjTypeAttributLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbTypeAttributID\" value=\"" . $objAttribut->stpi_getObjTypeAttribut()->stpi_getNbID() . "\" />\n");
				$objAttribut->stpi_getObjTypeAttribut()->stpi_affJsDelete();
				$objAttribut->stpi_getObjTypeAttribut()->stpi_affJsEdit();
				$objAttribut->stpi_getObjTypeAttribut()->stpi_affEdit();
			}
		}

		print("<h2>\n");
		print($objTexte->stpi_getArrTxt("titre6"));
		print("</h2>\n");
		if ($arrNbAttributID = $objAttribut->stpi_getObjTypeAttribut()->stpi_selNbAttributID())
		{
			print("<ul>\n");
			foreach ($arrNbAttributID as $nbAttributID)
			{
				if ($objAttribut->stpi_setNbID($nbAttributID))
				{
					if ($objAttribut->stpi_setObjAttributLgFromBdd())
					{
						print("<li>\n");
						print($objBdd->stpi_trsBddToHTML($objAttribut->stpi_getNbOrdre()) . " - ");
						print("<a href=\"./itemattribut.php?l=" . LG . "&amp;nbAttributID=" . $objBdd->stpi_trsBddToHTML($nbAttributID) . "\" >");
						print($objBdd->stpi_trsBddToHTML($objAttribut->stpi_getObjAttributLg()->stpi_getStrName()));
						print("</a>\n");
						print("</li>\n");
					}
				}
			}
			print("</ul>\n");
		}
	?>		
	</div>
	</body>
</html>