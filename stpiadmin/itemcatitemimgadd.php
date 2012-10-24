<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/item/clscatitem.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objCatItem = new clscatitem();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/items");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre4") . "</h1>\n");
		
		if ($objCatItem->stpi_setNbID($_GET["nbCatItemID"]))
		{
			if ($_GET["op"] == "save")
			{
				if ($objCatItem->stpi_getObjImg()->stpi_setTmpImg($_FILES["blobImage"]["tmp_name"]))
				{
					if ($objCatItem->stpi_getObjImg()->stpi_trsImgResize($objCatItem->stpi_getNbImgWidthMax(), $objCatItem->stpi_getNbImgHeightMax()))
					{
						if ($objCatItem->stpi_getObjImg()->stpi_trsTmpImgToImg())
						{
							if ($objBdd->stpi_startTransaction())
							{
								$ok = true;
								if ($nbImageID = $objCatItem->stpi_getObjImg()->stpi_insert())
								{
									print($nbImageID);
									if ($objCatItem->stpi_setNbImageID($nbImageID))
									{
										if (!$objCatItem->stpi_update())
										{
											$ok = false;
										}
									}
									else
									{
										$ok = false;
									}
								}
								else
								{
									$ok = false;
								}
								if ($ok)
								{
									if ($objBdd->stpi_commit())
									{
										print("<script type=\"text/javascript\">\n<!--\n");
										print("window.location = \"./itemcatitem.php?l=" . LG);
										print("&nbCatItemID=" . $objBdd->stpi_trsBddToHTML($objCatItem->stpi_getNbID()) . "\"");
										print("\n-->\n</script>");
									}
									else
									{
										$objBdd->stpi_rollback();
									}
								}
								else
								{
									$objBdd->stpi_rollback();
								}
							}
						}
					}
				}
			}
			else
			{
				$objCatItem->stpi_affImgAdd();
			}
		}
	?>
	</div>
	</body>
</html>