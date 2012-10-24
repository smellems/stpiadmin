<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/event/clsevent.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objEvent = new clsevent();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/events");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		
		if ($objEvent->stpi_setNbID($_GET["nbEventID"]))
		{
			if ($_GET["op"] == "save")
			{
				if ($objEvent->stpi_getObjImg()->stpi_setNbID($objEvent->stpi_getNbImageID()))
				{
					if ($objEvent->stpi_getObjImg()->stpi_setTmpImg($_FILES["blobImage"]["tmp_name"]))
					{
						if ($objEvent->stpi_getObjImg()->stpi_trsImgResize($objEvent->stpi_getNbImgWidthMax(), $objEvent->stpi_getNbImgHeightMax()))
						{
							if ($objEvent->stpi_getObjImg()->stpi_trsTmpImgToImg())
							{
								if ($objEvent->stpi_getObjImg()->stpi_update())
								{
									print("<script type=\"text/javascript\">\n<!--\n");
									print("window.location = \"./event.php?l=" . LG);
									print("&nbEventID=" . $objBdd->stpi_trsBddToHTML($objEvent->stpi_getNbID()) . "\"");
									print("\n-->\n</script>");
								}
							}
						}
					}
				}
			}
			else
			{
				$objEvent->stpi_affImgEdit();
			}
		}
	?>
	</div>
	</body>
</html>