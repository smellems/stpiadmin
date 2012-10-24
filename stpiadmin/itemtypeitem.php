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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre2") . "</h1>\n");
		if ($objItem->stpi_getObjTypeItem()->stpi_setNbID($_GET["nbTypeItemID"]))
		{
			if ($objItem->stpi_getObjTypeItem()->stpi_setArrObjTypeItemLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbTypeItemID\" value=\"" . $objItem->stpi_getObjTypeItem()->stpi_getNbID() . "\" />\n");
				$objItem->stpi_getObjTypeItem()->stpi_affJsDelete();
				$objItem->stpi_getObjTypeItem()->stpi_affJsEdit();
				$objItem->stpi_getObjTypeItem()->stpi_affEdit();
			}
		}	

		print("<h2>\n");
		print($objTexte->stpi_getArrTxt("titre1"));
		print("</h2>\n");
		if ($arrNbItemID = $objItem->stpi_getObjTypeItem()->stpi_selNbItemID())
		{
			print("<ul>\n");
			foreach ($arrNbItemID as $nbItemID)
			{
				if ($objItem->stpi_setNbID($nbItemID))
				{
					if ($objItem->stpi_setObjItemLgFromBdd())
					{
						print("<li>\n");
						print("<a href=\"./item.php?l=" . LG . "&amp;nbItemID=" . $objBdd->stpi_trsBddToHTML($nbItemID) . "\" >");
						print($objBdd->stpi_trsBddToHTML($objItem->stpi_getObjItemLg()->stpi_getStrName()));
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