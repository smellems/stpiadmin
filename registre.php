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
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/registre");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageEncrypted();
	
	$objClient = new clsclient();
	$objUser = new clsuser();
	$objRegistre = new clsregistre();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();
			
	$nbClientID = 0;

	if (isset($_SESSION["stpiObjUser"]))
	{
		if ($objUser = $objUser->stpi_getObjUserFromSession())
		{
			if ($objUser->stpi_getNbTypeUserID() == 2)
			{
				if ($objClient->stpi_chkNbID($objUser->stpi_getNbID()))
				{
					$nbClientID = $objUser->stpi_getNbID();
				}
			}
		}
	}
	
	print("<p>" . $objTexte->stpi_getArrTxt("description") . "</p>\n");			
?>		
	
	<table>
	<tr>
		<td width="50%" style="text-align: left; vertical-align: top;">
			<?php
				print("<h3>" . $objTexte->stpi_getArrTxt("coderegistre") . "</h3>\n");
				
				print("<p>" . $objTexte->stpi_getArrTxt("coderegistredesc") . "</p>\n");
				
				print("<table>\n");
	
				print("<tr>\n");
				print("<td style=\"text-align: right; vertical-align: top;\" >\n");
				print("<p>" . $objTexte->stpi_getArrTxt("entrercode") . " :</p>\n");
				print("</td>\n");
				print("<td style=\"text-align: left; vertical-align: top;\" >\n");
				print("<input type=\"text\" maxlength=\"10\" size=\"15\" id=\"strRegistreCode\" value=\"\" /><br/>\n");
				print("<input type=\"button\" onclick=\"window.location='./shopregistre.php?l=" . LG . "&amp;strRegistreCode=' + document.getElementById('strRegistreCode').value\" value=\"" . $objTexte->stpi_getArrTxt("shop") . "\"/>\n");
				print("</td>\n");
				print("</tr>\n");
								
				print("</table>\n");
				
				print("<br/>\n");
				
				print("<h3>" . $objTexte->stpi_getArrTxt("monregistre") . "</h3>\n");
				
				
				if ($nbClientID != 0)
				{
					if ($objClient->stpi_setNbID($nbClientID))
					{
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
					}
				
					print("<a style=\"padding: 0px 0px 0px 20px;\" href=\"./registrepublic.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("creerregistre") . "</a>\n");
				}
				else
				{
					print("<a style=\"padding: 0px 0px 0px 20px;\" href=\"./login.php?l=" . LG . "&amp;redirect=registre\" >" . $objTexte->stpi_getArrTxt("connectermodifierregistre") . "</a>\n");
					print("<br/><br/>\n");
					print("<a style=\"padding: 0px 0px 0px 20px;\" href=\"./login.php?l=" . LG . "&amp;redirect=registrepublic\" >" . $objTexte->stpi_getArrTxt("connectercreerregistre") . "</a>\n");						
				}
			?>
		</td>
		<td width="50%" style="text-align: left; vertical-align: top;">
			
			<?php
				print("<h3>" . $objTexte->stpi_getArrTxt("pourquoiregistre") . "</h3>\n");
				
				print("<ul>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("pourquoi1"));
				print("</li>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("pourquoi2"));
				print("</li>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("pourquoi3"));
				print("</li>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("pourquoi4"));
				print("</li>\n");
				print("</ul>\n");
				
				print("<br/>\n");
				
				print("<h3>" . $objTexte->stpi_getArrTxt("commentregistre") . "</h3>\n");
				
				print("<p>" . $objTexte->stpi_getArrTxt("commentregistredesc") . "</p>\n");
				
			?>
		</td>
	</tr>
	</table>
<?php
// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
