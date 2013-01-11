<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");	
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	require_once("./stpiadmin/includes/classes/item/clsitem.php");
	require_once("./stpiadmin/includes/classes/news/clsnews.php");
	require_once("./stpiadmin/includes/classes/banniere/clsbanniere.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	require_once("./stpiadmin/includes/classes/page/clspagepublic.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/home");
	$objBody = new clsbody();
  $objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageNotEncrypted();
	
	$objUser = new clsuser();
	$objItem = new clsitem();
	$objRegistre = new clsregistre();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objNews = new clsnews();
	$objBanniere = new clsbanniere();
	$objItem = new clsitem();

	$objPage = new clspagepublic();
	
	$boolRegistre = 0;
	if ($objUser = $objUser->stpi_getObjUserFromSession())
	{
		if ($objUser->stpi_getNbTypeUserID() == 2)
		{
			if ($objClient->stpi_setNbID($objUser->stpi_getNbID()))
			{
				if ($nbRegistreID = $objClient->stpi_selNbRegistreIDPublic())
				{
					if ($objRegistre->stpi_setNbID($nbRegistreID))
					{
						if ($objUser->stpi_getNbID() == $objRegistre->stpi_getNbClientID())
						{
							$boolRegistre = 1;
						}
					}
				}
			}
		}
	}

	$objPage->stpi_setNbID(4);
	$objPage->stpi_setObjPageLgFromBdd();
	$objPageLg = $objPage->stpi_getObjPageLg();
	$objHead = new clshead(STR_NOM_ENT . " - " . $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrTitre()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrKeywords()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrDesc()));
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();
	print("<div class=\"span-2 float-right\">\n");

	print("<h3>" . $objTexte->stpi_getArrTxt("coderegistre") . "</h3>\n");
	print($objTexte->stpi_getArrTxt("entrercode") . " : <input type=\"text\" maxlength=\"10\" size=\"10\" id=\"strRegistreCode\" value=\"\" /><br/>\n");
	print("<input type=\"button\" onclick=\"window.location='./shopregistre.php?l=" . LG . "&amp;strRegistreCode=' + document.getElementById('strRegistreCode').value\" value=\"" . $objTexte->stpi_getArrTxt("shop") . "\"/><br/>\n");
	print("<br/>\n");
	$objNews->stpi_affPublic(2);
				
	print("</div>\n");
	
	$objBanniere->stpi_affHomePublic();
	
	print($objBdd->stpi_trsBddHtmlToHTML($objPageLg->stpi_getStrContent()));
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
