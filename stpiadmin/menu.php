<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/javascript/clsjavascript.php");
	require_once("./includes/classes/menu/clsmenuelement.php");
	require_once("./includes/classes/content/clshead.php");
	require_once("./includes/classes/content/clsmenu.php");
	require_once("./includes/classes/content/clsrestrictedmenu.php");
	require_once("./includes/classes/content/clsfooter.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMenuElement = new clsmenuelement();
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/menus");
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
		print("<h1>" . $objTexte->stpi_getArrTxt("titre1") . "</h1>\n");
		if ($objMenuElement->stpi_objMenuPublic()->stpi_setNbID($_GET["nbMenuID"]))
		{
			if ($objMenuElement->stpi_objMenuPublic()->stpi_setArrObjMenuLgFromBdd())
			{
				print("<input type=\"hidden\" id=\"nbMenuID\" value=\"" . $objMenuElement->stpi_objMenuPublic()->stpi_getNbID() . "\" />\n");
				$objMenuElement->stpi_objMenuPublic()->stpi_affJsDelete();
				$objMenuElement->stpi_objMenuPublic()->stpi_affJsEdit();
				$objMenuElement->stpi_objMenuPublic()->stpi_affEdit();
			}
		}

		print("<h2>\n");
		print($objTexte->stpi_getArrTxt("titre2"));
		print("</h2>\n");
		if ($objMenuElement->stpi_setNbMenuID($_GET["nbMenuID"]))
		{
			if ($arrNbMenuElementID = $objMenuElement->stpi_selNbMenuID())
			{
				$ok = false;
				print("<ul>");
				foreach ($arrNbMenuElementID as $nbMenuElementID)
				{
					if ($objMenuElement->stpi_setNbID($nbMenuElementID))
					{
						if ($objMenuElement->stpi_setObjMenuElementLgFromBdd())
						{
							print("<li><a href=\"./menuelement.php?l=" . LG);
							print("&amp;nbMenuElementID=" . $objBdd->stpi_trsBddToHTML($nbMenuElementID) . "\">");
							print($objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</a>\n");
							if ($arrNbMenuElement2ID = $objMenuElement->stpi_selNbParentID())
							{
								print("<ul>");
								foreach($arrNbMenuElement2ID as $nbMenuElementID)
								{
									if ($objMenuElement->stpi_setNbID($nbMenuElementID))
									{
										if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbMenuElementID($nbMenuElementID))
										{
											if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbID($objMenuElement->stpi_getObjMenuElementLg()->stpi_selNbMenuElementIDLG()))
											{
												print("<li><a href=\"./menuelement.php?l=" . LG);
												print("&amp;nbMenuElementID=" . $objBdd->stpi_trsBddToHTML($nbMenuElementID) . "\">");
												print($objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</a></li>\n");
						/* pour ajouter un niveau au menu
												if ($arrNbMenuElement3ID = $objMenuElement->stpi_selNbParentID())
												{
													print("<ul>");
													foreach($arrNbMenuElement3ID as $nbMenuElementID)
													{
														if ($objMenuElement->stpi_setNbID($nbMenuElementID))
														{
															if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbMenuElementID($nbMenuElementID))
															{
																if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbID($objMenuElement->stpi_getObjMenuElementLg()->stpi_selNbMenuElementIDLG()))
																{
																	print("<li><a href=\"./menuelement.php?l=" . LG);
																	print("&amp;nbMenuElementID=" . $objBdd->stpi_trsBddToHTML($nbMenuElementID) . "\">");
																	print($objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</a></li>\n");
																}
															}
														}
													}
													print("</ul></li>");
												}
						*/
											}
										}
									}
								}
								print("</ul>");
							}
							print("</li>");
						}
					}
				}
				print("</ul>");
			}
			print("<h2>" . $objTexte->stpi_getArrTxt("addmenuelement") . "</h2>\n");
			$objMenuElement->stpi_affJsAdd();
			$objMenuElement->stpi_affAdd();
		}
	?>		
	</div>
	</body>
</html>
