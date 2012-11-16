<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	require_once("./stpiadmin/includes/classes/page/clspagepublic.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/checkout");
	$objBody = new clsbody();
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	$objLock->stpi_pageNotEncrypted();

	$objPage = new clspagepublic();
	$objPage->stpi_setNbID(2);
	$objPage->stpi_setObjPageLgFromBdd();
	$objPageLg = $objPage->stpi_getObjPageLg();
	$objHead = new clshead(STR_NOM_ENT . " - " . $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrTitre()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrKeywords()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrDesc()));
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objPageLg->stpi_getStrTitre());

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();
	print($objBdd->stpi_trsBddHtmlToHTML($objPageLg->stpi_getStrContent()));
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
