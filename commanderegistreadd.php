<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/img/clscaptcha.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/client/clsclient.php");
	require_once("./stpiadmin/includes/classes/ship/clszone.php");
	require_once("./stpiadmin/includes/classes/commande/clscommande.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	
	$objBdd = clsbdd::singleton();
	$objUser = new clsuser();
	$objCaptcha = new clscaptcha();
	$objBody = new clsbody();
	$objZone = new clszone();
	$objCommande = new clscommande();
	$objCommandeSession = new clscommandesession();
	$objTexte = new clstexte("./texte/checkout");
	$objAdresseFacturation = new clsadresse();
	$objAdresseLivraison = new clsadresse();
	$objClientStore = new clsclient();
	
	$objEmail =& $objCommande->stpi_getObjEmail();
	$objDate =& $objCommande->stpi_getObjDate();
	$objMethodPaye =& $objCommande->stpi_getObjMethodPaye();
	$objInfoCarte =& $objCommande->stpi_getObjInfoCarte();
	$objCountry =& $objCommande->stpi_getObjAdresse()->stpi_getObjCountry();
	$objClient =& $objCommande->stpi_getObjClient();
	$objProvince =& $objCountry->stpi_getObjProvince();
	$objCommandeSousItem =& $objCommande->stpi_getObjCommandeSousItem();
	$objItem =& $objCommandeSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objPrix =& $objSousItem->stpi_getObjPrix();
	$objRegistre =& $objCommande->stpi_getObjRegistre();
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	
	
	$strEmailFromAdmin = STR_EMAIL_FROM;
	$strEmailAdmin = STR_EMAIL_TO;
	$strEmailSubjectAdmin = "";
	$strMessageAdmin = "";
	
	$strEmailFrom = STR_EMAIL_FROM;
	$strEmail = "";	
	$strEmailSubject = "";
	$strMessage = "";
	
	$strPayPalAccount = STR_EMAIL_PAYPAL;
	$strPayPalReturnUrl = STR_HOME_PAGE;
	
	$boolShipZoneTaxable = false;
	
	$nbAvailable = 0;
	
	$nbSousTotal = 0;
	$nbPrixRabais = 0;
	$nbPrixShipping = 0;
	$nbPrixTaxes = 0;
	$nbPrixTotal = 0;
	
	if (!isset($_SESSION["stpiObjCommandeRegistreSession"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (!$objCommandeSession = $objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession())
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	$arrObjCommandeSousItemSession = $objCommandeSession->stpi_getArrObjCommandeSousItemSession();
	$arrObjAdresseSession = $objCommandeSession->stpi_getArrObjAdresseSession();
	
	if (empty($arrObjCommandeSousItemSession))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (empty($arrObjAdresseSession))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (isset($_SESSION["stpiObjUser"]))
	{
		if ($objUser = $objUser->stpi_getObjUserFromSession())
		{
			if ($objUser->stpi_getNbTypeUserID() == 2)
			{
				if (!$objCommande->stpi_setNbClientID($objUser->stpi_getNbID()))
				{
					exit;			
				}
			}
		}
	}
	
	if (!$objClientStore->stpi_setNbID(1))
	{
		exit;
	}
	
	if (!$objRegistre->stpi_setNbID($objCommandeSession->stpi_getNbRegistreID()))
	{
		exit;
	}
	
	if (!$objRegistre->stpi_chkIfActif())
	{
		exit;
	}
	
	if (!$objRegistre->stpi_chkIfNotExpired())
	{
		exit;
	}
	
	if (!$objClient->stpi_setNbID($objRegistre->stpi_getNbClientID()))
	{
		exit;
	}
	
	if (!isset($arrObjAdresseSession[1]) || !isset($arrObjAdresseSession[2]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (!$objAdresseFacturation->stpi_setNbTypeAdresseID(1))
	{
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrNom($arrObjAdresseSession[1]->stpi_getStrNom()))
	{
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrPrenom($arrObjAdresseSession[1]->stpi_getStrPrenom()))
	{
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrCie($arrObjAdresseSession[1]->stpi_getStrCie()))
	{
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrAdresse($arrObjAdresseSession[1]->stpi_getStrAdresse()))
	{
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrVille($arrObjAdresseSession[1]->stpi_getStrVille()))
	{
		exit;
	}
	if (!$objCountry->stpi_setStrID($arrObjAdresseSession[1]->stpi_getStrCountryID()))
	{
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrCountryID($arrObjAdresseSession[1]->stpi_getStrCountryID()))
	{
		exit;
	}
	if (!$objCountry->stpi_chkProvinceInCountry($arrObjAdresseSession[1]->stpi_getStrProvinceID()))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("countryprovince") . "</span><br/>\n");
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrProvinceID($arrObjAdresseSession[1]->stpi_getStrProvinceID()))
	{
		exit;
	}
	if (!$objAdresseFacturation->stpi_setStrCodePostal($arrObjAdresseSession[1]->stpi_getStrCodePostal()))
	{
		exit;
	}
	
	if (!$objAdresseLivraison->stpi_setNbTypeAdresseID(2))
	{
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrNom($arrObjAdresseSession[2]->stpi_getStrNom()))
	{
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrPrenom($arrObjAdresseSession[2]->stpi_getStrPrenom()))
	{
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrCie($arrObjAdresseSession[2]->stpi_getStrCie()))
	{
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrAdresse($arrObjAdresseSession[2]->stpi_getStrAdresse()))
	{
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrVille($arrObjAdresseSession[2]->stpi_getStrVille()))
	{
		exit;
	}
	if (!$objCountry->stpi_setStrID($arrObjAdresseSession[2]->stpi_getStrCountryID()))
	{
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrCountryID($arrObjAdresseSession[2]->stpi_getStrCountryID()))
	{
		exit;
	}
	if (!$objCountry->stpi_chkProvinceInCountry($arrObjAdresseSession[2]->stpi_getStrProvinceID()))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("countryprovince") . "</span><br/>\n");
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrProvinceID($arrObjAdresseSession[2]->stpi_getStrProvinceID()))
	{
		exit;
	}
	if (!$objAdresseLivraison->stpi_setStrCodePostal($arrObjAdresseSession[2]->stpi_getStrCodePostal()))
	{
		exit;
	}
							
	$arrObjAdresse = array();
					
	$arrObjAdresse[1] = $objAdresseFacturation;
	$arrObjAdresse[2] = $objAdresseLivraison;
							
	$objCommande->stpi_setArrObjAdresse($arrObjAdresse);

	$arrObjCommandeSousItem = array();					
	foreach ($arrObjCommandeSousItemSession as $objCommandeSousItemSession)
	{
		if (!$objSousItem->stpi_setNbID($objCommandeSousItemSession->stpi_getNbSousItemID()))
		{
			exit;
		}
		if (!$objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
		{
			exit;
		}
		if (!$objRegistreSousItem->stpi_setNbID($objRegistre->stpi_getNbID(), $objSousItem->stpi_getNbID()))
		{
			exit;
		}
		if (!$objItem->stpi_chkDisponibilite(2))
		{
			print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notavailable") . "</span><br/>\n");
			exit;
		}		
		$nbAvailable = $objSousItem->stpi_getNbQte();
		if ($nbAvailable > ($objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu()))
		{
			$nbAvailable = $objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu();
		}	
		if (($nbAvailable - $objCommandeSousItemSession->stpi_getNbQte()) < 0)
		{
			if ($nbAvailable == 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("outofstock") . "</span><br/>\n");							
			}
			elseif ($nbAvailable > 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($nbAvailable) . " " . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("quantityleft") . "</span><br/>\n");
			}
			exit;
		}
				
		$objCommandeSousItemNew = new clscommandesousitem();
		$objCommandeSousItemNew->stpi_setNbSousItemID($objSousItem->stpi_getNbID());
		$objCommandeSousItemNew->stpi_setStrItemCode($objSousItem->stpi_getStrItemCode);
		if (!$objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 2))
		{
			exit;	
		}
		$objCommandeSousItemNew->stpi_setNbPrix($objPrix->stpi_getNbPrix());
		$objCommandeSousItemNew->stpi_setNbQte($objCommandeSousItemSession->stpi_getNbQte());
		$objCommandeSousItemNew->stpi_setStrSousItemDesc($objItem->stpi_getStrSousItemDesc());
		$arrObjCommandeSousItem[$objSousItem->stpi_getNbID()] = $objCommandeSousItemNew;
	}	
	$objCommande->stpi_setArrObjCommandeSousItem($arrObjCommandeSousItem);
	
	$nbSousTotal = $objCommande->stpi_getNbSousTotalFromArrObjCommandeSousItem();
	if ($nbSousTotal === false)
	{
		exit;
	}
	$nbPrixRabais = $objCommande->stpi_getNbPrixRabaisFromArrObjCommandeSousItem();
	if ($nbPrixRabais === false)
	{
		exit;
	}
	if ($objAdresseLivraison->stpi_getStrAdresse() != $objClientStore->stpi_getStrAdresse() || $objAdresseLivraison->stpi_getStrCodePostal() != $objClientStore->stpi_getStrCodePostal())
	{
		$nbUnits = $objCommande->stpi_getNbUnitsFromArrObjCommandeSousItem();
		if ($nbUnits === false)
		{
			exit;
		}
		
		$strProvinceID = $objAdresseLivraison->stpi_getStrProvinceID();
		if (empty($strProvinceID))
		{
			$nbPrixShipping = $objZone->stpi_getNbPrixShipping($nbUnits, $objAdresseLivraison->stpi_getStrCountryID());
			if ($nbPrixShipping === false)
			{
				exit;
			}
			$boolShipZoneTaxable = $objZone->stpi_getBoolTaxable();
		}
		else
		{
			$nbPrixShipping = $objZone->stpi_getNbPrixShipping($nbUnits, $objAdresseLivraison->stpi_getStrCountryID(), $strProvinceID);
			if ($nbPrixShipping === false)
			{
				exit;
			}
			$boolShipZoneTaxable = $objZone->stpi_getBoolTaxable();
		}
	}
	$arrNbPrixTaxes = $objCommande->stpi_getArrNbPrixTaxesFromArrObjCommandeSousItemAndAdresseFacturation($boolShipZoneTaxable, $nbPrixShipping);
	if ($arrNbPrixTaxes === false)
	{
		exit;
	}
	$nbPrixTaxes = $arrNbPrixTaxes["nbPrixTaxes"];
	
	$nbPrixTotal += $nbSousTotal;
	$nbPrixTotal -= $nbPrixRabais;
	$nbPrixTotal += $nbPrixShipping;
	$nbPrixTotal += $nbPrixTaxes;
	
	
	if (!$objCommande->stpi_setNbTypeCommandeID(2))
	{
		exit;
	}
	if (!$objCommande->stpi_setNbStatutCommandeID(1))
	{
		exit;
	}
	if (!$objCommande->stpi_setNbMethodPayeID($_POST["nbMethodPayeID"]))
	{
		exit;
	}
	if (!$objMethodPaye->stpi_setNbID($_POST["nbMethodPayeID"]))
	{
		exit;
	}
	if ($objMethodPaye->stpi_getBoolCarte())
	{
		if (!$objInfoCarte->stpi_setStrNom($_POST["strNom"]))
		{
			exit;
		}
		if (!$objInfoCarte->stpi_setStrNum($_POST["strNum"]))
		{
			exit;
		}
		if (!$objDate->stpi_setStrMois($_POST["dtDateExpir1"]))
		{
			exit;
		}
		if (!$objDate->stpi_setStrAnnee($_POST["dtDateExpir2"]))
		{
			exit;
		}
		if (!$objDate->stpi_setStrJour(date('t', mktime(0, 0, 0, $objDate->stpi_getStrMois(), 1, $objDate->stpi_getStrAnnee()))))
		{
			exit;
		}
		if (strtotime($objDate->stpi_getStrDateISO()) <= time())
		{
			print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("cardexpired") . "</span><br/>\n");
			exit;
		}
		if (!$objInfoCarte->stpi_setDtDateExpir($objDate->stpi_getStrDateISO()))
		{
			exit;
		}
		if (!$objInfoCarte->stpi_setStrCodeSecur($_POST["strCodeSecur"]))
		{
			exit;
		}
	}
	if (!$objCommande->stpi_setNbRegistreID($objRegistre->stpi_getNbID()))
	{
		exit;
	}
	if (!$objCommande->stpi_setStrTel($objCommandeSession->stpi_getStrTel()))
	{
		exit;
	}
	if (!$objCommande->stpi_setStrCourriel($objCommandeSession->stpi_getStrCourriel()))
	{
		exit;
	}
	if (!$objCommande->stpi_setNbPrixShipping($nbPrixShipping))
	{
		exit;
	}
	if (!$objCommande->stpi_setNbPrixRabais($nbPrixRabais))
	{
		exit;
	}
	if (!$objCommande->stpi_setNbPrixTaxes($nbPrixTaxes))
	{
		exit;
	}
	if (!$objCommande->stpi_setStrMessage($_POST["strMessage"]))
	{
		exit;
	}
	if (!$objCommande->stpi_setStrLangID(LG))
	{
		exit;
	}
	if (!$_POST["boolAgreement"])
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("mustagree") . "</span><br/>\n");
		exit;
	}
	
	if (empty($_POST["strCaptcha"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("pascaptcha") . "</span><br/>\n");
		exit;
	}
	
	if (!$objCaptcha->stpi_chkCaptcha($_POST["strCaptcha"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("captchainvalide") . "</span><br/>\n");
		exit;
	}
	
	
	$strMessage .= "<h3>" . $objTexte->stpi_getArrTxt("forgiftlist") . " : " . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . "</h3>\n";
	
	
	$strMessage .= "<table width=\"100%\" style=\"padding: 10px;\" >\n";
	$strMessage .= "<tr>\n";
	$strMessage .= "<td style=\"text-align: left;\" >\n";
	$strMessage .= "<h3 style=\"padding: 0px;\" >\n";
	$strMessage .= $objCommandeSousItem->stpi_getObjTexte()->stpi_getArrTxt("desc");
	$strMessage .= "</h3>\n";
	$strMessage .= "</td>\n";
	$strMessage .= "<td style=\"text-align: left;\" >\n";
	$strMessage .= "<h3 style=\"padding: 0px;\" >\n";
	$strMessage .= $objCommande->stpi_getObjTexte()->stpi_getArrTxt("quantityprice");
	$strMessage .= "</h3>\n";
	$strMessage .= "</td>\n";
	$strMessage .= "<td style=\"text-align: right;\" >\n";
	$strMessage .= "<h3 style=\"padding: 0px; text-align: right;\" >\n";
	$strMessage .= $objCommande->stpi_getObjTexte()->stpi_getArrTxt("total");
	$strMessage .= "</h3>\n";
	$strMessage .= "</td>\n";
	$strMessage .= "</tr>\n";	
	foreach ($arrObjCommandeSousItem as $objCommandeSousItem)
	{
		$strMessage .="<tr>\n";
		$strMessage .="<td style=\"text-align: left;\" >\n";
		$strItemCode = $objCommandeSousItem->stpi_getStrItemCode();
		if (empty($strItemCode))
		{
			$strMessage .= $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getStrSousItemDesc());
		}
		else
		{
			$strMessage .= $objBdd->stpi_trsBddToHTML($strItemCode) .  " - " . $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getStrSousItemDesc());
		}
		$strMessage .= "</td>\n";
		$strMessage .= "<td style=\"text-align: left;\" >\n";
		$strMessage .= $objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbQte()) . " X " . $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbPrix())) . "$\n";
		$strMessage .= "</td>\n";
		$strMessage .= "<td style=\"text-align: right;\" >\n";
		$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbQte() * $objCommandeSousItem->stpi_getNbPrix())) . "$";
		$strMessage .= "</td>\n";
		$strMessage .= "</tr>\n";
	}
	$strMessage .= "<tr>\n";
	$strMessage .= "<td colspan=\"2\" style=\"text-align: right;\" >\n";
	$strMessage .= $objCommande->stpi_getObjTexte()->stpi_getArrTxt("soustotal");
	$strMessage .= "</td>\n";
	$strMessage .= "<td style=\"text-align: right;\" >\n";
	$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbSousTotal) . "$");
	$strMessage .= "</td>\n";
	$strMessage .= "</tr>\n";
	if (!empty($nbPrixRabais))
	{
		$strMessage .= "<tr>\n";
		$strMessage .= "<td colspan=\"2\" style=\"text-align: right;\" >\n";
		$strMessage .= $objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixrabais");
		$strMessage .= "</td>\n";
		$strMessage .= "<td style=\"text-align: right;\" >\n";
		$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixRabais)) . "$";
		$strMessage .= "</td>\n";
		$strMessage .= "</tr>\n";
	}
	if (!empty($nbPrixShipping))
	{
		$strMessage .= "<tr>\n";
		$strMessage .= "<td colspan=\"2\" style=\"text-align: right;\" >\n";
		$strMessage .= $objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixshipping");
		$strMessage .= "</td>\n";
		$strMessage .= "<td style=\"text-align: right;\" >\n";
		$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixShipping)) . "$";
		$strMessage .= "</td>\n";
		$strMessage .= "</tr>\n";
	}
	if ($arrNbPrixTaxes["nbHST"] != 0)
	{
		$strMessage .= "<tr>\n";
		$strMessage .= "<td colspan=\"2\" style=\"text-align: right;\" >\n";
		$strMessage .= $objTexte->stpi_getArrTxt("taxehst") . " (" . $arrNbPrixTaxes["nbPrcHST"] . "%)";
		$strMessage .= "</td>\n";
		$strMessage .= "<td style=\"text-align: right;\" >\n";
		$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($arrNbPrixTaxes["nbHST"])) . "$";
		$strMessage .= "</td>\n";
		$strMessage .= "</tr>\n";
	}
	else
	{
		if ($arrNbPrixTaxes["nbGST"] != 0)
		{
			$strMessage .= "<tr>\n";
			$strMessage .= "<td colspan=\"2\" style=\"text-align: right;\" >\n";
			$strMessage .= $objTexte->stpi_getArrTxt("taxegst") . " (" . $arrNbPrixTaxes["nbPrcGST"] . "%)";
			$strMessage .= "</td>\n";
			$strMessage .= "<td style=\"text-align: right;\" >\n";
			$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($arrNbPrixTaxes["nbGST"])) . "$";
			$strMessage .= "</td>\n";
			$strMessage .= "</tr>\n";
		}
		if ($arrNbPrixTaxes["nbPST"] != 0)
		{
			$strMessage .= "<tr>\n";
			$strMessage .= "<td colspan=\"2\" style=\"text-align: right;\" >\n";
			$strMessage .= $objTexte->stpi_getArrTxt("taxepst") . " (" . $arrNbPrixTaxes["nbPrcPST"] . "%)";
			$strMessage .= "</td>\n";
			$strMessage .= "<td style=\"text-align: right;\" >\n";
			$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($arrNbPrixTaxes["nbPST"])) . "$";
			$strMessage .= "</td>\n";
			$strMessage .= "</tr>\n";
		}
	}
	$strMessage .= "<tr>\n";
	$strMessage .= "<td colspan=\"2\" style=\"text-align: right;\" >\n";
	$strMessage .= $objCommande->stpi_getObjTexte()->stpi_getArrTxt("total");
	$strMessage .= "</td>\n";
	$strMessage .= "<td style=\"text-align: right;\" >\n";
	$strMessage .= $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixTotal)) . "$";
	$strMessage .= "</td>\n";
	$strMessage .= "</tr>\n";
	$strMessage .= "</table>\n";

	
	$strMessage .= "<table style=\"padding: 10px;\" >\n";
	if ($objAdresseFacturation->stpi_getStrAdresse() == $objAdresseLivraison->stpi_getStrAdresse() && $objAdresseFacturation->stpi_getStrCodePostal() == $objAdresseLivraison->stpi_getStrCodePostal())
	{
		$strMessage .= "<tr>\n";
		$strMessage .= "<td style=\"padding: 0px 10px; text-align: center;\" >\n";
		$strMessage .= "<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("billingdelivery") . "</h3>";
		$strMessage .= "</td>\n";
		$strMessage .= "</tr>\n";
		$strMessage .= "<tr>\n";
		$strMessage .= "<td style=\"padding: 0px 10px; text-align: center;\" >\n";
		$strCie = $objAdresseFacturation->stpi_getStrCie();
		if (!empty($strCie))
		{
			$strMessage .= $objBody->stpi_trsInputToHTML($strCie) . "<br/>\n";
		}
		$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrPrenom()) . " " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrNom())  . "<br/>\n";
		$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrAdresse()) . "<br/>\n";
		$strProvinceID = $objAdresseFacturation->stpi_getStrProvinceID();
		if(empty($strProvinceID))
		{											
			$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n";
		}
		else
		{
			$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrProvinceID()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n";
		}
		$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCodePostal()) . "<br/>\n";
		$strMessage .= "</td>\n";
		$strMessage .= "</tr>\n";												
	}
	else
	{
		$strMessage .= "<tr>\n";
		$strMessage .= "<td style=\"padding: 0px 10px; text-align: center;\" >\n";
		$strMessage .= "<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("billing") . "</h3>";
		$strMessage .= "</td>\n";		
		$strMessage .= "<td style=\"padding: 0px 10px; text-align: center;\" >\n";
		if ($objAdresseLivraison->stpi_getStrAdresse() == $objClientStore->stpi_getStrAdresse() && $objAdresseLivraison->stpi_getStrCodePostal() == $objClientStore->stpi_getStrCodePostal())
		{
			$strMessage .= "<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("instorepickup") . "</h3>";
		}
		elseif ($objAdresseLivraison->stpi_getStrAdresse() == $objClient->stpi_getStrAdresse() && $objAdresseLivraison->stpi_getStrCodePostal() == $objClient->stpi_getStrCodePostal())
		{
			$strMessage .= "<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("delivertogiftlistowner") . "</h3>";
		}
		else
		{
			$strMessage .= "<h3 style=\"text-align: center; padding: 0px;\" >" . $objTexte->stpi_getArrTxt("delivery") . "</h3>";
		}
		$strMessage .= "</td>\n";	
		$strMessage .= "</tr>\n";
		$strMessage .= "<tr>\n";
		$strMessage .= "<td style=\"padding: 0px 10px; text-align: center;\" >\n";
		$strCie = $objAdresseFacturation->stpi_getStrCie();
		if (!empty($strCie))
		{
			$strMessage .= $objBody->stpi_trsInputToHTML($strCie) . "<br/>\n";
		}
		$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrPrenom()) . " " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrNom())  . "<br/>\n";
		$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrAdresse()) . "<br/>\n";
		$strProvinceID = $objAdresseFacturation->stpi_getStrProvinceID();
		if(empty($strProvinceID))
		{											
			$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n";
		}
		else
		{
			$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrProvinceID()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n";
		}
		$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseFacturation->stpi_getStrCodePostal()) . "<br/>\n";
		$strMessage .= "</td>\n";
		$strMessage .= "<td style=\"padding: 0px 10px; text-align: center;\" >\n";
		if ($objAdresseLivraison->stpi_getStrAdresse() != $objClient->stpi_getStrAdresse() || $objAdresseLivraison->stpi_getStrCodePostal() != $objClient->stpi_getStrCodePostal())
		{		
			$strCie = $objAdresseLivraison->stpi_getStrCie();
			if (!empty($strCie))
			{
				$strMessage .= $objBody->stpi_trsInputToHTML($strCie) . "<br/>\n";
			}
			if ($objAdresseLivraison->stpi_getStrAdresse() != $objClientStore->stpi_getStrAdresse() || $objAdresseLivraison->stpi_getStrCodePostal() != $objClientStore->stpi_getStrCodePostal())
			{
				$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrPrenom()) . " " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrNom())  . "<br/>\n";
			}
			$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrAdresse()) . "<br/>\n";
			$strProvinceID = $objAdresseLivraison->stpi_getStrProvinceID();
			if(empty($strProvinceID))
			{											
				$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrCountryID()) . "<br/>\n";
			}
			else
			{
				$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrVille()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrProvinceID()) . ", " . $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrCountryID()) . "<br/>\n";
			}
			$strMessage .= $objBody->stpi_trsInputToHTML($objAdresseLivraison->stpi_getStrCodePostal()) . "<br/>\n";
		}
		else
		{
			print("&nbsp;\n");
		}			
		$strMessage .= "</td>\n";			
		$strMessage .= "</tr>\n";
	}
	$strMessage .= "</table>\n";
	$strMessage .= "<h3>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getStrCourriel()) . "</h3>\n";
	$strMessage .= "<h3>" . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getStrTel()) . "</h3>\n";
	if (!$objMethodPaye->stpi_setObjMethodPayeLgFromBdd())
	{
		exit;
	}
	$objMethodPayeLg =& $objMethodPaye->stpi_getObjMethodPayeLg();
	$strMessage .= "<h3>" . $objTexte->stpi_getArrTxt("methodpaye") . " : " . $objBdd->stpi_trsBddToHTML($objMethodPayeLg->stpi_getStrName()) . "</h3>\n";
	$strDesc = $objMethodPayeLg->stpi_getStrDesc();
	if (!empty($strDesc))
	{
		$strMessage .= "<p>" . $objBdd->stpi_trsBddToHTML($strDesc) . "</p>\n";
	}
	
	$nbMethodPayeID = $objMethodPaye->stpi_getNbID();	
	if ($nbMethodPayeID == 4)
	{
		$strMessage .= "<form name=\"order\" action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"return\" value=\"" . STR_HOME_PAGE . "\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"cancel_return\" value=\"" . STR_HOME_PAGE . "\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"business\" value=\"" . $strPayPalAccount . "\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"item_name\" value=\"" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("paypalproduct") . "\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"quantity\" value=\"1\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"amount\" value=\"" . $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixTotal)) . "\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"currency_code\" value=\"CAD\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"no_note\" value=\"1\">\n";
		$strMessage .= "<input type=\"hidden\" name=\"tax\" value=\"0\">\n";
		$strMessage .= "<p>\n";
		$strMessage .= $objCommande->stpi_getObjTexte()->stpi_getArrTxt("paypal");
		$strMessage .= "<br/>\n";
		$strMessage .= "<input type=\"image\" name=\"submit\" border=\"0\" src=\"images/paypal.jpg\" alt=\"PayPal\">\n";
		$strMessage .= "</p>\n";
		$strMessage .= "</form>\n";
	}

	if (!empty($_POST["strMessage"]))
	{
		$strMessage .= "<h3>" . $objTexte->stpi_getArrTxt("ordermessage") . "</h3>\n";
		$strMessage .= "<p>" . $objBody->stpi_trsInputToHTML($_POST["strMessage"]) . "</p>\n";
	}
	
	$strMessageAdmin = $strMessage;
	
	if (!$objBdd->stpi_startTransaction())
	{
		exit;
	}
	
	if ($objMethodPaye->stpi_getBoolCarte())
	{
		if (!$nbInfoCarteID = $objInfoCarte->stpi_insert())
		{
			$objBdd->stpi_rollback();
			exit;
		}
		
		if (!$objCommande->stpi_setNbInfoCarteID($nbInfoCarteID))
		{
			$objBdd->stpi_rollback();
			exit;
		}
	}
	
	if (!$nbCommandeID = $objCommande->stpi_insert())
	{
		$objBdd->stpi_rollback();
		exit;
	}
	
	if (!$arrObjAdresse[1]->stpi_setNbCommandeID($nbCommandeID))
	{
		$objBdd->stpi_rollback();
		exit;
	}
	
	if (!$arrObjAdresse[2]->stpi_setNbCommandeID($nbCommandeID))
	{
		$objBdd->stpi_rollback();
		exit;
	}
	
	if (!$arrObjAdresse[1]->stpi_insert())
	{
		$objBdd->stpi_rollback();
		exit;
	}
	
	if (!$arrObjAdresse[2]->stpi_insert())
	{
		$objBdd->stpi_rollback();
		exit;
	}
	
	$arrNbSousItemIDLowStock = array();
	foreach ($objCommande->stpi_getArrObjCommandeSousItem() as $objCommandeSousItem)
	{
		if (!$objCommandeSousItem->stpi_setNbCommandeID($nbCommandeID))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objSousItem->stpi_setNbID($objCommandeSousItem->stpi_getNbSousItemID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if ($objSousItem->stpi_getNbQte() > $objSousItem->stpi_getNbQteMin())
		{
			if (($objSousItem->stpi_getNbQte() - $objCommandeSousItem->stpi_getNbQte()) <= $objSousItem->stpi_getNbQteMin())
			{
				$arrNbSousItemIDLowStock[] = $objSousItem->stpi_getNbID();
			}
		}
		
		//Décrémentation de la quantité de l'item lors de l'achat
		//if (!$objSousItem->stpi_setNbQte($objSousItem->stpi_getNbQte() - $objCommandeSousItem->stpi_getNbQte()))
		//{
		//	$objBdd->stpi_rollback();
		//	exit;
		//}
		
		if (!$objSousItem->stpi_update())
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objCommandeSousItem->stpi_insert())
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objRegistreSousItem->stpi_setNbID($objRegistre->stpi_getNbID(), $objSousItem->stpi_getNbID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objRegistreSousItem->stpi_setNbQteRecu($objRegistreSousItem->stpi_getNbQteRecu + $objCommandeSousItem->stpi_getNbQte()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objRegistreSousItem->stpi_update())
		{
			$objBdd->stpi_rollback();
			exit;
		}
	}
	
	$strEmail = $objCommande->stpi_getStrCourriel();
	$strEmailSubjectAdmin = $objCommande->stpi_getObjTexte()->stpi_getArrTxt("commande") . STR_NOM_ENT . " #" . $nbCommandeID; 
	$strEmailSubject = $objCommande->stpi_getObjTexte()->stpi_getArrTxt("commande") . STR_NOM_ENT . " #" . $nbCommandeID;
	
	$objEmail->stpi_setStrEmail($strEmail);
	$objEmail->stpi_setStrFromEmail($strEmailFrom);
	$objEmail->stpi_setStrSubject($strEmailSubject);
	$objEmail->stpi_setStrMessage($strMessage);
	
	if (!$objEmail->stpi_Send())
	{
		$objBdd->stpi_rollback();
		exit;
	}
	
	$objEmail->stpi_setStrEmail($strEmailAdmin);
	$objEmail->stpi_setStrFromEmail($strEmailFromAdmin);
	$objEmail->stpi_setStrSubject($strEmailSubjectAdmin);
	$objEmail->stpi_setStrMessage($strMessageAdmin);
	
	if (!$objEmail->stpi_Send())
	{
		$objBdd->stpi_rollback();
		exit;
	}
	
	if (!empty($arrNbSousItemIDLowStock))
	{
		$objEmail->stpi_setStrEmail($strEmailAdmin);
		$objEmail->stpi_setStrFromEmail($strEmailFromAdmin);
		$objEmail->stpi_setStrSubject("Quantité des SousItem est en dessous du seuil minimum");
		$strSousItemQuantitiesMessage = "Les SousItem suivants ont une quantité sous leur quantité minimum :<br/>\n";
		foreach($arrNbSousItemIDLowStock as $nbSousItemID)
		{
			if (!$objSousItem->stpi_setNbID($nbSousItemID))
			{
				$objBdd->stpi_rollback();
				exit;
			}
			$strItemCode = $objSousItem->stpi_getStrItemCode();
			$strSousItemDesc = $objItem->stpi_getStrSousItemDesc();
			if (!empty($strItemCode))
			{
				$strSousItemQuantitiesMessage .=  $objBdd->stpi_trsBddToHTML($strItemCode) .  " - ";
			}
			$strSousItemQuantitiesMessage .= $objBdd->stpi_trsBddToHTML($strSousItemDesc);
			$strSousItemQuantitiesMessage .= " : " . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbQte()) . "<br/>\n";
		}
		$objEmail->stpi_setStrMessage($strSousItemQuantitiesMessage);
		if (!$objEmail->stpi_Send())
		{
			$objBdd->stpi_rollback();
			exit;
		}
	}
	
	if ($objBdd->stpi_commit())
	{
		print("redirect");
		unset($_SESSION["stpiObjCommandeRegistreSession"]);
		$_SESSION["stpiCommandeEmailMessage"] = "<h3>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("commande") . " #" . $nbCommandeID . "</h3><br/>" . $strMessage;
	}
	else
	{
		$objBdd->stpi_rollback();
	}
			
?>