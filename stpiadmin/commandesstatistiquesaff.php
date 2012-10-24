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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre7") . "</h1>\n");
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
			if ($objCommande->stpi_getObjDate()->stpi_chkStrJour(1, $m, $a))
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
			if ($objCommande->stpi_getObjDate()->stpi_chkStrAnnee($_POST["strAnneeD"]))
			{
				$ad = $_POST["strAnneeD"];
			}
			if ($objCommande->stpi_getObjDate()->stpi_chkStrMois($_POST["strMoisD"]))
			{
				$md = $_POST["strMoisD"];
			}
			if ($objCommande->stpi_getObjDate()->stpi_chkStrJour($_POST["strJourD"], $_POST["strMoisD"], $_POST["strAnneeD"]))
			{
				$jd = $_POST["strJourD"];
			}
			
			if ($objCommande->stpi_getObjDate()->stpi_chkStrAnnee($_POST["strAnneeF"]))
			{
				$af = $_POST["strAnneeF"];
			}
			if ($objCommande->stpi_getObjDate()->stpi_chkStrMois($_POST["strMoisF"]))
			{
				$mf = $_POST["strMoisF"];
			}
			if ($objCommande->stpi_getObjDate()->stpi_chkStrJour($_POST["strJourF"], $_POST["strMoisF"], $_POST["strAnneeF"]))
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
		
		print("<form id=\"f1\" name=\"f1\" method=\"post\" action=\"./commandesstatistiquesaff.php?l=" . LG . "\">\n");
		print("<table><tr><td>" . $objTexte->stpi_getArrTxt("mois") . ":</td>\n<td><select id=\"nbMoisAnnee\" name=\"nbMoisAnnee\"><option value=\"0-0000\">-</option>\n");
		
		if ($arrMoisAnnee = $objCommande->stpi_selAllMois())
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
		
		print("<form id=\"f2\" name=\"f2\" method=\"post\" action=\"./commandesstatistiquesaff.php?l=" . LG . "\">\n");
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

		if ($ok)
		{
			$SQL2 = "SELECT DISTINCT nbTypeCommandeID";
			$SQL2 .= " FROM stpi_commande_Commande";
			$SQL2 .= " WHERE DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
			$SQL2 .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
			if ($result2 = $objBdd->stpi_select($SQL2))
			{
				while($row2 = mysql_fetch_assoc($result2))
				{
					if ($objCommande->stpi_getObjTypeCommande()->stpi_setNbID($row2["nbTypeCommandeID"]))
					{
						if ($objCommande->stpi_getObjTypeCommande()->stpi_setObjTypeCommandeLgFromBdd())
						{
							$SQL3 = "SELECT DISTINCT nbStatutCommandeID";
							$SQL3 .= " FROM stpi_commande_Commande";
							$SQL3 .= " WHERE nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
							$SQL3 .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
							$SQL3 .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
							if ($result3 = $objBdd->stpi_select($SQL3))
							{
								while($row3 = mysql_fetch_assoc($result3))
								{
									if ($objCommande->stpi_getObjStatutCommande()->stpi_setNbID($row3["nbStatutCommandeID"]))
									{
										if ($objCommande->stpi_getObjStatutCommande()->stpi_setObjStatutCommandeLgFromBdd())
										{
											print("<h2>" . $objTexte->stpi_getArrTxt("titre7") . " " . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjStatutCommande()->stpi_getObjStatutCommandeLg()->stpi_getStrName()) . " (" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjTypeCommande()->stpi_getObjTypeCommandeLg()->stpi_getStrName()) . ") " . $objTexte->stpi_getArrTxt("de") . " " . $ad . "-" . $md . "-" . $jd);
											print(" " . $objTexte->stpi_getArrTxt("au") . " " . $af . "-" . $mf . "-" . $jf . "</h2>\n");
											
											$nbSousTotal = 0;
											$nbPrixShipping = 0;
											$nbPrixRabais = 0;
											$nbPrixTaxes = 0;
											print("<table><tr><td>" . $objTexte->stpi_getArrTxt("soustotal") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT SUM(nbPrix*nbQte) as nbSousTotal";
											$SQL .= " FROM stpi_commande_Commande_SousItem, stpi_commande_Commande";
											$SQL .= " WHERE stpi_commande_Commande.nbCommandeID=stpi_commande_Commande_SousItem.nbCommandeID";
											$SQL .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													$nbSousTotal = $row["nbSousTotal"];
													print($objBdd->stpi_trsBddToHTML($nbSousTotal) . "$");
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>" . $objTexte->stpi_getArrTxt("rabais") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT SUM(nbPrixRabais) as nbPrixRabais";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													$nbPrixRabais = $row["nbPrixRabais"];
													print($objBdd->stpi_trsBddToHTML($nbPrixRabais) . "$");
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>" . $objTexte->stpi_getArrTxt("shipping") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT SUM(nbPrixShipping) as nbPrixShipping";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													$nbPrixShipping = $row["nbPrixShipping"];
													print($objBdd->stpi_trsBddToHTML($nbPrixShipping) . "$");
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>" . $objTexte->stpi_getArrTxt("taxes") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT SUM(nbPrixTaxes) as nbPrixTaxes";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													$nbPrixTaxes = $row["nbPrixTaxes"];
													print($objBdd->stpi_trsBddToHTML($nbPrixTaxes) . "$");
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>" . $objTexte->stpi_getArrTxt("total") . "</td>\n<td style=\"text-align: right;\">");
											print($nbSousTotal - $nbPrixRabais + $nbPrixShipping + $nbPrixTaxes . "$");
											print("</td></tr></table>\n");
											
											print("<table><tr><td>" . $objTexte->stpi_getArrTxt("nbcommandes") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT count(*) as nbCommandes";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													print($objBdd->stpi_trsBddToHTML($row["nbCommandes"]));
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>&nbsp;&nbsp;" . $objTexte->stpi_getArrTxt("nbavecclient") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT count(*) as nbCommandes";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbClientID!=0";
											$SQL .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													print($objBdd->stpi_trsBddToHTML($row["nbCommandes"]));
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>&nbsp;&nbsp;" . $objTexte->stpi_getArrTxt("nbpourregistre") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT count(*) as nbCommandes";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbRegistreID!=0";
											$SQL .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													print($objBdd->stpi_trsBddToHTML($row["nbCommandes"]));
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>&nbsp;&nbsp;" . $objTexte->stpi_getArrTxt("nbavecinfocarte") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT count(*) as nbCommandes";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbInfoCarteID!=0";
											$SQL .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													print($objBdd->stpi_trsBddToHTML($row["nbCommandes"]));
												}
												mysql_free_result($result);
											}
											
											print("</td></tr><tr><td>" . $objTexte->stpi_getArrTxt("nbsousitems") . "</td>\n<td style=\"text-align: right;\">");
											$SQL = "SELECT SUM(nbQte) as nbSousItems";
											$SQL .= " FROM stpi_commande_Commande_SousItem, stpi_commande_Commande";
											$SQL .= " WHERE stpi_commande_Commande.nbCommandeID=stpi_commande_Commande_SousItem.nbCommandeID";
											$SQL .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												if ($row = mysql_fetch_assoc($result))
												{
													print($objBdd->stpi_trsBddToHTML($row["nbSousItems"]));
												}
												mysql_free_result($result);
											}
											print("</td></tr></table>\n");
											
											
											print("<p><b>" . $objTexte->stpi_getArrTxt("bestseller") . "</b></p>\n");
											$SQL = "SELECT nbSousItemID, COUNT(*)*nbQte AS nbCommande";
											$SQL .= " FROM stpi_commande_Commande_SousItem, stpi_commande_Commande";
											$SQL .= " WHERE stpi_commande_Commande.nbCommandeID=stpi_commande_Commande_SousItem.nbCommandeID";
											$SQL .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											$SQL .= " GROUP BY nbSousItemID";
											$SQL .= " ORDER BY nbCommande DESC";
											if ($result = $objBdd->stpi_select($SQL))
											{
												print("<table border=\"1\"><tr>\n");
												print("<td>" . $objTexte->stpi_getArrTxt("sousitemid") . "</td>\n");
												print("<td>" . $objTexte->stpi_getArrTxt("achats") . "</td>\n");
												print("<td>" . $objTexte->stpi_getArrTxt("desc") . "</td>\n");
												print("</tr>\n");
												while($row = mysql_fetch_assoc($result))
												{
													print("<tr><td style=\"text-align: right;\"><a target=\"_blank\" href=\"./itemsousitem.php?l=" . LG . "&amp;nbSousItemID=" . $objBdd->stpi_trsBddToHTML($row["nbSousItemID"]) . "\">" . $objBdd->stpi_trsBddToHTML($row["nbSousItemID"]) . "</a></td>\n");
													print("<td style=\"text-align: right;\">" . $objBdd->stpi_trsBddToHTML($row["nbCommande"]) . "</td>\n");
													print("<td>");
													if ($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjItem()->stpi_getObjSousItem()->stpi_setNbID($row["nbSousItemID"]))
													{
														if ($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjItem()->stpi_setNbID($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjItem()->stpi_getObjSousItem()->stpi_getNbItemID()))
														{
															print($objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjCommandeSousItem()->stpi_getObjItem()->stpi_getStrSousItemDesc()) . "</td>\n");
														}
													}
													print("</td>\n");
													print("</tr>");
												}
												print("</table>\n");
												mysql_free_result($result);
											}
											
											
											print("<p><b>" . $objTexte->stpi_getArrTxt("methodpaye") . "</b></p>\n");
											$SQL = "SELECT nbMethodPayeID, COUNT(*) AS nbCommande";
											$SQL .= " FROM stpi_commande_Commande";
											$SQL .= " WHERE nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											$SQL .= " GROUP BY nbMethodPayeID";
											$SQL .= " ORDER BY nbCommande DESC";
											if ($result = $objBdd->stpi_select($SQL))
											{
												print("<table border=\"1\"><tr>\n");
												print("<td>" . $objTexte->stpi_getArrTxt("methodpaye") . "</td>\n");
												print("<td>" . $objTexte->stpi_getArrTxt("achats") . "</td>\n");
												print("</tr>\n");
												while($row = mysql_fetch_assoc($result))
												{
													print("<tr><td>\n");
													if ($objCommande->stpi_getObjMethodPaye()->stpi_setNbID($row["nbMethodPayeID"]))
													{
														if ($objCommande->stpi_getObjMethodPaye()->stpi_setObjMethodPayeLgFromBdd())
														{
															print($objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjMethodPaye()->stpi_getObjMethodPayeLg()->stpi_getStrName()));
														}
													}
													print("</td><td style=\"text-align: right;\">" . $objBdd->stpi_trsBddToHTML($row["nbCommande"]) . "</td>\n");
													print("</tr>");
												}
												print("</table>\n");
												mysql_free_result($result);
											}
											
											
											$SQL = "SELECT DISTINCT stpi_commande_TypeAdresse.nbTypeAdresseID";
											$SQL .= " FROM stpi_commande_TypeAdresse, stpi_commande_Commande, stpi_commande_Adresse";
											$SQL .= " WHERE stpi_commande_TypeAdresse.nbTypeAdresseID=stpi_commande_Adresse.nbTypeAdresseID";
											$SQL .= " AND stpi_commande_Adresse.nbCommandeID=stpi_commande_Commande.nbCommandeID";
											$SQL .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
											$SQL .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
											$SQL .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
											$SQL .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
											if ($result = $objBdd->stpi_select($SQL))
											{
												while($row = mysql_fetch_assoc($result))
												{
													if ($objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_setNbID($row["nbTypeAdresseID"]))
													{
														if ($objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_setObjTypeAdresseLgFromBdd())
														{
															print("<p><b>" . $objTexte->stpi_getArrTxt("nbcommandespays") . " (" . $objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_getObjTypeAdresseLg()->stpi_getStrName(). ")</b></p>\n");
															$SQL1 = "SELECT strCountryID, strProvinceID, COUNT(*) AS nbCommande";
															$SQL1 .= " FROM stpi_commande_Commande, stpi_commande_Adresse";
															$SQL1 .= " WHERE stpi_commande_Commande.nbCommandeID=stpi_commande_Adresse.nbCommandeID";
															$SQL1 .= " AND nbTypeAdresseID=" . $row["nbTypeAdresseID"];
															$SQL1 .= " AND nbTypeCommandeID=" . $row2["nbTypeCommandeID"];
															$SQL1 .= " AND nbStatutCommandeID=" . $row3["nbStatutCommandeID"];
															$SQL1 .= " AND DATE(dtEntryDate)>='" . $ad . "-" . $md . "-" . $jd . "'";
															$SQL1 .= " AND DATE(dtEntryDate)<='" . $af . "-" . $mf . "-" . $jf . "'";
															$SQL1 .= " GROUP BY strCountryID, strProvinceID";
															$SQL1 .= " ORDER BY nbCommande DESC";
															if ($result1 = $objBdd->stpi_select($SQL1))
															{
																print("<table border=\"1\"><tr>\n");
																print("<td>" . $objTexte->stpi_getArrTxt("pays") . "</td>\n");
																print("<td>" . $objTexte->stpi_getArrTxt("province") . "</td>\n");
																print("<td>" . $objTexte->stpi_getArrTxt("achats") . "</td>\n");
																print("</tr>\n");
																while($row1 = mysql_fetch_assoc($result1))
																{
																	print("<tr><td>");
																	if ($objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_setStrID($row1["strCountryID"]))
																	{
																		if ($objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_setObjCountryLgFromBdd())
																		{
																			print($objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_getObjCountryLg()->stpi_getStrName()) . "</td><td>\n");
																			
																			if ($row1["strProvinceID"] AND $objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_getObjProvince()->stpi_setStrID($row1["strProvinceID"], $row1["strCountryID"]))
																			{
																				if ($objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_getObjProvince()->stpi_setObjProvinceLgFromBdd())
																				{
																					print($objBdd->stpi_trsBddToHTML($objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_getStrName()));
																				}
																			}
																			print("</td><td style=\"text-align: right;\">\n");
																		}
																	}
																	print($objBdd->stpi_trsBddToHTML($row1["nbCommande"]) . "</td>\n");
																	print("</tr>");
																}
																print("</table>\n");
																mysql_free_result($result1);
															}
														}
													}
												}
											}
											print("<br/><br/>");
										}
									}
								}
							}							
						}
					}
				}
			}
		}
	?>
	</div>	
	</body>
</html>