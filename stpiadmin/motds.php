<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/motd/clsmotd.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/motds");
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
		if ($arrMotdID = $objMotd->stpi_selAll())
		{
			print("<p>");
			foreach ($arrMotdID as $nbMotdID)
			{
				if ($objMotd->stpi_setNbID($nbMotdID))
				{
					if ($objMotd->stpi_setObjMotdLgFromBdd());
					{
						print("<a href=\"./motd.php?l=" . $_GET["l"]);
						print("&amp;nbMotdID=" . $objBdd->stpi_trsBddToHTML($objMotd->stpi_getNbID()) . "\">");
						print($objBdd->stpi_trsBddToHTML($objMotd->stpi_getDtEntryDate()) . "</a> : ");
						if ($objMotd->stpi_getboolRouge())
						{
							print("<span style=\"color:#FF0000;\">");
						}
						print($objBdd->stpi_trsBddToHTML($objMotd->stpi_getObjMotdLg()->stpi_getStrMotd()));
						if ($objMotd->stpi_getboolRouge())
						{
							print("</span>\n");
						}
						print("<br/>\n");
					}
				}
			}
			print("</p>");
		}

		print("<h2>" . $objTexte->stpi_getArrTxt("titre3") . "</h2>\n");

		$objMotd->stpi_affJsAdd();
		$objMotd->stpi_affAdd();
	?>
	</div>	
	</body>
</html>