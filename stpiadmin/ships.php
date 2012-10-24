<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/ship/clsunitrange.php");
	require_once("./includes/classes/ship/clszone.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objUnitRange = new clsunitrange();
	$objZone = new clszone();
	
	$objTexte = new clstexte("./texte/ship");
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
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
										
				$objUnitRange->stpi_affJsAdd();
				$objUnitRange->stpi_affJsDelete();
				$objZone->stpi_affJsAdd();
				
				print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
				
				print("<h2>" . $objTexte->stpi_getArrTxt("titre2") . "</h2>\n");
								
				$objUnitRange->stpi_affAllUnitRange();
								
				print("<h2>" . $objTexte->stpi_getArrTxt("titre3") . "</h2>\n");
				
				$objZone->stpi_affAllZone();
				
				print("<h2>" . $objTexte->stpi_getArrTxt("titre4") . "</h2>\n");
				
				$objZone->stpi_affAdd();
								
			?>
			
		</div>
		
	</body>
</html>