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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		
		if ($objItem->stpi_setNbID($_GET["nbItemID"]))
		{
			if ($objItem->stpi_setArrObjItemLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbItemID\" value=\"" . $objItem->stpi_getNbID() . "\" />\n");
				$objItem->stpi_affJsDelete();
				$objItem->stpi_affJsEdit();
				print("<p><a href=\"./itemtypeitem.php?l=" . LG . "&amp;nbTypeItemID=" . $objItem->stpi_getNbTypeItemID() . "\">" . $objTexte->stpi_getArrTxt("backtotypeitem") . "</a></p>");
				$objItem->stpi_affEdit();
				
				print("<h1>" . $objTexte->stpi_getArrTxt("titre9") . "</h1>\n");
				if ($arrNbSousItemID = $objItem->stpi_selNbSousItemID())
				{
					foreach($arrNbSousItemID as $nbSousItemID)
					{
						if ($objItem->stpi_getObjSousItem()->stpi_setNbID($nbSousItemID))
						{
							print("<a href=\"./itemsousitem.php?l=" . LG . "&amp;nbSousItemID=" . $nbSousItemID . "\">(" . $objItem->stpi_getObjSousItem()->stpi_getStrItemCode() . ") - " . $objItem->stpi_getStrSousItemDesc() . "</a><br/>");
						}
					}
				}
				print("<h2>" . $objTexte->stpi_getArrTxt("addsousitem") . "</h2>\n");
				$objItem->stpi_getObjSousItem()->stpi_affJsAdd();
				$objItem->stpi_getObjSousItem()->stpi_affAdd();
			}
		}			
	?>		
	</div>
	</body>
</html>