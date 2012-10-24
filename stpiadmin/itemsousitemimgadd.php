<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/item/clssousitem.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSousItem = new clssousitem();
	
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre9") . "</h1>\n");
		
		if ($objSousItem->stpi_setNbID($_GET["nbSousItemID"]) AND $objSousItem->stpi_setNbNumImage($_GET["nbNumImage"]))
		{
			if ($_GET["op"] == "save")
			{
				if ($objSousItem->stpi_getObjImg()->stpi_setTmpImg($_FILES["blobImage"]["tmp_name"]))
				{
					if ($objSousItem->stpi_getObjImg()->stpi_trsImgResize($objSousItem->stpi_getNbImgWidthMax(), $objSousItem->stpi_getNbImgHeightMax()))
					{
						if ($objSousItem->stpi_getObjImg()->stpi_trsTmpImgToImg())
						{
							if ($objBdd->stpi_startTransaction())
							{
								$ok = true;
								if ($nbImageID = $objSousItem->stpi_getObjImg()->stpi_insert())
								{
									// print($nbImageID);
									if ($objSousItem->stpi_setNbImageID($nbImageID))
									{
										if (!$objSousItem->stpi_update(true))
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
										print("window.location = \"./itemsousitem.php?l=" . LG);
										print("&nbSousItemID=" . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbID()) . "\"");
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
			elseif ($_GET["op"] == "useother")
			{
				if ($objSousItem->stpi_setNbImageID($_POST["nbImageID"]))
				{
					if ($objSousItem->stpi_update(true))
					{
						print("<script type=\"text/javascript\">\n<!--\n");
						print("window.location = \"./itemsousitem.php?l=" . LG);
						print("&nbSousItemID=" . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbID()) . "\"");
						print("\n-->\n</script>");
					}
				}
			}
			else
			{
				$objSousItem->stpi_affImgAdd();
			}
		}
	?>
	</div>
	</body>
</html>