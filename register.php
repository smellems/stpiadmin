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
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/register");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageEncrypted();
	
	$objClient = new clsclient();
	$objCountry =& $objClient->stpi_getObjCountry();
	$objBody = new clsbody();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();
	$objJavaScript->stpi_affArrLang();
	$objJavaScript->stpi_affNoAjax();
	$objJavaScript->stpi_affCreateXmlHttp();
	$objJavaScript->stpi_affNoJavaScript();
	
	$objClient->stpi_affJsAddPublic();
	$objClient->stpi_affJsChkPasswordStrengthPublic();
	$objCountry->stpi_affJsSelectCountryPublic();
	
	if (isset($_GET["redirect"]))
	{
		$strRedirect = $_GET["redirect"];
	}
	else
	{
		$strRedirect = "clientpublic";
	}

	$objMotd->stpi_affPublic();				
?>

<table width="100%">
<tr>
	<td width="60%" style="text-align: left; vertical-align: top;">
		<?php
			$objClient->stpi_affAddPublic($strRedirect);
		?>
	</td>
	<td width="40%" style="text-align: left; vertical-align: top;">
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
</table>
<?php
// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
