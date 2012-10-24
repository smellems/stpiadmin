<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/event/clsevent.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	$objEvent = new clsevent();
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/events");
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
		if ($objEvent->stpi_setNbID($_GET["nbEventID"]))
		{
			if ($objEvent->stpi_setArrObjEventLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbEventID\" value=\"" . $objEvent->stpi_getNbID() . "\" />\n");
				$objEvent->stpi_affJsDelete();
				$objEvent->stpi_affJsEdit();
				$objEvent->stpi_affEdit();
				
				print("<h1>" . $objTexte->stpi_getArrTxt("titre4") . "</h1>\n");
				if ($arrNbDateHeureID = $objEvent->stpi_selNbDateHeureID())
				{
					print("<table border=\"1\"><tr>\n");
					print("<td>" . $objBdd->stpi_trsBddToHTML($objEvent->stpi_getObjDateHeure()->stpi_getObjTexte()->stpi_getArrTxt("debut")) . "</td>\n");
					print("<td>" . $objBdd->stpi_trsBddToHTML($objEvent->stpi_getObjDateHeure()->stpi_getObjTexte()->stpi_getArrTxt("fin")) . "</td>\n");
					print("</tr>\n");
					foreach($arrNbDateHeureID as $nbDateHeureID)
					{
						if ($objEvent->stpi_getObjDateHeure()->stpi_setNbID($nbDateHeureID))
						{
							print("<tr><td>" . $objBdd->stpi_trsBddToHTML($objEvent->stpi_getObjDateHeure()->stpi_getDtDebut()) . "</td>\n");
							print("<td>" . $objBdd->stpi_trsBddToHTML($objEvent->stpi_getObjDateHeure()->stpi_getDtFin()) . "</td>\n");
							print("<td><a href=\"./eventdateheure.php?l=" . LG . "&amp;nbDateHeureID=" . $objBdd->stpi_trsBddToHTML($nbDateHeureID) . "\">" . "EDIT" . "</a></td>");
							print("</tr>\n");
						}
					}
					print("</table>\n");
				}
				print("<h2>" . $objBdd->stpi_trsBddToHTML($objTexte->stpi_getArrTxt("adddateheure")) . "</h2>\n");
				$objEvent->stpi_getObjDateHeure()->stpi_affJsAdd();
				$objEvent->stpi_getObjDateHeure()->stpi_affAdd();
				
			}
		}			
	?>		
	</div>
	</body>
</html>