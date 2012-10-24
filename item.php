<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");	
	require_once("./stpiadmin/includes/classes/item/clsitem.php");
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/shop");
	$objBody = new clsbody();
	
	$objBdd = clsBdd::singleton();
	
	$objUser = new clsuser();
	$objItem = new clsitem();
	$objRegistre = new clsregistre();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objTypeItem =& $objItem->stpi_getObjTypeItem();
	$objCatItem =& $objItem->stpi_getObjCatItem();
	$objAttribut =& $objSousItem->stpi_getObjAttribut();
	
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
	if ($_GET["nbItemID"])
	{
		if ($objItem->stpi_setNbID($_GET["nbItemID"]))
		{
			if ($objItem->stpi_setObjItemLgFromBdd())
			{
				$strKeywords .= ", " . $objItem->stpi_getObjItemLg()->stpi_getStrName();
				if ($objItem->stpi_getObjItemLg()->stpi_getStrDesc() != "")
				{
					$strDescription .= " " . $objItem->stpi_getObjItemLg()->stpi_getStrDesc();
				}
			}
			
			if (!$arrNbAttributID = $objItem->stpi_selNbAttributIDPublic(0, $boolRegistre))
			{
				$arrNbAttributID = array();
			}
			foreach ($arrNbAttributID as $nbAttributID)
			{
				if ($objAttribut->stpi_setNbID($nbAttributID))
				{
					if ($objAttribut->stpi_setObjAttributLgFromBdd())
					{
						$strKeywords .= ", " . $objAttribut->stpi_getObjAttributLg()->stpi_getStrName();
						if ($objAttribut->stpi_getObjAttributLg()->stpi_getStrDesc() != "")
						{
							$strDescription .= " " . $objAttribut->stpi_getObjAttributLg()->stpi_getStrDesc();
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php
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
	
		<div id="sidemenu">
			<?php
			
				$objItem->stpi_affSideMenuPublic($boolRegistre, $_GET["nbCatItemID"], $_GET["nbTypeItemID"]);

			?>
		</div>
		 	
		<div id="content">
			<?php
				$objMotd->stpi_affPublic();
			?>
			<?php
				$objJavaScript->stpi_affArrLang();
				$objJavaScript->stpi_affNoAjax();
				$objJavaScript->stpi_affCreateXmlHttp();
				$objJavaScript->stpi_affNoJavaScript();
				
				$objSousItem =& $objItem->stpi_getObjSousItem();
				$objAttribut =& $objSousItem->stpi_getObjAttribut();
			
				$objSousItem->stpi_affJsTagImgPublic();
				$objSousItem->stpi_affJsTagImgLargePublic();	
				$objSousItem->stpi_affJsPrixPublic();
				$objSousItem->stpi_affJsSousItemToCommande();
				
				$objSousItem->stpi_setNbNumImage(1);				
				$nbImgSousItemWidthMaxSmall = $objSousItem->stpi_getNbImgWidthMax();
				$nbImgSousItemHeightMaxSmall = $objSousItem->stpi_getNbImgHeightMax();
				
				$objSousItem->stpi_setNbNumImage(2);				
				$nbImgSousItemWidthMaxBig = $objSousItem->stpi_getNbImgWidthMax();
				$nbImgSousItemHeightMaxBig = $objSousItem->stpi_getNbImgHeightMax();			
				
				$objImgSousItem =& $objSousItem->stpi_getObjImg();
				
				if ($objItem->stpi_setNbID($_GET["nbItemID"]))
				{
					$objItem->stpi_affPublic();
				}
				
				$nbSousItem = 0;
				
				if ($arrNbSousItemID = $objItem->stpi_selNbSousItemID())
				{
					$nbSousItem = count($arrNbSousItemID);
				}
				
				if ($nbSousItem > 1)
				{					
					if ($arrNbImageID = $objItem->stpi_selSousItemNbImageID($boolRegistre))
					{
						print("<table style=\"padding: 10px; margin: 0px;\" >\n");
						print("<tr>\n");
						
						$SQL = "SELECT nbSousItemID";
						$SQL .= " FROM stpi_item_SousItem_ImgSousItem";
						$SQL .= " WHERE nbImageID = '" . $objBdd->stpi_trsInputToBdd($arrNbImageID[0]) . "'";
						
						if ($result = $objBdd->stpi_select($SQL))
						{
							if ($row = mysql_fetch_assoc($result))
							{
								if ($objSousItem->stpi_setNbID($row["nbSousItemID"]))
								{
									if ($arrNbImageIDNumImage = $objSousItem->stpi_selNbImageID())
									{
										if (isset($arrNbImageIDNumImage[2]))
										{
											if ($objImgSousItem->stpi_setNbID($arrNbImageIDNumImage[2]))
											{
												if ($objImgSousItem->stpi_setNbWidthHeightFromImg())
												{
													if ($objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
													{
														if ($objItem->stpi_setObjItemLgFromBdd())
														{
															print("<td id=\"stpi_affSousItemImg\" style=\"width: " . $objBdd->stpi_trsBddToHTML(($nbImgSousItemWidthMaxBig / 2)) . "px; text-align: center;\" >\n");
															print("<img style=\"cursor: pointer;\" onclick=\"window.open ('sousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $objBdd->stpi_trsBddToHTML($objImgSousItem->stpi_getNbID()) . "', '', config='height=550, width=550, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')\" width=\"" . $objBdd->stpi_trsBddToHTML(($objImgSousItem->stpi_getNbWidth() / 2)) . "px\" height=\"" . $objBdd->stpi_trsBddToHTML(($objImgSousItem->stpi_getNbHeight() / 2)) . "px\" alt=\"" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getObjItemLg()->stpi_getStrName()) . "\" src=\"./sousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $objBdd->stpi_trsBddToHTML($objImgSousItem->stpi_getNbID()) . "\" />\n");
															print("</td>\n");
														}
													}
												}
											}	
										}
									}
								}
							}
							mysql_free_result($result);
						}
						
						print("<td style=\"text-align: left; vertical-align: top;\" >\n");
						print("<table>\n");
						print("<tr>\n");
						$i = 0;
						foreach ($arrNbImageID as $nbImageID)
						{
							$i++;
							if($i > 4)
							{
								print("</tr>\n");
								print("<tr>\n");
								$i = 1;
							}
							print("<td style=\"width: " . $objBdd->stpi_trsBddToHTML(($nbImgSousItemWidthMaxSmall / 2)) . "px; vertical-align: top; text-align: center;\" >\n");
							
							$SQL1 = "SELECT nbSousItemID";
							$SQL1 .= " FROM stpi_item_SousItem_ImgSousItem";
							$SQL1 .= " WHERE nbImageID = '" . $objBdd->stpi_trsInputToBdd($nbImageID) . "'";
							if ($result1 = $objBdd->stpi_select($SQL1))
							{
								$nbSousItemMatch = mysql_num_rows($result1);
								if ($row1 = mysql_fetch_assoc($result1))
								{
									if ($objSousItem->stpi_setNbID($row1["nbSousItemID"]))
									{
										if ($arrNbImageIDNumImage = $objSousItem->stpi_selNbImageID())
										{
											if (isset($arrNbImageIDNumImage[1]))
											{
												if ($objImgSousItem->stpi_setNbID($arrNbImageIDNumImage[1]))
												{
													if ($objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
													{
														if ($objItem->stpi_setObjItemLgFromBdd())
														{
															$nbSousItemResizedWidthSmall = $objImgSousItem->stpi_getNbWidth() / 2;
															$nbSousItemResizedHeightSmall = $objImgSousItem->stpi_getNbHeight() / 2;
															if (isset($arrNbImageIDNumImage[2]))
															{
																if ($objImgSousItem->stpi_setNbID($arrNbImageIDNumImage[2]))
																{
																	if ($objImgSousItem->stpi_setNbWidthHeightFromImg())
																	{
																		print("<img style=\"cursor: pointer;\" onclick=\"stpi_affSousItemTagImgLarge(" . $objBdd->stpi_trsBddToHTML($arrNbImageIDNumImage[2]) . ", " . $objBdd->stpi_trsBddToHTML(($objImgSousItem->stpi_getNbWidth() / 2)) . ", " . $objBdd->stpi_trsBddToHTML(($objImgSousItem->stpi_getNbHeight() / 2)) . ", '" . $objBdd->stpi_trsBddToHTML(str_replace("'", "", $objItem->stpi_getObjItemLg()->stpi_getStrName())) . "')\" width=\"" . $objBdd->stpi_trsBddToHTML($nbSousItemResizedWidthSmall) . "px\" height=\"" . $objBdd->stpi_trsBddToHTML($nbSousItemResizedHeightSmall) . "px\" src=\"./sousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $objBdd->stpi_trsBddToHTML($arrNbImageIDNumImage[1]) . "\"");
																	}
																}
															}
															else
															{
																print("<img width=\"" . $objBdd->stpi_trsBddToHTML($nbSousItemResizedWidthSmall) . "px\" height=\"" . $objBdd->stpi_trsBddToHTML($nbSousItemResizedHeightSmall) . "px\" src=\"./sousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $objBdd->stpi_trsBddToHTML($arrNbImageIDNumImage[1]) . "\"");
															}
															
															$SQL2 = "SELECT stpi_item_SousItem_Attribut.nbAttributID, COUNT(*) AS nbMatch";
															$SQL2 .= " FROM stpi_item_SousItem_ImgSousItem, stpi_item_SousItem_Attribut, stpi_item_Attribut, stpi_item_TypeAttribut_Lg";
															$SQL2 .= " WHERE stpi_item_SousItem_ImgSousItem.nbImageID = '" . $objBdd->stpi_trsInputToBdd($nbImageID) . "'";
															$SQL2 .= " AND stpi_item_SousItem_Attribut.nbSousItemID = stpi_item_SousItem_ImgSousItem.nbSousItemID";
															$SQL2 .= " AND stpi_item_SousItem_Attribut.nbAttributID = stpi_item_Attribut.nbAttributID";
															$SQL2 .= " AND stpi_item_Attribut.nbTypeAttributID = stpi_item_TypeAttribut_Lg.nbTypeAttributID";
															$SQL2 .= " AND stpi_item_TypeAttribut_Lg.strLg = '" . $objBdd->stpi_trsInputToBdd(LG) . "'";
															$SQL2 .= " GROUP BY stpi_item_SousItem_Attribut.nbAttributID";
															$SQL2 .= " HAVING nbMatch = '" . $objBdd->stpi_trsInputToBdd($nbSousItemMatch) . "'";
															$SQL2 .= " ORDER BY stpi_item_TypeAttribut_Lg.strName";
															
															$arrStrName = array();
															if ($result2 = $objBdd->stpi_select($SQL2))
															{
																while ($row2 = mysql_fetch_assoc($result2))
																{
																	if ($objAttribut->stpi_setNbID($row2["nbAttributID"]))
																	{									
																		if ($objAttribut->stpi_setObjAttributLgFromBdd())
																		{
																			$arrStrName[] = $objAttribut->stpi_getObjAttributLg()->stpi_getStrName();
																		}	
																	}
																}
																mysql_free_result($result2);
															}
													
													
															print(" alt=\"" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getObjItemLg()->stpi_getStrName()));
															foreach ($arrStrName as $strName)
															{
																print(", " . $objBdd->stpi_trsBddToHTML($strName));
															}
															print("\"/>\n");
															
															print("<br/>\n");
															foreach ($arrStrName as $strName)
															{
																print("<h5>" . $objBdd->stpi_trsBddToHTML($strName) . "</h5>\n");
															}
														}
													}
												}
											}
										}
									}
									mysql_free_result($result1);
								}
							}
							print("</td>\n");
						}
						print("</tr>\n");
						print("</table>\n");
						print("</td>\n");
						print("</tr>\n");
						print("</table>\n");
					}					
				}
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