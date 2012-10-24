<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/commande/clscommande.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objCommande = new clscommande();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/commandes");
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
		
		if ($objCommande->stpi_setNbID($_GET["nbCommandeID"]))
		{
			$objCommande->stpi_affJsEdit();
			$objCommande->stpi_affJsDelete();
			$objCommande->stpi_affEdit();

			print("<h1>" . $objTexte->stpi_getArrTxt("titre6") . "</h1>\n");
			if ($arrNbSousItemID = $objCommande->stpi_selNbSousItemID())
			{
				print("<table border=\"1\"><tr>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objTexte->stpi_getArrTxt("titre6")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjTexte()->stpi_getArrTxt("itemcode")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qte")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjTexte()->stpi_getArrTxt("prix")) . "</td>\n");
				print("<td>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjTexte()->stpi_getArrTxt("desc")) . "</td>\n");
				print("</tr>\n");
				foreach($arrNbSousItemID as $nbSousItemID)
				{
					if ($objCommande->stpi_getObjCommandeSousItem()->stpi_setNbID($objCommande->stpi_getNbID(), $nbSousItemID))
					{
						print("<tr><td>" . $objBdd->stpi_trsBddToHTML($nbSousItemID) . "</td>\n");
						print("<td>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getStrItemCode()) . "</td>\n");
						print("<td>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getNbQte()) . "</td>\n");
						print("<td>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getNbPrix()) . "$</td>\n");
						print("<td><a href=\"./commandesousitem.php?l=" . LG . "&amp;nbCommandeID=" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getNbID()));
						print("&amp;nbSousItemID=" . $objBdd->stpi_trsBddToHTML($nbSousItemID) . "\">" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getStrSousItemDesc()) . "</a></td>");
						print("</tr>\n");
					}
				}
				print("</table>\n");
			}
			print("<h2>" . $objTexte->stpi_getArrTxt("addsousitem") . "</h2>\n");
			$objCommande->stpi_getObjCommandeSousItem()->stpi_affJsAdd();
			$objCommande->stpi_getObjCommandeSousItem()->stpi_getObjItem()->stpi_affJsSelectItem();
			$objCommande->stpi_getObjCommandeSousItem()->stpi_getObjItem()->stpi_affJsSelectSousItem();
			$objCommande->stpi_getObjCommandeSousItem()->stpi_affJsInfoSousItem();
			$objCommande->stpi_getObjCommandeSousItem()->stpi_affAdd();
		}		
	?>
	</div>	
	</body>
</html>
