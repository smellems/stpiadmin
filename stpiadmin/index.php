<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/img/clscaptcha.php");
	require_once("./includes/classes/security/clslock.php");
	require_once("./includes/classes/security/clscryption.php");
	require_once("./includes/classes/user/clsuser.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/index");
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objRestrictedMenu = new clsrestrictedmenu($strPage);
	$objCaptcha = new clscaptcha();
	$objLock = new clslock($strPage);
	$objCryption = new clscryption();
	$objUser = new clsuser();
	$objFooter = new clsfooter();
	
	$objLock->stpi_pageEncrypted();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<?php
		$objHead->stpi_affSTPIAdminHead();
	?>
		
	<body>
		<?php
			print("<div id=\"menulang\">\n");
			$objMenu->stpi_affSTPIAdminMenuLang();
			print("</div>\n");		
		?>
		<div id="gauche">
			<?php
				///Vérifier si il y a une session avant d'afficher le Login
				if (!isset($_SESSION["stpiObjUser"]))
				{
					$gonogo = true;
					
					if (!$objBdd->stpi_chkInputToBdd($_POST["strUser"]) && $_GET["op"] == "login")
					{
						$gonogo = false;
						print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("username") . "</span><br/>\n");
					
					}
					
					if (!$objBdd->stpi_chkInputToBdd($_POST["strPass"]) && $_GET["op"] == "login")
					{
						$gonogo = false;
						print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("password") . "</span><br/>\n");
					}
					
					if (!$_POST["strCaptcha"] && $_GET["op"] == "login")
					{
						$gonogo = false;
						print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("pascaptcha") . "</span><br/>\n");
					}
					else if (!$objCaptcha->stpi_chkCaptcha($_POST["strCaptcha"]) && $_GET["op"] == "login" )
					{
						$gonogo = false;
						print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("captchainvalide") . "</span><br/>\n");
					}
					
					if ($gonogo && $_GET["op"] == "login")
					{
						//Mettre le nom d'usager en minuscule
						$strUsername = strtolower($_POST["strUser"]);
				
						//Création de la requête
						$SQL = "SELECT nbSTPIAdminUserID, strNom, strPrenom";
						$SQL .= " FROM stpi_stpiadminuser_STPIAdminUser";
						$SQL .= " WHERE  strUsername = '" . $strUsername . "'";
						$SQL .= " AND strPassword = '" . $objCryption->stpi_trsTextToCrypted($objBdd->stpi_trsInputToBdd($_POST["strPass"])) . "'";
						
						if ($result = $objBdd->stpi_select($SQL))
						{
							if ($row = mysql_fetch_assoc($result))
							{
								$objUser->stpi_setNbID($row["nbSTPIAdminUserID"]);
								$objUser->stpi_setNbTypeUserID(1);
								$objUser->stpi_setNbIP();
								$objUser->stpi_setObjUserToSession();
								print("<span style=\"color:#008000;\">");
								print($objTexte->stpi_getArrTxt("succeslogin"));
								print(" " . $objBdd->stpi_trsBddToHTML($row["strPrenom"]));
								print(" " . $objBdd->stpi_trsBddToHTML($row["strNom"]));
								print("</span><br/>\n");
							}
					  		
					  		mysql_free_result($result);
						}
						else
						{
							print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("failedlogin") . "</span><br/>\n");
							print("<a href=\"./index.php?l=" . LG . "\">" . $objTexte->stpi_getArrErrTxt("failedretry") . "</a>\n");
						}
					}
					else
					{		
						
						print("<h2>" . $objTexte->stpi_getArrTxt("titre") . "</h2>\n");
						
						print("<form action=\"./index.php?l=" . LG . "&amp;op=login\" method=\"post\">\n");
						
						print("<p>\n");
						print($objTexte->stpi_getArrTxt("username") . "<br/>\n");
						print("<input type=\"text\" maxlength=\"50\" size=\"14\" id=\"strUser\" name=\"strUser\" value=\"\" /><br/>\n");
						print("</p>\n");		
						
						print("<p>\n");
						print($objTexte->stpi_getArrTxt("password") . "<br/>\n");
						print("<input type=\"password\" maxlength=\"50\" size=\"14\" id=\"strPass\"name=\"strPass\" value=\"\" /><br/>\n");
						print("</p>\n");
						
						print("<p>\n");
						print("<img border=\"2\" src=\"./captcha.php\" alt=\"Captcha\"/><br/>\n");
						print($objTexte->stpi_getArrTxt("captcha") . "<br/>\n");		
						print("<input type=\"text\" maxlength=\"5\" size=\"14\" id=\"strCaptcha\" name=\"strCaptcha\" value=\"\" /><br/>\n");
						print("</p>\n");		
						
						print("<p>\n");
						print("<input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("submitbutton") . "\"/><br/>\n");
						print("</p>\n");
						
						print("</form>\n");
						
						print("<script type=\"text/javascript\">\n");
						print("document.getElementById(\"strUser\").focus();\n");
						print("</script>\n");
					}

					if (!isset($_SESSION["stpiObjUser"]))	
					{
						print("<div id=\"menu\">\n");
						print("<ul>\n");
						print("<li><a href=\"./exit.php");
						print("?l=" . LG . "\">");
						print($objTexte->stpi_getArrTxt("exit") . "</a></li>\n");			
						print("</ul>\n");
						print("</div>\n");			
					}
					
				}				
				
				if (isset($_SESSION["stpiObjUser"]))
				{
					print("<div id=\"menu\">\n");
					$objRestrictedMenu->stpi_affSTPIAdminMenu();
					print("</div>\n");					
				}				
			
				print("<div id=\"footer\">\n");
				$objFooter->stpi_affSTPIAdminFooter();
				print("</div>\n");
			?>
		</div>
		
		<div id="droite">
			<?php
				$objJavaScript->stpi_affNoJavaScript();
				print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
			?>
		</div>
		
	</body>
</html>
