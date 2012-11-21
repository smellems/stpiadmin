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
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/registre");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_run();
	
	$objUser = new clsuser();
	$objRegistre = new clsregistre();
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objItem =& $objRegistre->stpi_getObjRegistreSousItem()->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objCountry =& $objClient->stpi_getObjCountry();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();

	$objJavaScript->stpi_affArrLang();
	$objJavaScript->stpi_affNoAjax();
	$objJavaScript->stpi_affCreateXmlHttp();
	$objJavaScript->stpi_affNoJavaScript();
	
	$objRegistre->stpi_affJsAddPublic();
	$objRegistre->stpi_affJsEditPublic();
	$objRegistreSousItem->stpi_affJsDelSousItemFromRegistre();
	$objRegistre->stpi_affJsSendRegistreInvitationPublic();
	
	$objMotd->stpi_affPublic();

	$objUser = $objUser->stpi_getObjUserFromSession();
	
	if ($objUser->stpi_getNbTypeUserID() == 2)
	{
		if ($objClient->stpi_chkNbID($objUser->stpi_getNbID()))
		{					
			print("<table>\n");
			print("<tr>\n");
			print("<td width=\"70%\" style=\"text-align: left; vertical-align: top;\">\n");
			if ($_GET["nbRegistreID"])
			{
				if ($objRegistre->stpi_setNbID($_GET["nbRegistreID"]))
				{
					if ($objUser->stpi_getNbID() == $objRegistre->stpi_getNbClientID())
					{
						print("<h2>\n");
						print($objTexte->stpi_getArrTxt("editregistre"));
						print("</h2>\n");
						$objRegistre->stpi_affEditPublic();
						print("<p><a href=\"./shopregistre.php?l=" . LG . "&amp;strRegistreCode=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . "\">" . $objTexte->stpi_getArrTxt("visionnerregistre") . "</a></p><br/>\n");
						$objRegistre->stpi_affSendRegistreInvitationPublic();
					}
					else
					{
						print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notyours") . "</span><br/>\n");
					}
				}
			}
			else
			{
				print("<h2>\n");
				print($objTexte->stpi_getArrTxt("addregistre"));
				print("</h2>\n");
				print("<p>" . $objTexte->stpi_getArrTxt("instruction1") . "</p>\n");
				$objRegistre->stpi_affAddPublic();
			}
			print("</td>\n");
			print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\">\n");
			print("<ul>\n");
			print("<li>" . $objTexte->stpi_getArrTxt("instruction2") . "</li>\n");
			print("<li>" . $objTexte->stpi_getArrTxt("instruction3") . "</li>\n");
			print("<li>" . $objTexte->stpi_getArrTxt("instruction4") . "</li>\n");
			print("</ul>\n");
			print("</td>\n");
			print("</tr>\n");
			print("</table><br/><br/>\n");
		}
	}
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
