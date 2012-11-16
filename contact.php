<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	require_once("./stpiadmin/includes/classes/page/clspagepublic.php");
	require_once("./stpiadmin/includes/classes/email/clsemail.php");
	require_once("./stpiadmin/includes/classes/img/clscaptcha.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/contact");
	$objBody = new clsbody();
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageEncrypted();
	$objPage = new clspagepublic();
	$objEmail = new clsemail();
	$objCaptcha = new clscaptcha();
	
	$objPage->stpi_setNbID(1);
	$objPage->stpi_setObjPageLgFromBdd();
	$objPageLg = $objPage->stpi_getObjPageLg();
	$objHead = new clshead(STR_NOM_ENT . " - " . $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrTitre()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrKeywords()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrDesc()));
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objPageLg->stpi_getStrTitre());

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();

	print($objBdd->stpi_trsBddHtmlToHTML($objPageLg->stpi_getStrContent()));
	
	print("<center>\n");			
					
	$objEmail->stpi_setStrEmail(STR_EMAIL_TO);
	$objEmail->stpi_setStrSubject("Info request from web site");
	$gonogo = true;
					
	print("<h2 style=\"text-align: center;\">" . $objTexte->stpi_getArrTxt("titlesendmail") . "</h2>\n");
	
	print("<form action=\"./" . $strPage . "?l=" . LG . "&amp;op=send\" method=\"post\">\n");
	
	print("<p>\n");
	print($objTexte->stpi_getArrTxt("youremail") . "<br/>\n");
	print("<input type=\"text\" size=\"50\" name=\"txtemail\" value=\"");
	if ($_POST["txtemail"])
	{
		print($objBody->stpi_trsInputToHTML($_POST["txtemail"]));
	}
	print("\" /><br/>\n");
	if ($_GET["op"] == "send")
	{
		if (!$objEmail->stpi_setStrFromEmail($_POST["txtemail"]))
		{
			$gonogo = false;				
		}
	}
	print("</p>\n");
	
	
	print("<p>\n");
	print($objTexte->stpi_getArrTxt("message") . "<br/>\n");
	print("<textarea name=\"txtmessage\" rows=\"5\" cols=\"50\">");
	if ($_POST["txtmessage"])
	{
		print($objBody->stpi_trsInputToHTML($_POST["txtmessage"]));
	}
	print("</textarea><br/>\n");
	if (!$_POST["txtmessage"] && $_GET["op"] == "send")
	{
		$gonogo = false;
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("message") . "</span><br/>\n");
	}
	elseif (!$objEmail->stpi_setStrMessage("<p>" . $objBody->stpi_trsInputToHTML($_POST["txtmessage"]) . "</p>\n") && $_GET["op"] == "send")
	{
		$gonogo = false;
	}
	print("</p>\n");
	
	
	print("<p>\n");
	print("<img style=\"border: 2px solid black;\" src=\"./stpiadmin/captcha.php\" alt=\"Captcha\"/><br/>\n");
	print($objTexte->stpi_getArrTxt("captcha") . "<br/>\n");		
	print("<input type=\"text\" size=\"20\" name=\"txtcaptcha\" value=\"\" /><br/>\n");
	if (!$objCaptcha->stpi_chkCaptcha($_POST["txtcaptcha"]) && $_GET["op"] == "send" )
	{
		$gonogo = false;
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("captcha") . "</span><br/>\n");
	}
	print("</p>\n");
	
	
	print("<p>\n");
	
	if ($gonogo && $_GET["op"] == "send")
	{
		if ($objEmail->stpi_Send())
		{
			print("<span style=\"color:#008000;\">" .  $objTexte->stpi_getArrTxt("sended") . "</span><br/>\n");
		}
	}
	else
	{
		print("<input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("buttonsend") . "\"/><br/>\n");
	}
	
	print("</p>\n");
	
	print("</form>\n");
	
	print("</center>\n");
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
