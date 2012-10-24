<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/registre/clsregistre.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objRegistre = new clsregistre();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/registres");
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
		
		if ($objRegistre->stpi_setNbID($_GET["nbRegistreID"]))
		{
			$objRegistre->stpi_affJsEdit();
			$objRegistre->stpi_affJsDelete();
			$objRegistre->stpi_affEdit();

			print("<h1>" . $objTexte->stpi_getArrTxt("titre2") . "</h1>\n");
			if ($arrNbSousItemID = $objRegistre->stpi_selNbSousItemID())
			{
				print("<table border=\"1\"><tr>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objTexte->stpi_getArrTxt("titre2")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getObjTexte()->stpi_getArrTxt("codeitem")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qtevoulu")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qterecu")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getObjTexte()->stpi_getArrTxt("desc")) . "</td>\n");
				print("</tr>\n");
				foreach($arrNbSousItemID as $nbSousItemID)
				{
					if ($objRegistre->stpi_getObjRegistreSousItem()->stpi_setNbID($objRegistre->stpi_getNbID(), $nbSousItemID))
					{
						print("<tr><td>" . $objBdd->stpi_trsBddToHTML($nbSousItemID) . "</td>\n");
						print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getStrItemCode()) . "</td>\n");
						print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getNbQteVoulu()) . "</td>\n");
						print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getNbQteRecu()) . "</td>\n");
						print("<td><a href=\"./registresousitem.php?l=" . LG . "&amp;nbRegistreID=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbID()));
						print("&amp;nbSousItemID=" . $objBdd->stpi_trsBddToHTML($nbSousItemID) . "\">" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjRegistreSousItem()->stpi_getStrSousItemDesc()) . "</a></td>");
						print("</tr>\n");
					}
				}
				print("</table>\n");
			}
			print("<h2>" . $objTexte->stpi_getArrTxt("addsousitem") . "</h2>\n");
			$objRegistre->stpi_getObjRegistreSousItem()->stpi_affJsAdd();
			$objRegistre->stpi_getObjRegistreSousItem()->stpi_getObjItem()->stpi_affJsSelectItem();
			$objRegistre->stpi_getObjRegistreSousItem()->stpi_getObjItem()->stpi_affJsSelectSousItem();
			$objRegistre->stpi_getObjRegistreSousItem()->stpi_affJsInfoSousItem();
			$objRegistre->stpi_getObjRegistreSousItem()->stpi_affAdd();
		}		
	?>
	</div>	
	</body>
</html>
