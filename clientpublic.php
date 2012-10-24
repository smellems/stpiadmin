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
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/client");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_run();
	
	$objClient = new clsclient();
	$objUser = new clsuser();
	$objCommande = new clscommande();
	$objRegistre =& $objCommande->stpi_getObjRegistre();
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
	
	<div id="topmenu">
		<?php
			$objMenu->stpi_affPublicMenu();
		?>		
	</div>
	
	<div id="container">		
		<div id="fullcontent">
		<?php
			$objJavaScript->stpi_affArrLang();
			$objJavaScript->stpi_affNoAjax();
			$objJavaScript->stpi_affCreateXmlHttp();
			$objJavaScript->stpi_affNoJavaScript();
			
			$objClient->stpi_affJsEditPublic();
			$objCountry->stpi_affJsSelectCountryPublic();
			
			$objMotd->stpi_affPublic();

			$objUser = $objUser->stpi_getObjUserFromSession();
			
			if ($objUser->stpi_getNbTypeUserID() == 2)
			{
				if ($objClient->stpi_setNbID($objUser->stpi_getNbID()))
				{
					print("<table width=\"100%\">\n");
					print("<tr>\n");
					print("<td width=\"60%\" style=\"text-align: left; vertical-align: top;\">\n");
					$objClient->stpi_affEditPublic();
					print("</td>\n");
					print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\">\n");
					if ($arrNbCommandeID = $objClient->stpi_selNbCommandeID())
					{
						print("<h2>\n");
						print($objTexte->stpi_getArrTxt("order"));
						print("</h2>\n");
						
						print("<ul>\n");
						foreach ($arrNbCommandeID as $nbCommandeID)
						{
							if ($objCommande->stpi_setNbID($nbCommandeID))
							{
								print("<li>\n");
								print("<a href=\"./commandepublic.php?l=" . LG . "&amp;nbCommandeID=" . $objBdd->stpi_trsBddToHTML($nbCommandeID) . "\" >");
								print($objBdd->stpi_trsBddToHTML($objCommande->stpi_getDtEntryDate()));
								print("</a>\n");
								print(" : " . $objBdd->stpi_trsBddToHTML($objCommande->stpi_selNbSousTotal()) . " $");
								print("</li>\n");
							}
						}
						print("</ul>\n");
					}
					
					print("<h2>\n");
					print($objTexte->stpi_getArrTxt("giftlist"));
					print("</h2>\n");
					
					if ($arrNbRegistreID = $objClient->stpi_selNbRegistreID())
					{
						print("<ul>\n");
						foreach ($arrNbRegistreID as $nbRegistreID)
						{
							if ($objRegistre->stpi_setNbID($nbRegistreID))
							{
								print("<li>\n");
								print($objBdd->stpi_trsBddToHTML($objRegistre->stpi_getDtFin()));
								print(" : ");
								if ($objRegistre->stpi_getBoolActif())
								{
									print("(" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getObjTexte()->stpi_getArrTxt("actif")) . ") ");
								}
								print($objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()));
								print(" <a href=\"./registrepublic.php?l=" . LG . "&amp;nbRegistreID=" . $objBdd->stpi_trsBddToHTML($nbRegistreID) . "\" >");
								print($objRegistre->stpi_getObjTexte()->stpi_getArrTxt("edit"));
								print("</a>");
								print("</li>\n");
							}
						}
						print("</ul>\n");
					}
					
					print("<a style=\"padding: 0px 0px 0px 20px;\" href=\"./registrepublic.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("creategiftlist") . "</a>\n");
					
					print("</td>\n");
					print("</tr>\n");
					print("</table>\n");
				}
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