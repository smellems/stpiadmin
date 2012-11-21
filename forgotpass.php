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
	require_once("./stpiadmin/includes/classes/email/clsemail.php");
	require_once("./stpiadmin/includes/classes/img/clscaptcha.php");
	require_once("./stpiadmin/includes/classes/security/clscryption.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/forgotpass");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageEncrypted();
	
	$objBdd = clsbdd::singleton();
	$objCaptcha = new clscaptcha();
	$objClient = new clsclient();
	$objEmail = new clsemail();
	$objCryption = new clscryption();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();
			
	$gonogo = false;
	
	if ($_GET["op"] == "send")
	{
		$gonogo = true;
		
		if ($objEmail->stpi_chkStrEmail($_POST["strCourriel"]))
		{
			$SQL = "SELECT nbClientID";
			$SQL .= " FROM stpi_client_Client";
			$SQL .= " WHERE strCourriel = '" . $objBdd->stpi_trsInputToBdd($_POST["strCourriel"]) . "'";
			
			if ($result = $objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$nbClientID = $row["nbClientID"];
				}
				else
				{
					$gonogo = false;
				}
				mysql_free_result($result);			
			}	
			else
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("noaccount") . "</span><br/>\n");
				$gonogo = false;			
			}
		}
		else
		{
			$gonogo = false;
		}
		
		if (!empty($_POST["strCaptcha"]))
		{
			if (!$objCaptcha->stpi_chkCaptcha($_POST["strCaptcha"]))
			{
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("captchainvalide") . "</span><br/>\n");
				$gonogo = false;
			}
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("pascaptcha") . "</span><br/>\n");
			$gonogo = false;
		}
									
		if ($gonogo)
		{
			if ($objClient->stpi_setNbID($nbClientID))
			{
				$strPassword = $objCryption->stpi_selPasswordGenerator();
				if ($objClient->stpi_setStrPassword($strPassword))
				{
					$objClient->stpi_update();
				}							
			}
			$objEmail->stpi_setStrFromEmail(STR_EMAIL_FROM);
			$objEmail->stpi_setStrEmail($_POST["strCourriel"]);
			$objEmail->stpi_setStrSubject($objTexte->stpi_getArrTxt("emailsubject"));
			$objEmail->stpi_setStrMessage("<p>" . $objTexte->stpi_getArrTxt("emailmessage") . " " . $strPassword . "<br/>" . $objTexte->stpi_getArrTxt("emailwarning") . "<br/>IP: " . $_SERVER["REMOTE_ADDR"] . "</p>\n");					
			if ($objEmail->stpi_Send())
			{
				print("<span style=\"color:#008000;\">" .  $objTexte->stpi_getArrTxt("sended") . "</span><br/>\n");
			}
		}
	}
				
	
	print("<form name=\"forgotpass\" action=\"./forgotpass.php?l=" . LG . "&amp;op=send\" method=\"post\">\n");
	
	print("<table>\n");
	print("<tr>\n");
	print("<td colspan=\"2\" style=\"text-align: left; vertical-align: top;\">\n");
	print($objTexte->stpi_getArrTxt("instruction") . "\n");
	print("</td>\n");
	print("</tr>\n");
	print("<tr>\n");			
	print("<td style=\"text-align: right; vertical-align: top;\" >\n");
	print($objTexte->stpi_getArrTxt("courriel"));
	print("</td>\n");
	print("<td style=\"text-align: left; vertical-align: top;\">\n");
	print("<input type=\"text\" maxlength=\"200\" size=\"30\" name=\"strCourriel\" id=\"strCourriel\" value=\"\" />\n");
	print("</td>\n");
	print("</tr>\n");
	print("<tr>\n");
	print("<td style=\"text-align: right; vertical-align: top;\" >\n");
	print($objTexte->stpi_getArrTxt("captcha") . "<br/>\n");
	print("</td>\n");
	print("<td style=\"text-align: left; vertical-align: top;\">\n");
	print("<img style=\"border: 2px solid black;\" src=\"./stpiadmin/captcha.php\" alt=\"Captcha\"/>\n");
	print("<br/>\n");
	print("<input type=\"text\" size=\"20\" id=\"strCaptcha\" name=\"strCaptcha\" value=\"\" />\n");
	print("</td>\n");
	print("</tr>\n");
	print("<tr>\n");
	print("<td colspan=\"2\" style=\"text-align: right; vertical-align: top;\">\n");
	print("<input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("send") . "\"/>\n");
	print("</td>");
	print("</tr>\n");
	print("</table>\n");

	print("</form>\n");
	// <!-- MainContentEnd -->
	
	$objFooter->stpi_affFooter();
?>
