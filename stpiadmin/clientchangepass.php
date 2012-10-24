<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/client/clsclient.php");
	require_once("./includes/classes/user/clsuser.php");
	require_once("./includes/classes/security/clscryption.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objClient = new clsclient();
	$objCryption = new clscryption();
	$objUser = new clsuser();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/client");
	$objJavaScript = new clsjavascript();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objMenu = new clsmenu($strPage);
	$objRestrictedMenu = new clsrestrictedmenu($strPage);
	$objLock = new clslock($strPage);
	$objFooter = new clsfooter();
	
	$objLock->stpi_run();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php
		$objHead->stpi_affSTPIAdminHead();
	?>
	<body>
	<?php
		$objJavaScript->stpi_affArrLang();
		$objJavaScript->stpi_affNoAjax();
		$objJavaScript->stpi_affCreateXmlHttp();
		print("<div id=\"menulang\">\n");
		$objMenu->stpi_affSTPIAdminMenuLang();
		print("</div>\n");		
	?>
	<div id="gauche">
	<?php
		print("<div id=\"menu\">\n");
		$objRestrictedMenu->stpi_affSTPIAdminMenu();
		print("</div>\n");					

		print("<div id=\"footer\">\n");
		$objFooter->stpi_affSTPIAdminFooter();
		print("</div>\n");
	?>
	</div>
	<div id="droite">
	<?php
		$objJavaScript->stpi_affNoJavaScript();
		$objClient->stpi_affJsPassChange();
		$gonogo = true;
		if (!$objUser = $objUser->stpi_getObjUserFromSession())
		{
			$gonogo = false;
		}
		
		if (!$objClient->stpi_setNbID($objUser->stpi_getNbID()))
		{
			$gonogo = false;
		}
		
		print("<h1>" . $objTexte->stpi_getArrTxt("changepass") . "</h1>\n");		
		print("<form action=\"./" . $strPage . "?l=" . LG . "&op=save\" method=\"post\">\n");
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
		
		$objCryption->stpi_chkJsPasswordStrength();		
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
		
		print($objTexte->stpi_getArrTxt("newpassword") . "<br/>\n");
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
			
			if ($objClient->stpi_changePassword())
			{
				print("<script type=\"text/javascript\">\n");
				print("stpi_ClearPasswordChangeForm();\n");
				print("</script>\n");						
			}					
		}
		print("<input type=\"submit\" value=\"" . $objTexte->stpi_getArrTxt("change") . "\"/><br/>\n");
		print("</p>\n");

		print("</form>\n");
		
	?>
	</div>	
	</body>
</html>