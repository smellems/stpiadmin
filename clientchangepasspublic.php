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
	require_once("./stpiadmin/includes/classes/security/clscryption.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/email/clsemail.php");
		
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
	$objCryption = new clscryption();
	$objUser = new clsuser();
	$objEmail = new clsemail();
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
			
			$objClient->stpi_affJsPassChange();
			$objClient->stpi_affJsChkPasswordStrengthPublic();
						
			$objMotd->stpi_affPublic();

			$objUser = $objUser->stpi_getObjUserFromSession();
			
			if ($objUser->stpi_getNbTypeUserID() == 2)
			{
				if ($objClient->stpi_setNbID($objUser->stpi_getNbID()))
				{
					$gonogo = true;
										
					print("<h2>" . $objTexte->stpi_getArrTxt("changepass") . "</h2>\n");		
					print("<form action=\"./" . $strPage . "?l=" . LG . "&amp;op=save\" method=\"post\">\n");
					print("<p>\n");
					print($objTexte->stpi_getArrTxt("oldpassword") . "<br/>\n");
					print("<input type=\"password\" maxlength=\"50\" size=\"20\" id=\"strOldPassword\" name=\"strOldPassword\" value=\"\" /><br/>\n");
					if ($_GET["op"] == "save")
					{
						if (!$objClient->stpi_chkStrOldPassword($_POST["strOldPassword"]))
						{
							$gonogo = false;
						}					
					}
					print("</p>\n");
					
					print("<p>\n");
					print($objTexte->stpi_getArrTxt("newpassword") . "<br/>\n");
					print("<input type=\"password\" onkeyup=\"stpi_chkPasswordStrength(this.value)\" maxlength=\"50\" size=\"20\" id=\"strPassword\" name=\"strPassword\" value=\"\" />\n");
					print("<span id=\"stpi_chkPasswordStrength\"></span><br/>\n");
					if ($_GET["op"] == "save")
					{
						if (!$objClient->stpi_chkStrPassword($_POST["strPassword"]))
						{
							$gonogo = false;
						}
					}
					
					print($objTexte->stpi_getArrTxt("newpassword2") . "<br/>\n");
					print("<input type=\"password\" maxlength=\"50\" size=\"20\" id=\"strPasswordConfirm\" name=\"strPasswordConfirm\" value=\"\" /><br/>\n");
					if ($_GET["op"] == "save")
					{
						if ($_POST["strPassword"] != $_POST["strPasswordConfirm"])
						{
							$gonogo = false;
							print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("passworddifferent") . "</span><br/>\n");
						}
					}
					print("</p>\n");
			
					print("<p>\n");
					if ($gonogo && $_GET["op"] == "save")
					{
						$objClient->stpi_setStrPassword($_POST["strPassword"]);
						
						if ($objClient->stpi_update())
						{
							print("<script type=\"text/javascript\">\n");
							print("stpi_ClearPasswordChangeForm();\n");
							print("</script>\n");
							
							$objEmail->stpi_setStrEmail($objBdd->stpi_trsBddToHTML($objClient->stpi_getStrCourriel()));
							$objEmail->stpi_setStrFromEmail(STR_EMAIL_FROM);
							$objEmail->stpi_setStrSubject($objTexte->stpi_getArrTxt("changepass"));
							$objEmail->stpi_setStrMessage("<p>" . $objTexte->stpi_getArrTxt("emailmessage") . "<br/>" . $objTexte->stpi_getArrTxt("emailwarning") . "<br/> IP: " . $_SERVER["REMOTE_ADDR"] . "</p>\n");
							
							if ($objEmail->stpi_Send())
							{
								print("<span style=\"color:#008000;\">" .  $objTexte->stpi_getArrTxt("changed") . "</span><br/>\n");
							}
						}				
					}
					print("<input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("change") . "\"/><br/>\n");
					print("</p>\n");
			
					print("</form>\n");
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