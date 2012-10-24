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
			
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/registre");
	$objBody = new clsbody();
	$objBdd = clsBdd::singleton();
	$objUser = new clsuser();
	$objRegistre = new clsregistre();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objLock = new clslock($strPage, "login.php");
		
	$objLock->stpi_pageNotEncrypted();
	
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objItem = & $objRegistreSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objItemLg =& $objItem->stpi_getObjItemLg();
	$objAttribut =& $objSousItem->stpi_getObjAttribut();
	$objTypeAttribut =& $objAttribut->stpi_getObjTypeAttribut();
	$objImgSousItem =& $objSousItem->stpi_getObjImg();
	
	$nbAvailable = 0;
	
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
		<div id="fullcontent">
			<?php
				$objMotd->stpi_affPublic();

				$objJavaScript->stpi_affArrLang();
				$objJavaScript->stpi_affNoAjax();
				$objJavaScript->stpi_affCreateXmlHttp();
				$objJavaScript->stpi_affNoJavaScript();
									
				$objSousItem->stpi_affJsSousItemToCommandeRegistre();
				
				$objSousItem->stpi_setNbNumImage(2);				
				$nbImgSousItemWidthMaxBig = $objSousItem->stpi_getNbImgWidthMax();
				$nbImgSousItemHeightMaxBig = $objSousItem->stpi_getNbImgHeightMax();			
				
				if ($objSousItem->stpi_setNbID($_GET["nbSousItemID"]))
				{
					if ($objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
					{
						if ($objRegistre->stpi_setNbID($_GET["nbRegistreID"]))
						{
							if ($objRegistre->stpi_chkIfActif())
							{
								if ($objRegistre->stpi_chkIfNotExpired())
								{
									if ($objRegistreSousItem->stpi_setNbID($objRegistre->stpi_getNbID(), $objSousItem->stpi_getNbID()))
									{
										if ($objClient->stpi_setNbID($objRegistre->stpi_getNbClientID()))
										{
											if ($objItem->stpi_setObjItemLgFromBdd())
											{
												print("<h2>" . $objTexte->stpi_getArrTxt("registrede") . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrPrenom() . " " . $objClient->stpi_getStrNom()) . " (" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . ")</h2>\n");
												
												print("<input type=\"hidden\" id=\"nbRegistreID\" value=\"" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbID()) . "\" />");
											
												print("<table style=\"width: 100%; padding: 0px; margin: 10px 0px;\">\n");
												print("<tr>\n");
												if (!$arrNbImageID = $objSousItem->stpi_selNbImageID())
												{
													$arrNbImageID = array();
												}
												if (isset($arrNbImageID[2]))
												{
													print("<td style=\"vertical-align: top; text-align: center; width:" . $objBdd->stpi_trsBddToHTML($nbImgSousItemWidthMaxBig / 2) . "px; height:" . $objBdd->stpi_trsBddToHTML($nbImgSousItemHeightMaxBig / 2) . "px;\" >\n");
													print("<img style=\"cursor: pointer;\" onclick=\"window.open ('sousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $objBdd->stpi_trsBddToHTML($arrNbImageID[2]) . "', '', config='height=" . $objBdd->stpi_trsBddToHTML($nbImgSousItemHeightMaxBig) . ", width=" . $objBdd->stpi_trsBddToHTML($nbImgSousItemWidthMaxBig) . ", toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')\" alt=\"" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . "\" src=\"./sousitemimgaff.php?nbImageID=" . $objBdd->stpi_trsBddToHTML($arrNbImageID[2]) . "\" width=\"" . $objBdd->stpi_trsBddToHTML($nbImgSousItemWidthMaxBig / 2) . "px\" height=\"" . $objBdd->stpi_trsBddToHTML($nbImgSousItemHeightMaxBig / 2) . "px\" />\n");
													print("</td>\n");				
												}
												print("<td style=\"vertical-align: top; text-align: left;\" >\n");
												print("<h3>\n");
												print($objBdd->stpi_trsBddToHTML($objItemLg->stpi_getStrName()));
												print("</h3>\n");
												$strDesc = $objItemLg->stpi_getStrDesc();
												if (!empty($strDesc))
												{
													print("<p>" . $objBdd->stpi_trsBddToHTML($strDesc) . "</p>\n");
												}
												
												
												if (!$arrNbAttributID = $objSousItem->stpi_selNbAttributID())
												{
													$arrNbAttributID = array();
												}
										
												foreach ($arrNbAttributID as $nbAttributID)
												{
													if ($objAttribut->stpi_setNbID($nbAttributID))
													{
														if ($objTypeAttribut->stpi_setNbID($objAttribut->stpi_getNbTypeAttributID()))
														{
															if ($objTypeAttribut->stpi_setObjTypeAttributLgFromBdd())
															{
																$objTypeAttributLg =& $objTypeAttribut->stpi_getObjTypeAttributLg();
																print("<h4>\n");
																print($objBdd->stpi_trsBddToHTML($objTypeAttributLg->stpi_getStrName()));
																print("</h4>\n");
															}
														}
													if ($objAttribut->stpi_setObjAttributLgFromBdd())
													{
														$objAttributLg =& $objAttribut->stpi_getObjAttributLg();
														print("<h5>&nbsp;&nbsp;\n");
														print($objBdd->stpi_trsBddToHTML($objAttributLg->stpi_getStrName()));
														print("</h5>\n");
														
														$strDesc = $objAttributLg->stpi_getStrDesc();
														if (!empty($strDesc))
														{
															print("<p>&nbsp;&nbsp;" . $objBdd->stpi_trsBddToHTML($strDesc) . "</p>\n");
														}
													}
												}
												
												
												
												print("</td>\n");
												print("</tr>\n");

												print("<tr>\n");
												print("<td colspan=\"2\" style=\"vertical-align: top; text-align: right;\" >\n");
												$objSousItem->stpi_affNewPublic();
												$nbAvailable = $objSousItem->stpi_getNbQte();
												if ($nbAvailable > ($objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu()))
												{	
													$nbAvailable = $objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu();
												}												
												
												$objSousItem->stpi_affPrixRegistrePublic($nbAvailable);
												print("</td>\n");
												print("</tr>\n");
												print("</table>\n");
												}
											}
										}
									}
								}
							}
						}
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