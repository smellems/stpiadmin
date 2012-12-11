<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/client/clsclient.php");
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
	$objAdresse =& $objCommande->stpi_getObjAdresse();
	$objClient =& $objCommande->stpi_getObjClient();
	$objUser = new clsuser();
	$objCountry =& $objAdresse->stpi_getObjCountry();

	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("headtitre"));

	// <!-- MainContentStart -->
	$objJavaScript->stpi_affArrLang();
	$objJavaScript->stpi_affCreateXmlHttp();
	$objJavaScript->stpi_affNoAjax();

	$objJavaScript->stpi_affNoJavaScript();

	$objMotd->stpi_affPublic();
			
	$objCountry->stpi_affJsSelectCountryPublic();
	$objAdresse->stpi_affJsAdresseFacturationToCommande();
				
	print("<h2>" . $objTexte->stpi_getArrTxt("checkout2") . "</h2>\n");
	
	if (isset($_SESSION["stpiObjCommandeSession"]))
	{
		if ($objCommandeSession = $objCommandeSession->stpi_getObjCommandeSessionFromSession())
		{
			$arrObjAdresseSession = $objCommandeSession->stpi_getArrObjAdresseSession();
			
			if (isset($arrObjAdresseSession[1]))
			{
				$objCommande->stpi_setStrTel($objCommandeSession->stpi_getStrTel());
				$objCommande->stpi_setStrCourriel($objCommandeSession->stpi_getStrCourriel());			
				$objAdresse->stpi_setNbTypeAdresseID(1);
				$objAdresse->stpi_setStrNom($arrObjAdresseSession[1]->stpi_getStrNom());
				$objAdresse->stpi_setStrPrenom($arrObjAdresseSession[1]->stpi_getStrPrenom());
				$objAdresse->stpi_setStrCie($arrObjAdresseSession[1]->stpi_getStrCie());
				$objAdresse->stpi_setStrAdresse($arrObjAdresseSession[1]->stpi_getStrAdresse());
				$objAdresse->stpi_setStrVille($arrObjAdresseSession[1]->stpi_getStrVille());
				$objAdresse->stpi_setStrProvinceID($arrObjAdresseSession[1]->stpi_getStrProvinceID());
				$objAdresse->stpi_setStrCountryID($arrObjAdresseSession[1]->stpi_getStrCountryID());
				$objAdresse->stpi_setStrCodePostal($arrObjAdresseSession[1]->stpi_getStrCodePostal());
			}
			elseif (isset($_SESSION["stpiObjUser"]))
			{
				if ($objUser = $objUser->stpi_getObjUserFromSession())
				{
					if ($objUser->stpi_getNbTypeUserID() == 2)
					{
						if ($objClient->stpi_setNbID($objUser->stpi_getNbID()))
						{
							$objCommande->stpi_setStrTel($objClient->stpi_getStrTel());
							$objCommande->stpi_setStrCourriel($objClient->stpi_getStrCourriel());
							$objAdresse->stpi_setNbTypeAdresseID(1);
							$objAdresse->stpi_setStrNom($objClient->stpi_getStrNom());
							$objAdresse->stpi_setStrPrenom($objClient->stpi_getStrPrenom());
							$objAdresse->stpi_setStrCie($objClient->stpi_getStrCie());
							$objAdresse->stpi_setStrAdresse($objClient->stpi_getStrAdresse());
							$objAdresse->stpi_setStrVille($objClient->stpi_getStrVille());
							$objAdresse->stpi_setStrProvinceID($objClient->stpi_getStrProvinceID());
							$objAdresse->stpi_setStrCountryID($objClient->stpi_getStrCountryID());
							$objAdresse->stpi_setStrCodePostal($objClient->stpi_getStrCodePostal());
						}									
					}
				}
			}
			
			$strTel = $objBody->stpi_trsInputToHTML($objCommande->stpi_getStrTel());
			$strCourriel = $objBody->stpi_trsInputToHTML($objCommande->stpi_getStrCourriel());
			$strNom = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrNom());
			$strPrenom = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrPrenom());
			$strCie = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrCie());
			$strAdresse = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrAdresse());
			$strVille = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrVille());
			$strProvinceID = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrProvinceID());
			$strCountryID = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrCountryID());
			$strCodePostal = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrCodePostal());
			
			
			print("<table style=\"padding: 10px;\" >\n");

			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("courriel") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strCourriel))
			{
				print("<input type=\"text\" maxlength=\"200\" size=\"30\" id=\"strCourriel\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"200\" size=\"30\" id=\"strCourriel\" value=\"" . $strCourriel . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
			
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("tel") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strTel))
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strTel\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strTel\" value=\"" . $strTel . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
								
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("nom") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strNom))
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"" . $strNom . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
	
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("prenom") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strPrenom))
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"" . $strPrenom . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
	
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("cie") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strCie))
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strCie\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strCie\" value=\"" . $strCie . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
			
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("adresse") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strAdresse))
			{
				print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strAdresse\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strAdresse\" value=\"" . $strAdresse . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
			
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("ville") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strVille))
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille\" value=\"" . $strVille . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
			
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("country") . " / " . $objClient->stpi_getObjTexte()->stpi_getArrTxt("province") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strCountryID))
			{
				$objCountry->stpi_affSelectCountryShippable(NULL, NULL, 0, 0);						
			}
			else
			{
				if (empty($strProvinceID))
				{
					$objCountry->stpi_affSelectCountryShippable($strCountryID, NULL, 0, 0);
				}
				else
				{
					$objCountry->stpi_affSelectCountryShippable($strCountryID, $strProvinceID, 0, 0);
				}
			}
			print("</td>\n");
			print("</tr>\n");
			
			print("<tr>\n");
			print("<td style=\"text-align: right; vertical-align: top;\" >\n");
			print($objClient->stpi_getObjTexte()->stpi_getArrTxt("codepostal") . " :\n");
			print("</td>\n");
			print("<td style=\"text-align: left; vertical-align: top;\" >\n");
			if (empty($strCodePostal))
			{
				print("<input type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal\" value=\"\" />\n");
			}
			else
			{
				print("<input type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal\" value=\"" . $strCodePostal . "\" />\n");
			}
			print("</td>\n");
			print("</tr>\n");
			
			print("<tr>\n");
			print("<td colspan=\"2\" style=\"text-align: right; vertical-align: top;\" >\n");
			print("<span id=\"stpi_AddAdresseFacturationToCommande\"></span><br/>\n");
			print("<a href=\"checkout1.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("backto1") . "</a> - <input id=\"stpi_AddAdresseFacturationToCommandeButton\" type=\"button\" onclick=\"stpi_AddAdresseFacturationToCommande()\" value=\"" . $objTexte->stpi_getArrTxt("continue") . "\"/><br/>\n");
			print("</td>\n");
			print("</tr>\n");
			
			print("</table>\n");
			print("<br/>");
			print("<br/>");
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
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
