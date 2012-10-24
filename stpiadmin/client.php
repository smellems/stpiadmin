<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/client/clsclient.php");
	require_once("./includes/classes/commande/clscommande.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objClient = new clsclient();
	$objCommande = new clscommande();
	$objRegistre =& $objCommande->stpi_getObjRegistre();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/client");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("clients") . "</h1>\n");
		
		if ($objClient->stpi_setNbID($_GET["nbClientID"]))
		{
			$objClient->stpi_affJsEdit();
			$objClient->stpi_affJsDelete();
			$objClient->stpi_affJsPassReset();
			$objClient->stpi_affEdit();	

			if ($arrNbCommandeID = $objClient->stpi_selNbCommandeID())
			{
				print("<h2>\n");
				print($objTexte->stpi_getArrTxt("orders"));
				print("</h2>\n");
				
				print("<ul>\n");
				foreach ($arrNbCommandeID as $nbCommandeID)
				{
					if ($objCommande->stpi_setNbID($nbCommandeID))
					{
						print("<li>\n");
						print("<a href=\"./commande.php?l=" . LG . "&amp;nbCommandeID=" . $objBdd->stpi_trsBddToHTML($nbCommandeID) . "\" >");
						print($objBdd->stpi_trsBddToHTML($objCommande->stpi_getDtEntryDate()));
						print("</a>\n");
						print(" : " . $objBdd->stpi_trsBddToHTML($objCommande->stpi_selNbSousTotal()) . " $");
						print("</li>\n");
					}
				}
				print("</ul>\n");
			}
			
			print("<h2>\n");
			print($objTexte->stpi_getArrTxt("giftlists"));
			print("</h2>\n");
			
			if ($arrNbRegistreID = $objClient->stpi_selNbRegistreID())
			{
				print("<ul>\n");
				foreach ($arrNbRegistreID as $nbRegistreID)
				{
					if ($objRegistre->stpi_setNbID($nbRegistreID))
					{
						print("<li>\n");
						print($objBdd->stpi_trsBddToHTML($objRegistre->stpi_getDtFin()));
						print(" : ");
						if ($objRegistre->stpi_getBoolActif())
						{
							print("(" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjTexte()->stpi_getArrTxt("actif")) . ") ");
						}
						print($objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()));
						// print(" <a href=\"./registrepublic.php?l=" . LG . "&amp;nbRegistreID=" . $objBdd->stpi_trsBddToHTML($nbRegistreID) . "\" >");
						// print($objRegistre->stpi_getObjTexte()->stpi_getArrTxt("edit"));
						// print("</a>");
						print("</li>\n");
					}
				}
				print("</ul>\n");
			}
		}		
	?>
	</div>	
	</body>
</html>