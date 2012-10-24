<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/commande/clscommande.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	require_once("./stpiadmin/includes/classes/client/clsclient.php");
	require_once("./stpiadmin/includes/classes/ship/clszone.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/checkout");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageEncrypted();
	
	$objCommande = new clscommande();
	$objCommandeSession = new clscommandesession();
	$objZone = new clszone();
	$objClientStore = new clsclient(1);
	$objClient =& $objCommande->stpi_getObjClient();
	$objCommandeSousItem =& $objCommande->stpi_getObjCommandeSousItem();
	$objMethodePaye =& $objCommande->stpi_getObjMethodPaye();
	$objItem =& $objCommandeSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objPrix =& $objSousItem->stpi_getObjPrix();
	$objRegistre =& $objCommande->stpi_getObjRegistre();
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php
		$objHead->stpi_affPublicHead();
	?>
	</head>
	<body>
	
	<div id="header">
		<div id="menulang">
			<?php
				$objMenu->stpi_affPublicMenuLang();
			?>
		</div>		
		<div id="loginurl">
			<?php
				$objLock->stpi_affUrl();
			?>
		</div>		
		<div id="cart"><?php $objBody->stpi_affCartUrl();  ?></div>
		
		<div id="welcomemsg">
			<?php
				print($objTexte->stpi_getArrTxt("welcome"));
			?>
		</div>				
	</div>
	
	<div id="topmenu">
		<?php
			$objMenu->stpi_affPublicMenu();
		?>		
	</div>
	
	<div id="container">		
		<div id="fullcontent">
		<?php
			$objJavaScript->stpi_affArrLang();
			$objJavaScript->stpi_affCreateXmlHttp();
			$objJavaScript->stpi_affNoAjax();
			
			$objJavaScript->stpi_affNoJavaScript();
			
			$objMotd->stpi_affPublic();
			
			$objCommande->stpi_affJsAddRegistrePublic();
			$objMethodePaye->stpi_affJsSelectMethodPaye();
			
			print("<h2>" . $objTexte->stpi_getArrTxt("checkout4") . "</h2>\n");
			
			if (isset($_SESSION["stpiObjCommandeRegistreSession"]))
			{
				if ($objCommandeSession = $objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession())
				{
					if ($objRegistre->stpi_setNbID($objCommandeSession->stpi_getNbRegistreID()))
					{
						$boolShipZoneTaxable = false;
						
						if ($arrObjAdresseSession = $objCommandeSession->stpi_getArrObjAdresseSession())
						{
							if (isset($arrObjAdresseSession[1]) && isset($arrObjAdresseSession[2]))
							{
								$objAdresseFacturation = new clsadresse();
								$objAdresseLivraison = new clsadresse();
								
								$objAdresseFacturation->stpi_setNbTypeAdresseID(1);
								$objAdresseFacturation->stpi_setStrNom($arrObjAdresseSession[1]->stpi_getStrNom());
								$objAdresseFacturation->stpi_setStrPrenom($arrObjAdresseSession[1]->stpi_getStrPrenom());
								$objAdresseFacturation->stpi_setStrCie($arrObjAdresseSession[1]->stpi_getStrCie());
								$objAdresseFacturation->stpi_setStrAdresse($arrObjAdresseSession[1]->stpi_getStrAdresse());
								$objAdresseFacturation->stpi_setStrVille($arrObjAdresseSession[1]->stpi_getStrVille());
								$objAdresseFacturation->stpi_setStrProvinceID($arrObjAdresseSession[1]->stpi_getStrProvinceID());
								$objAdresseFacturation->stpi_setStrCountryID($arrObjAdresseSession[1]->stpi_getStrCountryID());
								$objAdresseFacturation->stpi_setStrCodePostal($arrObjAdresseSession[1]->stpi_getStrCodePostal());
								
								$objAdresseLivraison->stpi_setNbTypeAdresseID(2);
								$objAdresseLivraison->stpi_setStrNom($arrObjAdresseSession[2]->stpi_getStrNom());
								$objAdresseLivraison->stpi_setStrPrenom($arrObjAdresseSession[2]->stpi_getStrPrenom());
								$objAdresseLivraison->stpi_setStrCie($arrObjAdresseSession[2]->stpi_getStrCie());
								$objAdresseLivraison->stpi_setStrAdresse($arrObjAdresseSession[2]->stpi_getStrAdresse());
								$objAdresseLivraison->stpi_setStrVille($arrObjAdresseSession[2]->stpi_getStrVille());
								$objAdresseLivraison->stpi_setStrProvinceID($arrObjAdresseSession[2]->stpi_getStrProvinceID());
								$objAdresseLivraison->stpi_setStrCountryID($arrObjAdresseSession[2]->stpi_getStrCountryID());
								$objAdresseLivraison->stpi_setStrCodePostal($arrObjAdresseSession[2]->stpi_getStrCodePostal());
								
								$arrObjAdresse = array();
								
								$arrObjAdresse[1] = $objAdresseFacturation;
								$arrObjAdresse[2] = $objAdresseLivraison;
								
								$objCommande->stpi_setArrObjAdresse($arrObjAdresse);
								
								print("<table style=\"padding: 10px;\" >\n");
								if ($objAdresseFacturation->stpi_getStrAdresse() == $objAdresseLivraison->stpi_getStrAdresse() && $objAdresseFacturation->stpi_getStrCodePostal() == $objAdresseLivraison->stpi_getStrCodePostal())
								{
									print("<tr>\n");
									print("<td style=\"padding: 0px 10px; text-align: center;\" >\n");
									print("<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("billingdelivery") . "</h3>");
									print("</td>\n");
									print("</tr>\n");
									print("<tr>\n");
									print("<td style=\"padding: 0px 10px; text-align: center;\" >\n");
									$strCie = $objAdresseFacturation->stpi_getStrCie();
									if (!empty($strCie))
									{
										print($objBody->stpi_trsInputToHTML($strCie) . "<br/>\n");
									}
									print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrPrenom()) . " " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrNom())  . "<br/>\n");
									print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrAdresse()) . "<br/>\n");
									$strProvinceID = $objAdresseFacturation->stpi_getStrProvinceID();
									if(empty($strProvinceID))
									{											
										print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
									}
									else
									{
										print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrProvinceID()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
									}
									print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCodePostal()) . "<br/>\n");
									print("</td>\n");
									print("</tr>\n");												
								}
								else
								{
									if ($objClient->stpi_setNbID($objRegistre->stpi_getNbClientID()))
									{
										print("<tr>\n");
										print("<td style=\"padding: 0px 10px; text-align: center;\" >\n");
										print("<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("billing") . "</h3>");
										print("</td>\n");
										
										print("<td style=\"padding: 0px 10px; text-align: center;\" >\n");
										if ($objAdresseLivraison->stpi_getStrAdresse() == $objClientStore->stpi_getStrAdresse() && $objAdresseLivraison->stpi_getStrCodePostal() == $objClientStore->stpi_getStrCodePostal())
										{
											print("<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("instorepickup") . "</h3>");
										}
										elseif ($objAdresseLivraison->stpi_getStrAdresse() == $objClient->stpi_getStrAdresse() && $objAdresseLivraison->stpi_getStrCodePostal() == $objClient->stpi_getStrCodePostal())
										{
											print("<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("delivertogiftlistowner") . "</h3>");
										}
										else
										{
											print("<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("delivery") . "</h3>");
										}
										print("</td>\n");
										print("</tr>\n");
										print("<tr>\n");
										print("<td style=\"padding: 0px 10px; text-align: center;\" >\n");
										$strCie = $objAdresseFacturation->stpi_getStrCie();
										if (!empty($strCie))
										{
											print($objBody->stpi_trsInputToHTML($strCie) . "<br/>\n");
										}
										print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrPrenom()) . " " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrNom())  . "<br/>\n");
										print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrAdresse()) . "<br/>\n");
										$strProvinceID = $objAdresseFacturation->stpi_getStrProvinceID();
										if(empty($strProvinceID))
										{											
											print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
										}
										else
										{
											print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrProvinceID()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
										}
										print($objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCodePostal()) . "<br/>\n");
										print("</td>\n");
										print("<td style=\"padding: 0px 10px; text-align: center;\" >\n");
										if ($objAdresseLivraison->stpi_getStrAdresse() != $objClient->stpi_getStrAdresse() || $objAdresseLivraison->stpi_getStrCodePostal() != $objClient->stpi_getStrCodePostal())
										{
											$strCie = $objAdresseLivraison->stpi_getStrCie();
											if (!empty($strCie))
											{
												print($objBody->stpi_trsInputToHTML($strCie) . "<br/>\n");
											}
											if ($objAdresseLivraison->stpi_getStrAdresse() != $objClientStore->stpi_getStrAdresse() || $objAdresseLivraison->stpi_getStrCodePostal() != $objClientStore->stpi_getStrCodePostal())
											{
												print($objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrPrenom()) . " " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrNom())  . "<br/>\n");
											}
											print($objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrAdresse()) . "<br/>\n");
											$strProvinceID = $objAdresseLivraison->stpi_getStrProvinceID();
											if(empty($strProvinceID))
											{											
												print($objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrCountryID()) . "<br/>\n");
											}
											else
											{
												print($objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrProvinceID()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrCountryID()) . "<br/>\n");
											}
											print($objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrCodePostal()) . "<br/>\n");
										}
										else
										{
											print("&nbsp;\n");
										}
										print("</td>\n");
										print("</tr>\n");
									}
								}
								print("</table>\n");
								print("<p><a href=\"checkoutregistre2.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("backto2") . "</a><br/>");
								print("<a href=\"checkoutregistre3.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("backto3") . "</a></p>");						
							}	
						}
									
						if ($arrObjCommandeSousItemSession = $objCommandeSession->stpi_getArrObjCommandeSousItemSession())
						{
							if(!empty($arrObjCommandeSousItemSession))
							{
								print("<br/><br/>\n");
								print("<h3>" . $objTexte->stpi_getArrTxt("checkout1") . "</h3>\n");
								
								print("<table width=\"100%\" style=\"padding: 10px;\" >\n");
								print("<tr>\n");
								print("<td style=\"text-align: left;\" >\n");
								print("<h3 style=\"padding: 0px;\" >\n");
								print($objCommandeSousItem->stpi_getObjTexte()->stpi_getArrTxt("desc"));
								print("</h3>\n");
								print("</td>\n");
								print("<td style=\"text-align: left;\" >\n");
								print("<h3 style=\"padding: 0px;\" >\n");
								print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("quantityprice"));
								print("</h3>\n");
								print("</td>\n");
								print("<td style=\"text-align: right;\" >\n");
								print("<h3 style=\"padding: 0px; text-align: right;\" >\n");
								print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("total"));
								print("</h3>\n");
								print("</td>\n");
								print("</tr>\n");
								
								$arrObjCommandeSousItem = array();
								
								foreach ($arrObjCommandeSousItemSession as $objCommandeSousItemSession)
								{
									if ($objSousItem->stpi_setNbID($objCommandeSousItemSession->stpi_getNbSousItemID()))
									{
										$objCommandeSousItemNew = new clscommandesousitem();
										$objCommandeSousItemNew->stpi_setNbSousItemID($objSousItem->stpi_getNbID());
										$objCommandeSousItemNew->stpi_setStrItemCode($objSousItem->stpi_getStrItemCode);
										if ($objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 2))
										{
											$objCommandeSousItemNew->stpi_setNbPrix($objPrix->stpi_getNbPrix());	
										}
										$objCommandeSousItemNew->stpi_setNbQte($objCommandeSousItemSession->stpi_getNbQte());
										if ($objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
										{
											$objCommandeSousItemNew->stpi_setStrSousItemDesc($objItem->stpi_getStrSousItemDesc());
										}									
										$arrObjCommandeSousItem[$objSousItem->stpi_getNbID()] = $objCommandeSousItemNew;
									}
								}
								
								$objCommande->stpi_setArrObjCommandeSousItem($arrObjCommandeSousItem);
							
								foreach ($arrObjCommandeSousItem as $objCommandeSousItem)
								{
									$strArrSousItem .= ", " . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbSousItemID());
									
									print("<tr>\n");
									print("<td style=\"text-align: left;\" >\n");
									$strItemCode = $objCommandeSousItem->stpi_getStrItemCode();
									if (empty($strItemCode))
									{
										print($objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getStrSousItemDesc()));
									}
									else
									{
										print($objBdd->stpi_trsBddToHTML($strItemCode) .  " - " . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getStrSousItemDesc()));
									}
									print("</td>\n");
									print("<td style=\"text-align: left;\" >\n");
									print($objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbQte()) . " X " . $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbPrix())) . "$\n");
									print("</td>\n");
									print("<td style=\"text-align: right;\" >\n");
									print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbQte() * $objCommandeSousItem->stpi_getNbPrix())) . "$");
									print("</td>\n");
									print("</tr>\n");
								}
								
								$nbSousTotal = $objCommande->stpi_getNbSousTotalFromArrObjCommandeSousItem();
								
								print("<tr>\n");
								print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
								print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("soustotal"));
								print("</td>\n");
								print("<td style=\"text-align: right;\" >\n");
								print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbSousTotal) . "$"));
								print("</td>\n");
								print("</tr>\n");
								
								$nbPrixRabais = $objCommande->stpi_getNbPrixRabaisFromArrObjCommandeSousItem();
										
								if (!empty($nbPrixRabais))
								{
									print("<tr>\n");
									print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
									print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixrabais"));
									print("</td>\n");
									print("<td style=\"text-align: right;\" >\n");
									print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixRabais)) . "$");
									print("</td>\n");
									print("</tr>\n");
								}
								
								$nbPrixShipping = 0;
								
								if (isset($objAdresseLivraison))
								{
									if ($objAdresseLivraison->stpi_getStrAdresse() != $objClientStore->stpi_getStrAdresse() || $objAdresseLivraison->stpi_getStrCodePostal() != $objClientStore->stpi_getStrCodePostal())
									{
										if ($nbUnits = $objCommande->stpi_getNbUnitsFromArrObjCommandeSousItem())
										{
											$strProvinceID = $objAdresseLivraison->stpi_getStrProvinceID();
											if ($strProvinceID == "isNULL" || empty($strProvinceID))
											{
												if ($nbPrixShipping = $objZone->stpi_getNbPrixShipping($nbUnits, $objAdresseLivraison->stpi_getStrCountryID()))
												{
													$boolShipZoneTaxable = $objZone->stpi_getBoolTaxable();
													print("<tr>\n");
													print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
													print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixshipping"));
													print("</td>\n");
													print("<td style=\"text-align: right;\" >\n");
													print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixShipping)) . "$");
													print("</td>\n");
													print("</tr>\n");
												}
											}
											else
											{
												if ($nbPrixShipping = $objZone->stpi_getNbPrixShipping($nbUnits, $objAdresseLivraison->stpi_getStrCountryID(), $strProvinceID))
												{
													$boolShipZoneTaxable = $objZone->stpi_getBoolTaxable();
													print("<tr>\n");
													print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
													print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixshipping"));
													print("</td>\n");
													print("<td style=\"text-align: right;\" >\n");
													print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixShipping)) . "$");
													print("</td>\n");
													print("</tr>\n");											
												}
											}
										}
									}
								}
								
								$nbPrixTaxes = 0;
								
								if ($arrNbPrixTaxes = $objCommande->stpi_getArrNbPrixTaxesFromArrObjCommandeSousItemAndAdresseFacturation($boolShipZoneTaxable, $nbPrixShipping))
								{
									if ($arrNbPrixTaxes["nbHST"] != 0)
									{
										print("<tr>\n");
										print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
										print($objTexte->stpi_getArrTxt("taxehst") . " (" . $arrNbPrixTaxes["nbPrcHST"] . "%)");
										print("</td>\n");
										print("<td style=\"text-align: right;\" >\n");
										print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($arrNbPrixTaxes["nbHST"])) . "$");
										print("</td>\n");
										print("</tr>\n");
									}
									else
									{
										if ($arrNbPrixTaxes["nbGST"] != 0)
										{
											print("<tr>\n");
											print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
											print($objTexte->stpi_getArrTxt("taxegst") . " (" . $arrNbPrixTaxes["nbPrcGST"] . "%)");
											print("</td>\n");
											print("<td style=\"text-align: right;\" >\n");
											print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($arrNbPrixTaxes["nbGST"])) . "$");
											print("</td>\n");
											print("</tr>\n");
										}
										if ($arrNbPrixTaxes["nbPST"] != 0)
										{
											print("<tr>\n");
											print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
											print($objTexte->stpi_getArrTxt("taxepst") . " (" . $arrNbPrixTaxes["nbPrcPST"] . "%)");
											print("</td>\n");
											print("<td style=\"text-align: right;\" >\n");
											print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($arrNbPrixTaxes["nbPST"])) . "$");
											print("</td>\n");
											print("</tr>\n");
										}
									}
									$nbPrixTaxes = $arrNbPrixTaxes["nbPrixTaxes"];
								}
								
								$nbPrixTotal += $nbSousTotal;
								$nbPrixTotal -= $nbPrixRabais;
								$nbPrixTotal += $nbPrixShipping;
								$nbPrixTotal += $nbPrixTaxes;
				
								if (!empty($nbPrixTotal))
								{
									print("<tr>\n");
									print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
									print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("total"));
									print("</td>\n");
									print("<td style=\"text-align: right;\" >\n");
									print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixTotal)) . "$");
									print("</td>\n");
									print("</tr>\n");
								}					
								print("</table>\n");
								print("<p><a href=\"checkout1.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("backto1") . "</a></p>");
								
								print("<table width=\"100%\" style=\"padding: 10px;\" >\n");
								print("<tr>\n");
								print("<td width=\"40%\" style=\"text-align:left; vertical-align:top;\" >\n");
								print("<h3 style=\"padding: 0px;\">" . $objTexte->stpi_getArrTxt("methodpaye") . "</h3>\n");
								$objMethodePaye->stpi_affSelectMethodPaye();
								print("</td>\n");
								
								print("<td width=\"60%\" style=\"text-align:left; vertical-align:top;\" >\n");
								print("<table width=\"100%\" >");
								print("<tr>\n");
								print("<td style=\"text-align:right; vertical-align:top;\" >\n");
								print($objTexte->stpi_getArrTxt("ordermessage"));
								print("</td>\n");
								print("<td style=\"text-align:left; vertical-align:top;\" >\n");
								print("<textarea id=\"strMessage\" rows=\"5\" cols=\"30\"></textarea>\n");
								print("</td>\n");
								print("</tr>\n");
								print("</table>");														
								print("</td>\n");
								print("</tr>\n");							
								print("</table>\n");
								
								print("<h3>" . $objTexte->stpi_getArrTxt("termsconditionstitle") . "</h3>\n");
								print("<p>\n");
								print($objTexte->stpi_getArrTxt("termsconditions") . "<br/>\n");
								print("<input type=\"checkbox\" id=\"boolAgreement\" />" . $objTexte->stpi_getArrTxt("chktermsconditions")  . "\n");
								print("</p>\n");
								
								print("<br/><br/>");
								
								print("<table style=\"padding: 10px;\" >\n");
								print("<tr>\n");
								print("<td style=\"text-align: right; vertical-align: top;\" >\n");
								print($objTexte->stpi_getArrTxt("captcha") . " :\n");
								print("</td>\n");
								print("<td style=\"text-align: left; vertical-align: top;\" >\n");
								print("<img style=\"border: 2px solid black;\" src=\"./stpiadmin/captcha.php\" alt=\"Captcha\"/>\n");
								print("<br/>\n");
								print("<input type=\"text\" size=\"20\" id=\"strCaptcha\" name=\"strCaptcha\" value=\"\" />\n");
								print("</td>\n");
								print("</tr>\n");
								print("</table>\n");
								
								print("<div style=\"width: 100%; text-align: right;\" >\n");
								print("<span id=\"stpi_addCommandeRegistre\" ></span>\n");
								print("<br/><br/>\n");
								print("<a href=\"shopregistre.php?l=" . LG . "&amp;strRegistreCode=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . "\" >" . $objTexte->stpi_getArrTxt("backtoshop") . "</a> - <input type=\"button\" id=\"stpi_addCommandeRegistreButton\" onclick=\"stpi_addCommandeRegistre()\" value=\"" . $objTexte->stpi_getArrTxt("buttonplaceorder") . "\" />\n");
								print("</div><br/>\n");
				
							}
						}
					}
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
				}
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
			}
			
		?>
		
		</div>
		
		<div class="doubleclear"></div>
	</div>
	
	<div id="bottommenu">
		<?php
			$objMenu->stpi_affPublicMenu();
		?>
	</div>
	
	<div id="footer">
		<?php
			$objFooter->stpi_affPublicFooter();
		?>
	</div>
	
	</body>

</html>