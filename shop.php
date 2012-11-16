<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	
	require_once("./stpiadmin/includes/classes/item/clsitem.php");
	// require_once("./stpiadmin/includes/classes/content/clsnavigator.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/shop");
	$objBody = new clsbody();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageNotEncrypted();
	
	$objUser = new clsuser();
	$objItem = new clsitem();
	$objRegistre = new clsregistre();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objTypeItem =& $objItem->stpi_getObjTypeItem();
	$objCatItem =& $objItem->stpi_getObjCatItem();
	
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
	
	if (!$arrNbCatItemID = $objCatItem->stpi_selAllPublic($boolRegistre))
	{
		 $arrNbCatItemID = array();
	}
	$strKeywords = "";
	$strDescription = "";
	foreach ($arrNbCatItemID as $nbCatItemID)
	{
		if ($objCatItem->stpi_setNbID($nbCatItemID))
		{
			if ($objCatItem->stpi_setObjCatItemLgFromBdd())
			{
				$strKeywords .= ", " . $objCatItem->stpi_getObjCatItemLg()->stpi_getStrName();
				if ($objCatItem->stpi_getObjCatItemLg()->stpi_getStrDesc() != "")
				{
					$strDescription .= " " . $objCatItem->stpi_getObjCatItemLg()->stpi_getStrDesc();
				}
			}
		}
	}
	if ($_GET["nbCatItemID"])
	{
		if ($objCatItem->stpi_setNbID($_GET["nbCatItemID"]))
		{
			if (!$arrNbTypeItemID = $objCatItem->stpi_selNbTypeItemIDPublic($boolRegistre))
			{
				$arrNbTypeItemID = array();
			}
			
			foreach ($arrNbTypeItemID as $nbTypeItemID)
			{
				if ($objTypeItem->stpi_setNbID($nbTypeItemID))
				{
					if ($objTypeItem->stpi_setObjTypeItemLgFromBdd())
					{
						$strKeywords .= ", " . $objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrName();
						if ($objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrDesc() != "")
						{
							$strDescription .= " " . $objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrDesc();
						}
					}
				}
			}
		}
	}
	if ($_GET["nbTypeItemID"])
	{
		if ($objTypeItem->stpi_setNbID($_GET["nbTypeItemID"]))
		{
			if (!$arrNbItemID = $objTypeItem->stpi_selNbItemIDPublic($boolRegistre))
			{
				$arrNbItemID = array();
			}
			
			foreach ($arrNbItemID as $nbItemID)
			{
				if ($objItem->stpi_setNbID($nbItemID))
				{
					if ($objItem->stpi_setObjItemLgFromBdd())
					{
						$strKeywords .= ", " . $objItem->stpi_getObjItemLg()->stpi_getStrName();
						if ($objItem->stpi_getObjItemLg()->stpi_getStrDesc() != "")
						{
							$strDescription .= " " . $objItem->stpi_getObjItemLg()->stpi_getStrDesc();
						}
					}
				}
			}
		}
	}
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords") . $objBdd->stpi_trsBddToHTML($strKeywords), $objTexte->stpi_getArrTxt("description") . $objBdd->stpi_trsBddToHTML($strDescription));
	
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageNotEncrypted();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"), "-sec");

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();

	$objJavaScript->stpi_affArrLang();
	$objJavaScript->stpi_affNoAjax();
	$objJavaScript->stpi_affCreateXmlHttp();
	$objJavaScript->stpi_affNoJavaScript();

	$objSousItem->stpi_affJsTagImgPublic();			
	$objSousItem->stpi_affJsPrixPublic();
	$objSousItem->stpi_affJsSousItemToCommande();
	$objSousItem->stpi_affJsSousItemToRegistre();
	
	if ($_GET["nbTypeItemID"])
	{
		if ($objTypeItem->stpi_setNbID($_GET["nbTypeItemID"]))
		{
			if ($_GET["nbCatItemID"] AND $objCatItem->stpi_setNbID($_GET["nbCatItemID"]))
			{
				// page du type d'item
				$nbCatItemID = $_GET["nbCatItemID"];
				// $objTypeItem->stpi_affPublic($nbCatItemID, 0, 1, 0, 0);	
			}
			else
			{
				$nbCatItemID = 0;
				if ($arrNbImageIDNumImage = $objTypeItem->stpi_selNbImageID())
				{
					if (isset($arrNbImageIDNumImage[1]))
					{
						if ($objTypeItem->stpi_setNbNumImage(1))
						{
							if ($objTypeItem->stpi_setNbImageID($arrNbImageIDNumImage[1]))
							{
								$objTypeItem->stpi_affPublic($nbCatItemID, 0, 1, 0, 1);
							}
						}
					}
				}
			}
			
			if (!$arrNbItemID = $objItem->stpi_selAllPublic($_GET["nbTypeItemID"], $nbCatItemID, $boolRegistre))
			{
				$arrNbItemID = array();
			}

			//$objNavigator = new clsnavigator($arrNbItemID, $_GET["nbPage"]);
			//$objNavigator->stpi_setAllVariables();
			//$arrNbItemID = $objNavigator->stpi_getArrNbID();
			//$objNavigator->stpi_aff();
			
			print("<table style=\"margin: 0px; padding: 0px 10px;\" >\n");
			print("<tr>\n");
			$i = 1; 
			foreach ($arrNbItemID as $nbItemID)
			{
				if ($i == 4)
				{
					print("</tr>\n");
					print("<tr>\n");
					$i = 1;
				}
				if ($objItem->stpi_setNbID($nbItemID))
				{
					print("<td>\n");
					$objItem->stpi_affShopPublic($boolRegistre);
					print("</td>\n");
				}
				$i++;
			}
			print("</tr>\n");
			print("</table>\n");
			
			//$objNavigator->stpi_aff();
		}
	}
	elseif ($_GET["nbCatItemID"])
	{
		if ($objCatItem->stpi_setNbID($_GET["nbCatItemID"]))
		{
			if (!$arrNbTypeItemID = $objCatItem->stpi_selNbTypeItemIDPublic($boolRegistre))
			{
				$arrNbTypeItemID = array();
			}
			
			foreach ($arrNbTypeItemID as $nbTypeItemID)
			{
				if ($objTypeItem->stpi_setNbID($nbTypeItemID))
				{
					if ($arrNbImageIDNumImage = $objTypeItem->stpi_selNbImageID())
					{
						if (isset($arrNbImageIDNumImage[1]))
						{
							if ($objTypeItem->stpi_setNbNumImage(1))
							{
								if ($objTypeItem->stpi_setNbImageID($arrNbImageIDNumImage[1]))
								{
									$objTypeItem->stpi_affPublic($_GET["nbCatItemID"], 0, 1, 0, 1);
								}
							}
						}
					}

					if (!$arrNbItemID = $objItem->stpi_selAllPublic($nbTypeItemID, $_GET["nbCatItemID"], $boolRegistre))
					{
						$arrNbItemID = array();
					}

					$nbItems = count($arrNbItemID);
					if ($nbItems > 3)
					{
						shuffle($arrNbItemID);
						$nbItems = 3;
					}
					
					if (count($arrNbItemID) > 3)
					{
						print("<h3 style=\"text-align: right;\">");
						print("<a href=\"./shop.php?l=" . $objBdd->stpi_trsBddToHTML(LG));
						print("&amp;nbCatItemID=" . $objBdd->stpi_trsBddToHTML($_GET["nbCatItemID"]));
						print("&amp;nbTypeItemID=" . $objBdd->stpi_trsBddToHTML($nbTypeItemID));
						
						print("\">" . $objTexte->stpi_getArrTxt("autresitems") . " ");
						if ($objTypeItem->stpi_setObjTypeItemLgFromBdd())
						{
							print($objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrName() . "...");
						}
						
						print("</a>\n");
						print("</h3>");
					}
					
					print("<table style=\"padding: 0px 10px 35px 10px; margin: 0px;\" >\n");
					print("<tr>\n");
					$i = 1; 
					for ($i = 0; $i < $nbItems; $i++)
					{
						if ($objItem->stpi_setNbID($arrNbItemID[$i]))
						{
							print("<td>\n");
							$objItem->stpi_affShopPublic($boolRegistre);
							print("</td>\n");
						}
					}
					print("</tr>\n");
					print("</table>\n");
				}
			}
		}					
	}
	else
	{	
		print("<table style=\"padding: 0px 10px; margin: 0px;\" >\n");
		print("<tr>\n");
		$i = 0;	
		foreach ($arrNbCatItemID as $nbCatItemID)
		{
			if ($i >= 3)
			{
				$i = 0;
				print("</tr><tr>\n");
			}
			$i++;
			if ($objCatItem->stpi_setNbID($nbCatItemID))
			{
				print("<td style=\"padding: 10px 10px; margin: 0px; width: " . $objBdd->stpi_trsBddToHTML($objCatItem->stpi_getNbImgWidthMax()) . "px; height: " . $objBdd->stpi_trsBddToHTML($objCatItem->stpi_getNbImgHeightMax()) . "px; vertical-align: top;\" >\n");
				$objCatItem->stpi_affPublic();
				print("</td>");
			}
		}
		print("</tr></table>\n");
	}
	print("<p><br></br>" . $objTexte->stpi_getArrTxt("voirles") . " <a href=\"politiques.php?l=" . $objBdd->stpi_trsBddToHTML(LG) . "\">" . $objTexte->stpi_getArrTxt("termsconditions") . "</a></p>\n");
	// <!-- MainContentEnd -->

	// SideMenu
	$objItem->stpi_affSideMenuPublic($boolRegistre, $_GET["nbCatItemID"], $_GET["nbTypeItemID"]);
	
	$objFooter->stpi_affFooter();
?>
