<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/news/clsnews.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNews = new clsnews();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/newss");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre4") . "</h1>\n");
		if ($objNews->stpi_getObjTypeNews()->stpi_setNbID($_GET["nbTypeNewsID"]))
		{
			if ($objNews->stpi_getObjTypeNews()->stpi_setArrObjTypeNewsLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbTypeNewsID\" value=\"" . $objNews->stpi_getObjTypeNews()->stpi_getNbID() . "\" />\n");
				$objNews->stpi_getObjTypeNews()->stpi_affJsDelete();
				$objNews->stpi_getObjTypeNews()->stpi_affJsEdit();
				$objNews->stpi_getObjTypeNews()->stpi_affEdit();
			}
		}

		print("<h2>\n");
		print($objTexte->stpi_getArrTxt("titre1"));
		print("</h2>\n");
		if ($arrNbNewsID = $objNews->stpi_getObjTypeNews()->stpi_selNbNewsID())
		{
			foreach ($arrNbNewsID as $nbNewsID)
			{
				if ($objNews->stpi_setNbID($nbNewsID))
				{
					if ($objNews->stpi_setObjNewsLgFromBdd())
					{
						print("<a href=\"./news.php?l=" . LG);
						print("&amp;nbNewsID=" . $objBdd->stpi_trsBddToHTML($nbNewsID) . "\">");
						print($objBdd->stpi_trsBddToHTML($objNews->stpi_getDtEntryDate()) . "</a> : ");
						print($objBdd->stpi_trsBddToHTML($objNews->stpi_getObjNewsLg()->stpi_getStrTitre()) . "<br/>\n");
					}
				}
			}
		}			
	?>		
	</div>
	</body>
</html>
