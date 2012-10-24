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
require_once("./stpiadmin/includes/classes/commande/clsadresse.php");
require_once("./stpiadmin/includes/classes/content/clsbody.php");

$strPage = basename($_SERVER["SCRIPT_NAME"]);

$objMotd = new clsmotd();
$objTexte = new clstexte("./texte/commande");
$objBody = new clsbody();
$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
$objJavaScript = new clsjavascript();
$objMenu = new clsmenu($strPage);
$objFooter = new clsfooter();
$objBdd = clsBdd::singleton();
$objLock = new clslock($strPage, "login.php");

$objLock->stpi_run();

$objAdresseFacturation = new clsadresse();
$objAdresseLivraison = new clsadresse();
$objBody = new clsbody();
$objClient = new clsclient();
$objUser = new clsuser();
$objCommande = new clscommande();
$objCommandeSousItem =& $objCommande->stpi_getObjCommandeSousItem();
$objStatutCommande =& $objCommande->stpi_getObjStatutCommande();
$objMethodPaye =& $objCommande->stpi_getObjMethodPaye();
$objCountry =& $objClient->stpi_getObjCountry();
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

<div id="topmenu"><?php
$objMenu->stpi_affPublicMenu();
?></div>

<div id="container">
<div id="fullcontent"><?php

$objMotd->stpi_affPublic();

$objUser = $objUser->stpi_getObjUserFromSession();

if (isset($_GET["nbCommandeID"]))
{
	if ($objUser->stpi_getNbTypeUserID() == 2)
	{
		if ($objClient->stpi_setNbID($objUser->stpi_getNbID()))
		{
			if ($arrNbCommandeID = $objClient->stpi_selNbCommandeID())
			{
				if (in_array($_GET["nbCommandeID"],$arrNbCommandeID))
				{
					if ($objCommande->stpi_setNbID($_GET["nbCommandeID"]))
					{
						if ($objCommande->stpi_getNbTypeCommandeID() == 1)
						{
							print("<h2>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("commande") . " # : " . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getNbID()) . "</h2>\n");

							print("<h3>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("date") . " : " . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getDtEntryDate()) . "</h3>\n");

							if ($objStatutCommande->stpi_setNbID($objCommande->stpi_getNbStatutCommandeID()))
							{
								$objStatutCommande->stpi_setObjStatutCommandeLgFromBdd();
								$objStatutCommandeLg =& $objStatutCommande->stpi_getObjStatutCommandeLg();
								print("<h3>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("statutcommande") . " : " . $objBdd->stpi_trsBddToHTML($objStatutCommandeLg->stpi_getStrName()) . "</h3>\n");
							}
							
							if ($objMethodPaye->stpi_setNbID($objCommande->stpi_getNbMethodPayeID()))
							{
								$objMethodPaye->stpi_setObjMethodPayeLgFromBdd();
								$objMethodPayeLg =& $objMethodPaye->stpi_getObjMethodPayeLg();
								print("<h3>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("methodpaye") . " : " . $objBdd->stpi_trsBddToHTML($objMethodPayeLg->stpi_getStrName()) . "</h3>\n");
							}
							
							print("<br/>\n");
							
							$strCodeSuivi = $objCommande->stpi_getStrCodeSuivi();
							if (!empty($strCodeSuivi))
							{
								print("<h3>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("codesuivi") . " : " . $objBdd->stpi_trsBddToHTML($strCodeSuivi) . "</h3>\n");
							}
							
							$dtShipped = $objCommande->stpi_getDtShipped();
							if (!empty($dtShipped))
							{
								print("<h3>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("dateshipped") . " : " . $objBdd->stpi_trsBddToHTML($dtShipped) . "</h3>\n");
							}
							
							$dtArrived = $objCommande->stpi_getDtArrived();
							if (!empty($dtArrived))
							{
								print("<h3>" . $objCommande->stpi_getObjTexte()->stpi_getArrTxt("datearrived") . " : " . $objBdd->stpi_trsBddToHTML($dtArrived) . "</h3>\n");
							}

							if ($arrNbSousItemID = $objCommande->stpi_selNbSousItemID())
							{
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
								foreach ($arrNbSousItemID as $nbSousItemID)
								{
									if ($objCommandeSousItem->stpi_setNbID($objCommande->stpi_getNbID(), $nbSousItemID))
									{
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
										print($objBdd->stpi_trsBddToHTML($objCommandeSousItem->stpi_getNbQte()) . " X " . $objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbPrix())) . " $");
										print("</td>\n");
										print("<td style=\"text-align: right;\" >\n");
										print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommandeSousItem->stpi_getNbQte() * $objCommandeSousItem->stpi_getNbPrix())) . " $");
										print("</td>\n");
										print("</tr>\n");
									}
								}
									
								print("<tr>\n");
								print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
								print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("soustotal"));
								print("</td>\n");
								print("<td style=\"text-align: right;\" >\n");
								print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommande->stpi_getNbSousTotal()) . " $"));
								print("</td>\n");
								print("</tr>\n");
									
								$nbPrixRabais = $objCommande->stpi_getNbPrixRabais();
									
								if ($nbPrixRabais != 0)
								{
									print("<tr>\n");
									print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
									print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixrabais"));
									print("</td>\n");
									print("<td style=\"text-align: right;\" >\n");
									print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixRabais)) . " $");
									print("</td>\n");
									print("</tr>\n");
								}
									
								$nbPrixShipping = $objCommande->stpi_getNbPrixShipping();
									
								if ($nbPrixShipping != 0)
								{
									print("<tr>\n");
									print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
									print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixshipping"));
									print("</td>\n");
									print("<td style=\"text-align: right;\" >\n");
									print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixShipping)) . " $");
									print("</td>\n");
									print("</tr>\n");
								}
									
								$nbPrixTaxes = $objCommande->stpi_getNbPrixTaxes();
									
								if ($nbPrixTaxes != 0)
								{
									print("<tr>\n");
									print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
									print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("prixtaxes"));
									print("</td>\n");
									print("<td style=\"text-align: right;\" >\n");
									print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($nbPrixTaxes)) . " $");
									print("</td>\n");
									print("</tr>\n");
								}
									
								print("<tr>\n");
								print("<td colspan=\"2\" style=\"text-align: right;\" >\n");
								print($objCommande->stpi_getObjTexte()->stpi_getArrTxt("total"));
								print("</td>\n");
								print("<td style=\"text-align: right;\" >\n");
								print($objBdd->stpi_trsBddToHTML($objBody->stpi_trsNbToPrix($objCommande->stpi_getNbTotal())) . " $");
								print("</td>\n");
								print("</tr>\n");

								print("</table>\n");
									
								if ($objAdresseFacturation->stpi_setNbID($objCommande->stpi_getNbID(), 1))
								{
									if ($objAdresseLivraison->stpi_setNbID($objCommande->stpi_getNbID(), 2))
									{
										print("<table style=\"padding: 10px;\" >\n");
										if ($objAdresseFacturation->stpi_getStrAdresse() == $objAdresseLivraison->stpi_getStrAdresse() && $objAdresseFacturation->stpi_getStrCodePostal() == $objAdresseLivraison->stpi_getStrCodePostal())
										{
											print("<tr>\n");
											print("<td style=\"text-align: center;\" >\n");
											print("<h3>" . $objTexte->stpi_getArrTxt("billingdelivery") . "</h3>");
											print("</td>\n");
											print("</tr>\n");
											print("<tr>\n");
											print("<td style=\"text-align: center;\" >\n");
											$strCie = $objAdresseFacturation->stpi_getStrCie();
											if (!empty($strCie))
											{
												print($objBdd->stpi_trsBddToHTML($strCie) . "<br/>\n");
											}
											print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrNom())  . "<br/>\n");
											print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrAdresse()) . "<br/>\n");
											$strProvinceID = $objAdresseFacturation->stpi_getStrProvinceID();
											if(empty($strProvinceID))
											{											
												print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
											}
											else
											{
												print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrProvinceID()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
											}
											print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrCodePostal()) . "<br/>\n");
											print("</td>\n");
											print("</tr>\n");												
										}
										else
										{
											print("<tr>\n");
											print("<td style=\"text-align: center;\" >\n");
											print("<h3>" . $objTexte->stpi_getArrTxt("billing") . "</h3>");
											print("</td>\n");
											print("<td style=\"text-align: center;\" >\n");
											print("<h3>" . $objTexte->stpi_getArrTxt("delivery") . "</h3>");
											print("</td>\n");
											print("</tr>\n");
											print("<tr>\n");
											print("<td style=\"text-align: center;\" >\n");
											$strCie = $objAdresseFacturation->stpi_getStrCie();
											if (!empty($strCie))
											{
												print($objBdd->stpi_trsBddToHTML($strCie) . "<br/>\n");
											}
											print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrNom())  . "<br/>\n");
											print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrAdresse()) . "<br/>\n");
											$strProvinceID = $objAdresseFacturation->stpi_getStrProvinceID();
											if(empty($strProvinceID))
											{											
												print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
											}
											else
											{
												print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrProvinceID()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrCountryID()) . "<br/>\n");
											}
											print($objBdd->stpi_trsBddToHTML($objAdresseFacturation->stpi_getStrCodePostal()) . "<br/>\n");
											print("</td>\n");
											print("<td style=\"text-align: center;\" >\n");
											$strCie = $objAdresseLivraison->stpi_getStrCie();
											if (!empty($strCie))
											{
												print($objBdd->stpi_trsBddToHTML($strCie) . "<br/>\n");
											}
											print($objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrPrenom()) . " " . $objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrNom())  . "<br/>\n");
											print($objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrAdresse()) . "<br/>\n");
											$strProvinceID = $objAdresseLivraison->stpi_getStrProvinceID();
											if(empty($strProvinceID))
											{											
												print($objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrCountryID()) . "<br/>\n");
											}
											else
											{
												print($objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrVille()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrProvinceID()) . ", " . $objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrCountryID()) . "<br/>\n");
											}
											print($objBdd->stpi_trsBddToHTML($objAdresseLivraison->stpi_getStrCodePostal()) . "<br/>\n");
											print("</td>\n");
											print("</tr>\n");
										}
										print("</table>\n");								
									}	
								}									
							}
						}
					}
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notyours") . "</span><br/>\n");
				}
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notyours") . "</span><br/>\n");
			}
		}
	}
}
?></div>

<div class="doubleclear"></div>
</div>

<div id="bottommenu"><?php
$objMenu->stpi_affPublicMenu();
?></div>

<div id="footer"><?php
$objFooter->stpi_affPublicFooter();
?></div>

</body>

</html>
