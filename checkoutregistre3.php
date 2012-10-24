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
	$objRegistre =& $objCommande->stpi_getObjRegistre();
	$objCommandeSession = new clscommandesession();
	$objAdresse =& $objCommande->stpi_getObjAdresse();
	$objClientStore = new clsclient(1);
	$objClientRegistre = new clsclient();
	$objClient =& $objCommande->stpi_getObjClient();
	$objUser = new clsuser();
	$objCountry =& $objAdresse->stpi_getObjCountry();
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
			
			$objCountry->stpi_affJsSelectCountryPublic();
			$objAdresse->stpi_affJsAdresseLivraisonToCommandeRegistre();
						
			print("<h2>" . $objTexte->stpi_getArrTxt("checkout3") . "</h2>\n");
			
			if (isset($_SESSION["stpiObjCommandeRegistreSession"]))
			{
				if ($objCommandeSession = $objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession())
				{
					if ($objRegistre->stpi_setNbID($objCommandeSession->stpi_getNbRegistreID()))
					{
						$objClientRegistre->stpi_setNbID($objRegistre->stpi_getNbClientID());						
					}
					
					$arrObjAdresseSession = $objCommandeSession->stpi_getArrObjAdresseSession();
					
					if (isset($arrObjAdresseSession[2]))
					{
						$objAdresse->stpi_setNbTypeAdresseID(2);
						$objAdresse->stpi_setStrNom($arrObjAdresseSession[2]->stpi_getStrNom());
						$objAdresse->stpi_setStrPrenom($arrObjAdresseSession[2]->stpi_getStrPrenom());
						$objAdresse->stpi_setStrCie($arrObjAdresseSession[2]->stpi_getStrCie());
						$objAdresse->stpi_setStrAdresse($arrObjAdresseSession[2]->stpi_getStrAdresse());
						$objAdresse->stpi_setStrVille($arrObjAdresseSession[2]->stpi_getStrVille());
						$objAdresse->stpi_setStrProvinceID($arrObjAdresseSession[2]->stpi_getStrProvinceID());
						$objAdresse->stpi_setStrCountryID($arrObjAdresseSession[2]->stpi_getStrCountryID());
						$objAdresse->stpi_setStrCodePostal($arrObjAdresseSession[2]->stpi_getStrCodePostal());
					}
					elseif (isset($arrObjAdresseSession[1]))
					{
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
					
					$strNom = "";
					$strPrenom = "";
					$strCie = "";
					$strAdresse = "";
					$strVille = "";
					$strProvinceID = "";
					$strCountryID = "";
					$strCodePostal = "";
					
					if (($objAdresse->stpi_getStrAdresse() != $objClientStore->stpi_getStrAdresse() || $objAdresse->stpi_getStrCodePostal() != $objClientStore->stpi_getStrCodePostal()) && ($objAdresse->stpi_getStrAdresse() != $objClientRegistre->stpi_getStrAdresse() || $objAdresse->stpi_getStrCodePostal() != $objClientRegistre->stpi_getStrCodePostal()))
					{
						$strNom = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrNom());
						$strPrenom = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrPrenom());
						$strCie = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrCie());
						$strAdresse = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrAdresse());
						$strVille = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrVille());
						$strProvinceID = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrProvinceID());
						$strCountryID = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrCountryID());
						$strCodePostal = $objBody->stpi_trsInputToHTML($objAdresse->stpi_getStrCodePostal());
					}
					
					print("<table style=\"padding: 10px;\" width=\"100%\" >\n");
					print("<tr>\n");
					
					print("<td>\n");					
					print("<table>\n");
					
					print("<tr>\n");
					print("<td colspan=\"2\" style=\"text-align: left; vertical-align: top;\" >\n");
					print("<input type=\"radio\"");
					if (($objAdresse->stpi_getStrAdresse() != $objClientStore->stpi_getStrAdresse() || $objAdresse->stpi_getStrCodePostal() != $objClientStore->stpi_getStrCodePostal()) && ($objAdresse->stpi_getStrAdresse() != $objClientRegistre->stpi_getStrAdresse() || $objAdresse->stpi_getStrCodePostal() != $objClientRegistre->stpi_getStrCodePostal()))
					{
						print(" checked=\"checked\"");
					}
					print(" name=\"stpi_Delivery\" id=\"stpi_DeliverTo\" onclick=\"stpi_DeliverTo()\" value=\"stpi_DeliverTo\" /><b>" . $objTexte->stpi_getArrTxt("deliverto") . " :</b>\n");
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
					print("</table>\n");
					print("</td>\n");
					
					print("<td style=\"text-align: left; vertical-align: top;\" >\n");
					print("<input type=\"radio\"");
					if ($objAdresse->stpi_getStrAdresse() == $objClientStore->stpi_getStrAdresse() && $objAdresse->stpi_getStrCodePostal() == $objClientStore->stpi_getStrCodePostal())
					{
						print(" checked=\"checked\"");
					}
					print(" name=\"stpi_Delivery\" id=\"stpi_InStorePickup\" onclick=\"stpi_InStorePickup()\" value=\"stpi_InStorePickup\" /><b>" . $objTexte->stpi_getArrTxt("instorepickup") . " :</b><br/>\n");
					print("<p style=\"text-align: center;\" >\n");
					$strCie = $objClientStore->stpi_getStrCie();
					if (!empty($strCie))
					{
						print($objBdd->stpi_trsBddToHTML($strCie) . "<br/>\n");
					}
					print($objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrAdresse()) . "<br/>\n");
					$strProvinceID = $objClientStore->stpi_getStrProvinceID();
					if(empty($strProvinceID))
					{											
						print($objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrCountryID()) . "<br/>\n");
					}
					else
					{
						print($objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrProvinceID()) . ", " . $objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrCountryID()) . "<br/>\n");
					}
					print($objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrCodePostal()) . "<br/>\n");
					print($objBdd->stpi_trsBddToHTML($objClientStore->stpi_getStrTel()) . "\n");
					print("</p>\n");
										
					print("<input type=\"radio\"");
					if ($objAdresse->stpi_getStrAdresse() == $objClientRegistre->stpi_getStrAdresse() && $objAdresse->stpi_getStrCodePostal() == $objClientRegistre->stpi_getStrCodePostal())
					{
						print(" checked=\"checked\"");
					} 
					print(" name=\"stpi_Delivery\" id=\"stpi_DeliverToGiftListOwner\" onclick=\"stpi_DeliverToGiftListOwner()\" value=\"stpi_DeliverToGiftListOwner\" /><b>" . $objTexte->stpi_getArrTxt("delivertogiftlistowner") . "</b><br/>\n");
					print("</td>\n");
					print("</tr>\n");
					
					print("<tr>\n");
					print("<td colspan=\"2\" style=\"text-align: right; vertical-align: top;\" >\n");
					print("<span id=\"stpi_AddAdresseLivraisonToCommandeRegistre\"></span><br/>\n");
					print("<a href=\"checkoutregistre2.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("backto2") . "</a> - <input id=\"stpi_AddAdresseLivraisonToCommandeRegistreButton\" type=\"button\" onclick=\"stpi_AddAdresseLivraisonToCommandeRegistre()\" value=\"" . $objTexte->stpi_getArrTxt("continue") . "\"/><br/>\n");
					print("</td>\n");
					print("</tr>\n");
					
					print("</table>\n");
					print("<br/>");
					print("<br/>");
					
					if ($objAdresse->stpi_getStrAdresse() == $objClientStore->stpi_getStrAdresse() && $objAdresse->stpi_getStrCodePostal() == $objClientStore->stpi_getStrCodePostal())
					{
						print("<script type=\"text/javascript\">\n");
						?>
						<!--
						stpi_InStorePickup();
						-->
						<?php
						print("</script>\n");
					}
					elseif ($objAdresse->stpi_getStrAdresse() == $objClientRegistre->stpi_getStrAdresse() && $objAdresse->stpi_getStrCodePostal() == $objClientRegistre->stpi_getStrCodePostal())
					{
						print("<script type=\"text/javascript\">\n");
						?>
						<!--
						stpi_DeliverToGiftListOwner();
						-->
						<?php
						print("</script>\n");
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