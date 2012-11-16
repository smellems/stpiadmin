<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/commande/clscommande.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	
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
	$objRegistre =& $objCommande->stpi_getObjRegistre();
	$objUser = new clsuser();
	$objCommandeSousItem =& $objCommande->stpi_getObjCommandeSousItem();
	$objItem =& $objCommandeSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objPrix =& $objSousItem->stpi_getObjPrix();
	
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objPageLg->stpi_getStrTitre());

	// <!-- MainContentStart -->
	$objJavaScript->stpi_affArrLang();
	$objJavaScript->stpi_affCreateXmlHttp();
	$objJavaScript->stpi_affNoAjax();

	$objJavaScript->stpi_affNoJavaScript();

	$objMotd->stpi_affPublic();

	$objSousItem->stpi_affJsEditSousItemFromCommande();
	$objSousItem->stpi_affJsDelSousItemFromCommande();
	$objSousItem->stpi_affJsEditSousItemFromCommandeRegistre();
	$objSousItem->stpi_affJsDelSousItemFromCommandeRegistre();

	print("<h2>" . $objTexte->stpi_getArrTxt("checkout1") . "</h2>\n");

	if (isset($_SESSION["stpiObjCommandeSession"]))
	{
		if ($objCommandeSession = $objCommandeSession->stpi_getObjCommandeSessionFromSession())
		{
			if ($arrObjCommandeSousItemSession = $objCommandeSession->stpi_getArrObjCommandeSousItemSession())
			{
				if(!empty($arrObjCommandeSousItemSession))
				{
					$strArrSousItem = "0";
				
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
					print("<td>\n");
					print("&nbsp;");
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
							if ($objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 1))
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
						print("<input type=\"text\" maxlength=\"3\" size=\"2\" id=\"nbQuantity" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbSousItemID()) . "\" value=\"" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbQte()) . "\" /> X ");
						print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbPrix())) . "$");
						print("</td>\n");
						print("<td style=\"text-align: right;\" >\n");
						print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbQte() * $objCommandeSousItem->stpi_getNbPrix())) . "$");
						print("</td>\n");
						print("<td style=\"text-align: right;\" >\n");
						print("<input type=\"button\" id=\"stpi_delSousItemFromCommande" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbSousItemID()). "\" onclick=\"stpi_delSousItemFromCommande(" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbSousItemID()) . ")\" value=\"" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("delete") . "\" />\n");
						print("</td>\n");
						print("</tr>\n");
					}
				
					print("<tr>\n");
					print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
					print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("soustotal"));
					print("</td>\n");
					print("<td style=\"text-align: right;\" >\n");
					print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommande->stpi_getNbSousTotalFromArrObjCommandeSousItem()) . "$"));
					print("</td>\n");
					print("<td>\n");
					print("&nbsp;");
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
						print("<td>\n");
						print("&nbsp;");
						print("</td>\n");
						print("</tr>\n");
					}
				
					print("</table>\n");
				
					print("<p><a href=\"shop.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("returntoshop") . "</a></p>\n");
				
					print("<div style=\"padding: 0px 10px; margin: 0px; text-align: right;\" >\n");
					print("<span id=\"stpi_message\" ></span>\n");
					print("<br/>\n");
					print("<input type=\"button\" id=\"stpi_editSousItemFromCommande\" onclick=\"stpi_editSousItemFromCommande(Array(" . $strArrSousItem . "))\" value=\"" . $objTexte->stpi_getArrTxt("updatecart") . "\" />\n");
					print("<br/>\n");
					$boolLogged = true;
					if (isset($_SESSION["stpiObjUser"]))
					{
						if ($objUser = $objUser->stpi_getObjUserFromSession())
						{
							if ($objUser->stpi_getNbTypeUserID() == 2)
							{
								print("<input type=\"button\" id=\"buttoncontinue\" onclick=\"window.location = './checkout2.php?l=" . LG . "';\" value=\"" . $objTexte->stpi_getArrTxt("continue") . "\" />\n");
							}
							else
							{
								$boolLogged = false;
							}
						}
						else
						{
							$boolLogged = false;	
						}
					}
					else
					{
						$boolLogged = false;
					}
					if (!$boolLogged)
					{
						print("<input type=\"button\" id=\"buttonlogincontinue\" onclick=\"window.location = './login.php?redirect=checkout2&amp;l=" . LG . "';\" value=\"" . $objTexte->stpi_getArrTxt("buttonlogincontinue") . "\" />\n");
						print("<br/>\n");
						print("<input type=\"button\" id=\"buttonnologincontinue\" onclick=\"window.location = './checkout2.php?l=" . LG . "';\" value=\"" . $objTexte->stpi_getArrTxt("buttonnologincontinue") . "\" />\n");
					}			
					print("</div><br/>\n");

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
		}
	}
	if (isset($_SESSION["stpiObjCommandeRegistreSession"]))
	{
		if ($objCommandeSession = $objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession())
		{
			if ($objRegistre->stpi_setNbID($objCommandeSession->stpi_getNbRegistreID()))
			{
				if ($arrObjCommandeSousItemSession = $objCommandeSession->stpi_getArrObjCommandeSousItemSession())
				{
					if(!empty($arrObjCommandeSousItemSession))
					{
						print("<h2>" . $objTexte->stpi_getArrTxt("forgiftlist") . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . "</h2>\n");
					
						$strArrSousItem = "0";
					
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
						print("<td>\n");
						print("&nbsp;");
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
							print("<input type=\"text\" maxlength=\"3\" size=\"2\" id=\"nbQuantityRegistre" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbSousItemID()) . "\" value=\"" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbQte()) . "\" /> X ");
							print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbPrix())) . "$");
							print("</td>\n");
							print("<td style=\"text-align: right;\" >\n");
							print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbQte() * $objCommandeSousItem->stpi_getNbPrix())) . "$");
							print("</td>\n");
							print("<td style=\"text-align: right;\" >\n");
							print("<input type=\"button\" id=\"stpi_delSousItemFromCommandeRegistre" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbSousItemID()). "\" onclick=\"stpi_delSousItemFromCommandeRegistre(" . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbSousItemID()) . ")\" value=\"" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("delete") . "\" />\n");
							print("</td>\n");
							print("</tr>\n");
						}
					
						print("<tr>\n");
						print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
						print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("soustotal"));
						print("</td>\n");
						print("<td style=\"text-align: right;\" >\n");
						print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommande->stpi_getNbSousTotalFromArrObjCommandeSousItem()) . "$"));
						print("</td>\n");
						print("<td>\n");
						print("&nbsp;");
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
							print("<td>\n");
							print("&nbsp;");
							print("</td>\n");
							print("</tr>\n");
						}
					
						print("</table>\n");
					
						print("<p><a href=\"shopregistre.php?l=" . LG . "&amp;strRegistreCode=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . "\" >" . $objTexte->stpi_getArrTxt("returntogistlist") . "</a></p>\n");								
					
						print("<div style=\"padding: 0px 10px; margin: 0px; text-align: right;\" >\n");
						print("<span id=\"stpi_messageregistre\" ></span>\n");
						print("<br/>\n");
						print("<input type=\"button\" id=\"stpi_editSousItemFromCommandeRegistre\" onclick=\"stpi_editSousItemFromCommandeRegistre(Array(" . $strArrSousItem . "))\" value=\"" . $objTexte->stpi_getArrTxt("updatecart") . "\" />\n");
						print("<br/>\n");
						$boolLogged = true;
						if (isset($_SESSION["stpiObjUser"]))
						{
							if ($objUser = $objUser->stpi_getObjUserFromSession())
							{
								if ($objUser->stpi_getNbTypeUserID() == 2)
								{
									print("<input type=\"button\" id=\"buttoncontinue\" onclick=\"window.location = './checkoutregistre2.php?l=" . LG . "';\" value=\"" . $objTexte->stpi_getArrTxt("continue") . "\" />\n");
								}
								else
								{
									$boolLogged = false;
								}
							}
							else
							{
								$boolLogged = false;	
							}
						}
						else
						{
							$boolLogged = false;
						}
						if (!$boolLogged)
						{
							print("<input type=\"button\" id=\"buttonlogincontinue\" onclick=\"window.location = './login.php?redirect=checkoutregistre2&amp;l=" . LG . "';\" value=\"" . $objTexte->stpi_getArrTxt("buttonlogincontinue") . "\" />\n");
							print("<br/>\n");
							print("<input type=\"button\" id=\"buttonnologincontinue\" onclick=\"window.location = './checkoutregistre2.php?l=" . LG . "';\" value=\"" . $objTexte->stpi_getArrTxt("buttonnologincontinue") . "\" />\n");
						}			
						print("</div><br/>\n");
	
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
			}
		}
	}
	if (!isset($_SESSION["stpiObjCommandeSession"]) && !isset($_SESSION["stpiObjCommandeRegistreSession"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
	}
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
