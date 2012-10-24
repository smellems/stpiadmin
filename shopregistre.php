<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	require_once("./stpiadmin/includes/classes/content/clsnavigator.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/registre");
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objBody = new clsbody();
	$objBdd = clsBdd::singleton();
	$objJavaScript = new clsjavascript();
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageNotEncrypted();
	
	$objRegistre = new clsregistre();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	$objItem =& $objRegistreSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objAttribut =& $objSousItem->stpi_getObjAttribut();
	$objTypeAttribut =& $objAttribut->stpi_getObjTypeAttribut();
	
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
								
				if ($nbRegistreID = $objRegistre->stpi_selNbIDFromStrRegistreCode(strtoupper($_GET["strRegistreCode"])))
				{
					if ($objRegistre->stpi_setNbID($nbRegistreID))
					{
						if ($objRegistre->stpi_chkIfActif())
						{
							if ($objRegistre->stpi_chkIfNotExpired())
							{
								if ($objClient->stpi_setNbID($objRegistre->stpi_getNbClientID()))
								{
									print("<h2>" . $objTexte->stpi_getArrTxt("registrede") . " " . $objBdd->stpi_trsBddToHTML($objClient->stpi_getStrPrenom() . " " . $objClient->stpi_getStrNom()) . " (" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getStrRegistreCode()) . ")</h2>\n");
								
									$strMessage = $objRegistre->stpi_getStrMessage();
									if (!empty($strMessage))
									{
										print("<p>" . $objBdd->stpi_trsBddToHTML($strMessage) . "</p>\n");
									}
									
									print("<input type=\"hidden\" id=\"nbRegistreID\" value=\"" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbID()) . "\" />");
									
									if (!$arrNbSousItemID = $objRegistre->stpi_selNbSousItemIDPublic())
									{
										$arrNbSousItemID = array();
									}
									
									$objNavigator = new clsnavigator($arrNbSousItemID, $_GET["nbPage"], 20);
									
									$objNavigator->stpi_setAllVariables();
									
									$arrNbSousItemID = $objNavigator->stpi_getArrNbID();
									
									$objNavigator->stpi_aff();
		
									print("<table style=\"margin: 0px; padding: 10px 10px;\" >\n");
									print("<tr>\n");
									
									$i = 1; 
									foreach ($arrNbSousItemID as $nbSousItemID)
									{
										if (!$objSousItem->stpi_setNbID($nbSousItemID))
										{
											continue;
										}
										if (!$objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
										{
											continue;
										}
										if (!$objItem->stpi_setObjItemLgFromBdd())
										{
											continue;
										}
										if (!$objRegistreSousItem->stpi_setNbID($objRegistre->stpi_getNbID(), $objSousItem->stpi_getNbID()))
										{
											continue;
										}
										if (!$arrNbImageID = $objSousItem->stpi_selNbImageID())
										{
											$arrNbImageID = array();
										}
										
										$nbAvailable = $objSousItem->stpi_getNbQte();
										if ($nbAvailable > ($objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu()))
										{	
											$nbAvailable = $objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu();
										}
										
										if ($i == 5)
										{
											print("</tr>\n");
											print("<tr>\n");
											$i = 1;
										}
										
										$objSousItem->stpi_setNbNumImage(1);
										$objItemLg =& $objItem->stpi_getObjItemLg();									
		
										print("<td style=\" width: " . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbImgWidthMax()) . "px; vertical-align: top; margin: 0px; padding: 10px 10px;\" >\n");
										
										if (isset($arrNbImageID[1]))
										{
											print("<div style=\"width: " . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbImgWidthMax()) . "px; height: " . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbImgHeightMax()) . "px; text-align: center; vertical-align: middle;\" >");
											print("<a href=\"./itemregistre.php?l=" . LG . "&amp;nbRegistreID=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbID()) . "&amp;nbSousItemID=" . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbID()) . "\" >\n");										print("<img alt=\"" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . "\" src=\"./sousitemimgaff.php?nbImageID=" . $objBdd->stpi_trsBddToHTML($arrNbImageID[1]) . "\" />\n");
											print("</a>\n");								
											print("</div>");
										}
										
										print("<h5>");
										print("<a class=\"titre\" href=\"./itemregistre.php?l=" . LG . "&amp;nbRegistreID=" . $objBdd->stpi_trsBddToHTML($objRegistre->stpi_getNbID()) . "&amp;nbSousItemID=" . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbID()) . "\" >\n");
										print($objBdd->stpi_trsBddToHTML($objItemLg->stpi_getStrName()));
										print("</a>\n");
										print("</h5>\n");		
		
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
												}																
											}
										}
										
										$objSousItem->stpi_affNewPublic();
										$objSousItem->stpi_affPrixRegistrePublic($nbAvailable);
															
										print("</td>\n");
										$i++;
									}
									print("</tr>\n");
									print("</table>\n");
									
									$objNavigator->stpi_aff();
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