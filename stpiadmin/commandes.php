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
		print("<a name=\"top\"></a>");
		print("<h1 style=\"display: inline;\">" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		print("<span> - <a href=\"./commandesaff.php?l=" . LG . "\">" . $objTexte->stpi_getArrTxt("voircommandes") . "</a> - <a href=\"./commandesstatistiquesaff.php?l=" . LG . "\">" . $objTexte->stpi_getArrTxt("voirstats") . "</a></span>");
		// menu
		print("<p>\n");
		print("<a href=\"./commandes.php?l=" . LG . "#typecommande\">" . $objTexte->stpi_getArrTxt("titre2") . "</a> - ");
		print("<a href=\"./commandes.php?l=" . LG . "#statutcommande\">" . $objTexte->stpi_getArrTxt("titre3") . "</a> - ");
		print("<a href=\"./commandes.php?l=" . LG . "#methodpaye\">" . $objTexte->stpi_getArrTxt("titre4") . "</a> - ");
		print("<a href=\"./commandes.php?l=" . LG . "#typeadresse\">" . $objTexte->stpi_getArrTxt("titre5") . "</a>");
		print("</p>\n");
		
		print("<h2>" . $objTexte->stpi_getArrTxt("search") . "</h2>\n");
		
		$objCommande->stpi_getObjTypeCommande()->stpi_affJsSearch();
		$objCommande->stpi_getObjTypeCommande()->stpi_affSearch();
		
		$objCommande->stpi_getObjStatutCommande()->stpi_affJsSearch();
		$objCommande->stpi_getObjStatutCommande()->stpi_affSearch();
		$objCommande->stpi_getObjMethodPaye()->stpi_affJsSearch();
		$objCommande->stpi_getObjMethodPaye()->stpi_affSearch();
		
		$objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_affJsSearch();
		$objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_affSearch();
		
		
		print("<a name=\"typecommande\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypecommande") . "</h2>\n");
		$objCommande->stpi_getObjTypeCommande()->stpi_affJsAdd();
		$objCommande->stpi_getObjTypeCommande()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./commandes.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"statutcommande\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addstatutcommande") . "</h2>\n");
		$objCommande->stpi_getObjStatutCommande()->stpi_affJsAdd();
		$objCommande->stpi_getObjStatutCommande()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./commandes.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");

		print("<a name=\"methodpaye\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addmethodpaye") . "</h2>\n");
		$objCommande->stpi_getObjMethodPaye()->stpi_affJsAdd();
		$objCommande->stpi_getObjMethodPaye()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./commandes.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
		print("<a name=\"typeadresse\"></a>");
		print("<h2>" . $objTexte->stpi_getArrTxt("addtypeadresse") . "</h2>\n");
		$objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_affJsAdd();
		$objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_affAdd();
		print("<p style=\"text-align: right;\">\n");
		print("<a href=\"./commandes.php?l=" . LG . "#top\">" . $objTexte->stpi_getArrTxt("top") . "</a>");
		print("</p>\n");
		
	?>
	</div>	
	</body>
</html>