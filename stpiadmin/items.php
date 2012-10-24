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
		print("<a name=\"top\"></a>");
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		// menu
		print("<p>\n");
		print("<a href=\"./items.php?l=" . LG . "#cat\">" . $objTexte->stpi_getArrTxt("titre4") . "</a> - ");
		print("<a href=\"./items.php?l=" . LG . "#disp\">" . $objTexte->stpi_getArrTxt("titre3") . "</a> - ");
		print("<a href=\"./items.php?l=" . LG . "#typeitem\">" . $objTexte->stpi_getArrTxt("titre2") . "</a> - ");
		print("<a href=\"./items.php?l=" . LG . "#typeattribut\">" . $objTexte->stpi_getArrTxt("titre5") . "</a> - ");
		print("<a href=\"./items.php?l=" . LG . "#attribut\">" . $objTexte->stpi_getArrTxt("titre6") . "</a> - ");
		print("<a href=\"./items.php?l=" . LG . "#typeprix\">" . $objTexte->stpi_getArrTxt("titre7") . "</a> - ");
		print("<a href=\"./items.php?l=" . LG . "#item\">" . $objTexte->stpi_getArrTxt("titre1") . "</a>");
		print("</p>\n");
		
		print("<h2>" . $objTexte->stpi_getArrTxt("search") . "</h2>\n");
		$objItem->stpi_getObjCatItem()->stpi_affJsSearch();
		$objItem->stpi_getObjCatItem()->stpi_affSearch();
		$objItem->stpi_getObjDispItem()->stpi_affJsSearch();
		$objItem->stpi_getObjDispItem()->stpi_affSearch();
		$objItem->stpi_getObjTypeItem()->stpi_affJsSearch();
		$objItem->stpi_getObjTypeItem()->stpi_affSearch();
		
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_affJsSearch();
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_affSearch();
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_affJsSearch();
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_affSearch();
		
		$objItem->stpi_getObjSousItem()->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_affJsSearch();
		$objItem->stpi_getObjSousItem()->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_affSearch();
		
		$objItem->stpi_affJsSearch();
		$objItem->stpi_affSearch();

		print("<a name=\"cat\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addcatitem") . "</h2>\n");
		$objItem->stpi_getObjCatItem()->stpi_affJsAdd();
		$objItem->stpi_getObjCatItem()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./items.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"disp\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("adddispitem") . "</h2>\n");
		$objItem->stpi_getObjDispItem()->stpi_affJsAdd();
		$objItem->stpi_getObjDispItem()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./items.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");

		print("<a name=\"typeitem\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypeitem") . "</h2>\n");
		$objItem->stpi_getObjTypeItem()->stpi_affJsAdd();
		$objItem->stpi_getObjTypeItem()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./items.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"typeattribut\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypeattribut") . "</h2>\n");
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_affJsAdd();
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./items.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"attribut\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addattribut") . "</h2>\n");
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_affJsAdd();
		$objItem->stpi_getObjSousItem()->stpi_getObjAttribut()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./items.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"typeprix\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypeprix") . "</h2>\n");
		$objItem->stpi_getObjSousItem()->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_affJsAdd();
		$objItem->stpi_getObjSousItem()->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./items.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"item\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("additem") . "</h2>\n");
		$objItem->stpi_affJsAdd();
		$objItem->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./items.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
	?>
	</div>	
	</body>
</html>