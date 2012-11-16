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
	require_once("./stpiadmin/includes/classes/img/clscaptcha.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/login");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageEncrypted();
	
	$objClient = new clsclient();
	$objCaptcha = new clscaptcha();
	$objBody = new clsbody();
	$objUser = new clsuser();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();

		if (isset($_GET["redirect"]))
		{
			$strRedirect = $_GET["redirect"];
		}
		else
		{
			$strRedirect = "clientpublic";
		}
		
		if ($_GET["op"] == "login")
		{
			$gonogo = true;
				
			if (!$_POST["strCaptcha"])
			{
				$gonogo = false;
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("pascaptcha") . "</span><br/>\n");
			}
			else if (!$objCaptcha->stpi_chkCaptcha($_POST["strCaptcha"]))
			{
				$gonogo = false;
				print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("captchainvalide") . "</span><br/>\n");
			}
					
			if ($gonogo)
			{
				if ($nbClientID = $objClient->stpi_chkLogin($_POST["strCourriel"], $_POST["strPass"]))
				{
					$objUser->stpi_setNbID($nbClientID);
					$objUser->stpi_setNbTypeUserID(2);
					$objUser->stpi_setNbIP();
					$objUser->stpi_setObjUserToSession();
					
					print("<script type=\"text/javascript\" >\n");
					print("<!--\n");
					print("window.location = \"" . $strRedirect . ".php?l=" . LG . "\";\n");
					print("-->\n");
					print("</script>\n");										
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("failedlogin") . "</span><br/>\n");
				}
			}
		}
		
		print("<form name=\"login\" action=\"./login.php?l=" . LG . "&amp;redirect=" . $objBody->stpi_trsInputToHTML($strRedirect) . "&amp;op=login\" method=\"post\">\n");				
	?>
	
	<table>
	<tr>
		<td width="50%" style="text-align: left; vertical-align: top;">
			<h2>
				<?php
					print($objTexte->stpi_getArrTxt("welcome"));
				?>
			</h2>
			<table width="100%">
			<tr>
				<td style="text-align: right; vertical-align: top;" >
					<?php
						print($objTexte->stpi_getArrTxt("email"));
					?>
				</td>
				<td style="text-align: left; vertical-align: top;">
					<input type="text" maxlength="255" size="20" id="strCourriel" name="strCourriel" value="" />
				</td>
			</tr>
			<tr>
				<td style="text-align: right; vertical-align: top;" >
					<?php
						print($objTexte->stpi_getArrTxt("password"));
					?>
				</td>
				<td style="text-align: left; vertical-align: top;">
					<input type="password" maxlength="50" size="20" id="strPass" name="strPass" value="" />
					<br/>
					<?php
						print("<a href=\"./forgotpass.php?l=" . LG . "\" >" . $objTexte->stpi_getArrTxt("forgotpassword") . "</a>\n");
					?>
				</td>
			</tr>
			<tr>
				<td style="text-align: right; vertical-align: top;" >
					<?php
						print($objTexte->stpi_getArrTxt("captcha") . "<br/>\n");
					?>
				</td>
				<td style="text-align: left; vertical-align: top;">
					<img style="border: 2px solid black;" src="./stpiadmin/captcha.php" alt="Captcha"/>
					<br/>
					<input type="text" size="20" id="strCaptcha" name="strCaptcha" value="" />
				</td>
			</tr>
			</table>
		</td>
		<td width="50%" style="text-align: left; vertical-align: top;">
			<h2>
				<?php
					print($objTexte->stpi_getArrTxt("whyregister"));
				?>
			</h2>
			<?php
				print("<ul>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("li1"));
				print("</li>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("li2"));
				print("</li>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("li3"));
				print("</li>\n");
				print("<li>\n");
				print($objTexte->stpi_getArrTxt("li4"));
				print("</li>\n");
				print("</ul>\n");
			?>
		</td>
	</tr>
	<tr>
		<td width="50%" style="text-align: right; vertical-align: top;">
			<?php
				print("<input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("welcome") . "\"/>\n");
			?>
		</td>
		<td width="50%" style="text-align: right; vertical-align: top;">
			<?php
				print("<input onclick=\"window.location='register.php?redirect=" . $objBody->stpi_trsInputToHTML($strRedirect) . "&amp;l=" . LG . "'\" type=\"button\" value=\"" . $objTexte->stpi_getArrTxt("register") . "\"/>\n");
			?>
		</td>
	</tr>
	</table>
	
	</form>
<?php
// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
