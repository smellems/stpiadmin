<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/lien/clslien.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objLien = new clslien();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/liens");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre2") . "</h1>\n");
		if ($objLien->stpi_getObjTypeLien()->stpi_setNbID($_GET["nbTypeLienID"]))
		{
			if ($objLien->stpi_getObjTypeLien()->stpi_setArrObjTypeLienLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbTypeLienID\" value=\"" . $objLien->stpi_getObjTypeLien()->stpi_getNbID() . "\" />\n");
				$objLien->stpi_getObjTypeLien()->stpi_affJsDelete();
				$objLien->stpi_getObjTypeLien()->stpi_affJsEdit();
				$objLien->stpi_getObjTypeLien()->stpi_affEdit();
			}
		}

		print("<h2>\n");
		print($objTexte->stpi_getArrTxt("titre1"));
		print("</h2>\n");
		if ($arrNbLienID = $objLien->stpi_getObjTypeLien()->stpi_selNbLienID())
		{
			print("<ul>\n");
			foreach ($arrNbLienID as $nbLienID)
			{
				if ($objLien->stpi_setNbID($nbLienID))
				{
					if ($objLien->stpi_setObjLienLgFromBdd())
					{
						print("<li>\n");
						print("<a href=\"./lien.php?l=" . LG);
					print("&amp;nbLienID=" . $objBdd->stpi_trsBddToHTML($nbLienID) . "\">");
					print($objBdd->stpi_trsBddToHTML($objLien->stpi_getObjLienLg()->stpi_getStrName()) . "</a> - " . $objBdd->stpi_trsBddToHTML($objLien->stpi_getObjLienLg()->stpi_getStrLien()) . "\n");
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
