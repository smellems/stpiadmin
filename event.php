<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/content/clsmenu.php");
	require_once("./stpiadmin/includes/classes/content/clsfooter.php");
	require_once("./stpiadmin/includes/classes/motd/clsmotd.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/event/clsevent.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objTexte = new clstexte("./texte/event");
	$objBody = new clsbody();
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objMenu = new clsmenu($strPage);
	$objFooter = new clsfooter();
	$objBdd = clsBdd::singleton();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageNotEncrypted();
	
	$objEvent = new clsevent();
	$objEventLg =& $objEvent->stpi_getObjEventLg();
	$objDateHeure =& $objEvent->stpi_getObjDateHeure();
	$objDate =& $objDateHeure->stpi_getObjDate();
	$objTypeEvent =& $objEvent->stpi_getObjTypeEvent();
	$objTypeEventLg =& $objTypeEvent->stpi_getObjTypeEventLg();
	$objAdresse =& $objEvent->stpi_getObjAdresse();
	// Début page
	$objHead->stpi_affPublicHead();
	$objBody->stpi_affBodyHeader($objTexte->stpi_getArrTxt("welcome"));

	// <!-- MainContentStart -->
	$objMotd->stpi_affPublic();

	if ($arrNbTypeEventID = $objTypeEvent->stpi_selAll())
	{
		foreach ($arrNbTypeEventID as $nbTypeEventID)
		{
			if ($objTypeEvent->stpi_setNbID($nbTypeEventID))
			{
				if ($objTypeEvent->stpi_setObjTypeEventLgFromBdd())
				{
					print("<a name=\"typeevent" . $objBdd->stpi_trsBddToHTML($nbTypeEventID) . "\"></a>\n");
					print("<h2>" . $objBdd->stpi_trsBddToHTML($objTypeEventLg->stpi_getStrName()) . "</h2>\n");
					$strDesc = $objTypeEventLg->stpi_getStrDesc(); 
					if(!empty($strDesc))
					{
						print("<p>\n");
						print($objBdd->stpi_trsBddToBBCodeHTML($strDesc) . "\n");
						print("</p>\n");
					}				
					print("<br/>\n");
				}
		
				if ($arrAdresseID = $objTypeEvent->stpi_selNbAdresseIDPublic())
				{
					foreach ($arrAdresseID as $nbAdresseID)
					{
						if ($objAdresse->stpi_setNbID($nbAdresseID))
						{
							print("<h1>" . $objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrEndroit()) . "</h1>\n");
							print("<h2>" . $objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrAdresse()) . ", ");
							print($objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrVille()) . ", ");
							$strProvinceID = $objAdresse->stpi_getStrProvinceID();
							if (!empty($strProvinceID) && $strProvinceID != "isNULL")
							{
								print($objBdd->stpi_trsBddToHTML($strProvinceID) . ", ");
							}
							print($objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrCountryID()) . ", ");
							print($objBdd->stpi_trsBddToHTML($objAdresse->stpi_getStrCodePostal()));
							print("</h2>\n");
					
							if ($arrNbEventID = $objTypeEvent->stpi_selNbEventID($nbAdresseID))
							{
								foreach ($arrNbEventID as $nbEventID)
								{
									if ($objEvent->stpi_setNbID($nbEventID))
									{
										print("<a name=\"event" . $objBdd->stpi_trsBddToHTML($nbEventID) . "\"></a>\n");
										print("<table border=\"0\">\n");
										print("<tr>\n");					
										$nbImageID = $objEvent->stpi_getNbImageID();
										if (!empty($nbImageID))
										{
											print("<td style=\"text-align: center; vertical-align: middle;  width:" .  $objBdd->stpi_trsBddToHTML($objEvent->stpi_getNbImgWidthMax()) . "px; height:" .  $objBdd->stpi_trsBddToHTML($objEvent->stpi_getNbImgHeightMax()) . "px;\">\n");
											print("<img alt=\"" .  $objBdd->stpi_trsBddToHTML($nbImageID) . "\" src=\"./eventimgaff.php?nbImageID=" .  $objBdd->stpi_trsBddToHTML($nbImageID) . "\" />\n");
											print("</td>\n");
										}
	
										print("<td style=\"vertical-align: top;\">\n");
							
										if ($objEvent->stpi_setObjEventLgFromBdd())
										{
											print("<h3>" . $objBdd->stpi_trsBddToHTML($objEventLg->stpi_getStrName()) . "</h3>\n");
											print("<p>\n");
											$strDesc = $objEventLg->stpi_getStrDesc();
											if (!empty($strDesc))
											{						
												print($objBdd->stpi_trsBddToBBCodeHTML($strDesc) . "<br/>\n");
											}
											$strLien = $objEventLg->stpi_getStrLien();
											if (!empty($strLien))
											{
												print("<a target=\"_blank\" href=\"" . $objBdd->stpi_trsBddToHTML($strLien) . "\">" . $objBdd->stpi_trsBddToHTML($strLien) . "</a><br/>\n");
											}
											print("</p>\n");
										}
										if ($arrNbDateHeureID = $objEvent->stpi_selNbDateHeureIDPublic())
										{
											print("<p><b>\n");
											foreach ($arrNbDateHeureID as $nbDateHeureID)
											{
												if ($objDateHeure->stpi_setNbID($nbDateHeureID))
												{
													list($dtDebut, $dtHeure) = explode(" ", $objDateHeure->stpi_getDtDebut());
													print($objBdd->stpi_trsBddToHTML($objDate->stpi_trsDateISOtoTexte($dtDebut)) . " " . $objBdd->stpi_trsBddToHTML($dtHeure) . " ");
													print($objBdd->stpi_trsBddToHTML($objTexte->stpi_getArrTxt("until")) . " ");
											
													list($dtFin, $dtHeure) = explode(" ", $objDateHeure->stpi_getDtFin());
													if ($dtDebut == $dtFin)
													{
														print($objBdd->stpi_trsBddToHTML($dtHeure));
													}
													else
													{
														print($objBdd->stpi_trsBddToHTML($objDate->stpi_trsDateISOtoTexte($dtFin)) . " " . $objBdd->stpi_trsBddToHTML($dtHeure) . " ");
													}
													print("<br/>\n");
												}
											}
											print("</b></p>\n");
										}
										print("</td>\n");
										print("</tr>\n");
										print("</table>\n");
										print("<br/>\n");
									}
								}
							}									
						}
					}
				}
			}
		}
	}
	// <!-- MainContentEnd -->

	$objFooter->stpi_affFooter();
?>
