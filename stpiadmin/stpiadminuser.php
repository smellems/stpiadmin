<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/stpiadminuser/clsstpiadminuser.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSTPIAdminUser = new clsstpiadminuser();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/stpiadminuser");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("user") . "</h1>\n");
		
		if ($objSTPIAdminUser->stpi_setNbID($_GET["nbUserID"]))
		{
			$objSTPIAdminUser->stpi_affJsEdit();
			$objSTPIAdminUser->stpi_affJsDelete();
			$objSTPIAdminUser->stpi_affJsPassReset();
			$objSTPIAdminUser->stpi_affEdit();			
		}		
	?>
	</div>	
	</body>
</html>