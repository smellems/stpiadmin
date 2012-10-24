<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/item/clsitem.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objItem = new clsitem();
	
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre4") . "</h1>\n");
		
		if ($objItem->stpi_getObjCatItem()->stpi_setNbID($_GET["nbCatItemID"]))
		{
			if ($objItem->stpi_getObjCatItem()->stpi_setArrObjCatItemLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbCatItemID\" value=\"" . $objItem->stpi_getObjCatItem()->stpi_getNbID() . "\" />\n");
				$objItem->stpi_getObjCatItem()->stpi_affJsDelete();
				$objItem->stpi_getObjCatItem()->stpi_affJsEdit();
				$objItem->stpi_getObjCatItem()->stpi_affEdit();
			}
		}

		print("<h2>\n");
		print($objTexte->stpi_getArrTxt("titre2"));
		print("</h2>\n");
		if ($arrNbTypeItemID = $objItem->stpi_getObjCatItem()->stpi_selNbTypeItemID())
		{
			print("<ul>\n");
			foreach ($arrNbTypeItemID as $nbTypeItemID)
			{
				if ($objItem->stpi_getObjTypeItem()->stpi_setNbID($nbTypeItemID))
				{
					if ($objItem->stpi_getObjTypeItem()->stpi_setObjTypeItemLgFromBdd())
					{
						print("<li>\n");
						print("<a href=\"./itemtypeitem.php?l=" . LG . "&amp;nbTypeItemID=" . $objBdd->stpi_trsBddToHTML($nbTypeItemID) . "\" >");
						print($objBdd->stpi_trsBddToHTML($objItem->stpi_getObjTypeItem()->stpi_getObjTypeItemLg()->stpi_getStrName()));
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