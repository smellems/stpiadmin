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
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php
		$objPage->stpi_setNbID(4);
		$objPage->stpi_setObjPageLgFromBdd();
		$objPageLg = $objPage->stpi_getObjPageLg();
		$objHead = new clshead(STR_NOM_ENT . " - " . $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrTitre()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrKeywords()), $objBdd->stpi_trsBddToHTML($objPageLg->stpi_getStrDesc()));
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
				print("<div class=\"news\">\n");
				print("<h3>" . $objTexte->stpi_getArrTxt("coderegistre") . "</h3>\n");
				print("<div style=\"margin: 0px; padding: 0px 10px; text-align: center;\" >\n");
				print($objTexte->stpi_getArrTxt("entrercode") . " :<br/>\n");
				print("<input type=\"text\" maxlength=\"10\" size=\"10\" id=\"strRegistreCode\" value=\"\" /><br/>\n");
				print("<input type=\"button\" onclick=\"window.location='./shopregistre.php?l=" . LG . "&amp;strRegistreCode=' + document.getElementById('strRegistreCode').value\" value=\"" . $objTexte->stpi_getArrTxt("shop") . "\"/><br/>\n");
				print("</div><br/>\n");

				$objNews->stpi_affPublic(2);
							
				print("</div>\n");
				
				$objBanniere->stpi_affHomePublic();
						
				$objMotd->stpi_affPublic();

				print($objBdd->stpi_trsBddHtmlToHTML($objPageLg->stpi_getStrContent()));
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
