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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		$ok = true;
		$ad = 0;
		$af = 0;
		$md = 0;
		$mf = 0;
		$jd = 0;
		$jf = 0;
		if (isset($_POST["nbMoisAnnee"]))
		{
			list($m, $a) = explode("-", $_POST["nbMoisAnnee"]);
			if ($objRegistre->stpi_getObjDate()->stpi_chkStrJour(1, $m, $a))
			{
				$ad = $a;
				$af = $a;
				$md = $m;
				$mf = $m;
				$jd = 1;
				$jf = date('t', mktime(0, 0, 0, $mf, 1, $af));
			}
			else
			{
				$ok = false;
			}
		}
		elseif(isset($_POST["strJourD"]) AND isset($_POST["strMoisD"]) AND isset($_POST["strAnneeD"]) AND isset($_POST["strJourF"]) AND isset($_POST["strMoisF"]) AND isset($_POST["strAnneeF"]))
		{
			if ($objRegistre->stpi_getObjDate()->stpi_chkStrAnnee($_POST["strAnneeD"]))
			{
				$ad = $_POST["strAnneeD"];
			}
			if ($objRegistre->stpi_getObjDate()->stpi_chkStrMois($_POST["strMoisD"]))
			{
				$md = $_POST["strMoisD"];
			}
			if ($objRegistre->stpi_getObjDate()->stpi_chkStrJour($_POST["strJourD"], $_POST["strMoisD"], $_POST["strAnneeD"]))
			{
				$jd = $_POST["strJourD"];
			}
			
			if ($objRegistre->stpi_getObjDate()->stpi_chkStrAnnee($_POST["strAnneeF"]))
			{
				$af = $_POST["strAnneeF"];
			}
			if ($objRegistre->stpi_getObjDate()->stpi_chkStrMois($_POST["strMoisF"]))
			{
				$mf = $_POST["strMoisF"];
			}
			if ($objRegistre->stpi_getObjDate()->stpi_chkStrJour($_POST["strJourF"], $_POST["strMoisF"], $_POST["strAnneeF"]))
			{
				$jf = $_POST["strJourF"];
			}

			if ($ad == 0 OR $md == 0 OR $jd == 0 OR $af == 0 OR $mf == 0 OR $jf == 0)
			{
				$ok = false;
			}
		}
		else
		{
			$ok = false;
		}
		
		print("<form id=\"f1\" name=\"f1\" method=\"post\" action=\"./registresaff.php?l=" . LG . "\">\n");
		print("<table><tr><td>" . $objTexte->stpi_getArrTxt("mois") . ":</td>\n<td><select id=\"nbMoisAnnee\" name=\"nbMoisAnnee\"><option value=\"0-0000\">-</option>\n");
		
		if ($arrMoisAnnee = $objRegistre->stpi_selAllMois())
		{
			foreach($arrMoisAnnee as $strMoisAnnee)
			{
				list($m, $a) = explode("-", $strMoisAnnee);
				print("<option value=\"" . $strMoisAnnee . "\"");
				if ($mf == $m AND $af == $a)
				{
					print(" selected=\"selected\"");
				}
				print(">" . $strMoisAnnee . "</option>\n");
			}				
		}
		print("</select></td>\n<td><input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("search") . "\"/></td></tr></table>\n");
		print("</form>\n");
		print("<br/>");
		
		print("<form id=\"f2\" name=\"f2\" method=\"post\" action=\"./registresaff.php?l=" . LG . "\">\n");
		print("<table><tr><td colspan=\"6\"><b>" . $objTexte->stpi_getArrTxt("debut") . "</b></td><td colspan=\"6\">&nbsp;&nbsp;<b>" . $objTexte->stpi_getArrTxt("fin") . "</b></td><td>&nbsp;</td></tr>");
		
		// Date début
		print("<tr><td>" . $objTexte->stpi_getArrTxt("jour") . ":</td>\n");
		print("<td><input type=\"text\" maxlength=\"2\" size=\"2\" id=\"strJourD\" name=\"strJourD\" value=\"" . $jd . "\" /></td>\n");
		print("<td>" . $objTexte->stpi_getArrTxt("mois") . ":</td>\n");
		print("<td><select id=\"strMoisD\" name=\"strMoisD\">\n");
		for($x = 1; $x < 13; $x++)
		{
			print("<option value=\"" . $x . "\"");
			if ($md == $x)
			{
				print(" selected=\"selected\"");
			}
			print(">" . $x . "</option>\n");
		}
		print("</select></td>\n");
		print("<td>" . $objTexte->stpi_getArrTxt("annee") . ":</td>\n");
		print("<td><input type=\"text\" maxlength=\"4\" size=\"4\" id=\"strAnneeD\" name=\"strAnneeD\" value=\"" . $ad . "\" /></td>\n");
		
		// Date fin
		print("<td>&nbsp;&nbsp;" . $objTexte->stpi_getArrTxt("jour") . ":</td>\n");
		print("<td><input type=\"text\" maxlength=\"2\" size=\"2\" id=\"strJourF\" name=\"strJourF\" value=\"" . $jf . "\" /></td>\n");
		print("<td>" . $objTexte->stpi_getArrTxt("mois") . ":</td>\n");
		print("<td><select id=\"strMoisF\" name=\"strMoisF\">\n");
		for($x = 1; $x < 13; $x++)
		{
			print("<option value=\"" . $x . "\"");
			if ($mf == $x)
			{
				print(" selected=\"selected\"");
			}
			print(">" . $x . "</option>\n");
		}
		print("</select></td>\n");
		print("<td>" . $objTexte->stpi_getArrTxt("annee")  . ":</td>\n");
		print("<td><input type=\"text\" maxlength=\"4\" size=\"4\" id=\"strAnneeF\" name=\"strAnneeF\" value=\"" . $af . "\" /></td>\n");
		
		print("<td><input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("search") . "\"/></td></tr></table>\n");
		print("</form>\n");
		
		$arrNbRegistreID = array();
		if (!$ok)
		{
			print("<h2>50 " . $objTexte->stpi_getArrTxt("lastregistres") . "</h2>\n");
			$ok = true;
			if (!$arrNbRegistreID = $objRegistre->stpi_selAll(50))
			{
				$ok = false;
			}
		}
		else
		{
			print("<h2>" . $objTexte->stpi_getArrTxt("titre1") . " " . $objTexte->stpi_getArrTxt("de") . " " . $ad . "-" . $md . "-" . $jd);
			print(" " . $objTexte->stpi_getArrTxt("au") . " " . $af . "-" . $mf . "-" . $jf . "</h2>\n");
			if (!$arrNbRegistreID = $objRegistre->stpi_selAll(NULL, $ad, $md, $jd, $af, $mf, $jf))
			{
				$ok = false;
			}
		}
		if ($ok)
		{
			print("<table border=\"1\"><tr>\n");
			print("<td>" . $objRegistre->stpi_getObjTexte()->stpi_getArrTxt("date") . "</td>\n");
			print("<td>" . $objRegistre->stpi_getObjTexte()->stpi_getArrTxt("registre") . "#</td>\n");
			print("<td>" . $objRegistre->stpi_getObjTexte()->stpi_getArrTxt("registrecode") . "</td>\n");
			print("<td>" . $objRegistre->stpi_getObjTexte()->stpi_getArrTxt("client") . "</td>\n");
			print("</tr>\n");
			foreach($arrNbRegistreID as $nbRegistreID)
			{
				if ($objRegistre->stpi_setNbID($nbRegistreID))
				{
					print("<tr><td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getDtEntryDate()) . "</td>\n");
					print("<td><a href=\"./registre.php?l=" . LG . "&amp;nbRegistreID=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbID()) . "\">" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbID()) . "</a></td>\n");
					print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . "</td>\n");
					print("<td>" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbClientID()) . "</td>\n");
					print("</tr>\n");
				}
			}
			print("</table>\n");
		}
	?>
	</div>	
	</body>
</html>
