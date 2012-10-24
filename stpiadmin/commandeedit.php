<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clscommande.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objCommande = new clscommande();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objCommande->stpi_setNbID($_POST["nbCommandeID"]))
	{
		$ok = true;
		if(!$objCommande->stpi_setNbTypeCommandeID($_POST["nbTypeCommandeID"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setNbStatutCommandeID($_POST["nbStatutCommandeID"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setNbMethodPayeID($_POST["nbMethodPayeID"]))
		{
			$ok = false;
		}
		
		if ($objCommande->stpi_getObjMethodPaye()->stpi_setNbID($objCommande->stpi_getNbMethodPayeID()))
		{
			if ($objCommande->stpi_getObjMethodPaye()->stpi_getBoolCarte() == 1)
			{
				if ($objCommande->stpi_getNbInfoCarteID() != 0 AND !$objCommande->stpi_getObjInfoCarte()->stpi_setNbID($objCommande->stpi_getNbInfoCarteID()))
				{
					$ok = false;
				}
				
				if(!$objCommande->stpi_getObjInfoCarte()->stpi_setStrNom($_POST["strCarteNom"]))
				{
					$ok = false;
				}

				if(!$objCommande->stpi_getObjInfoCarte()->stpi_setStrNum($_POST["strCarteNum"]))
				{
					$ok = false;
				}
				
				if(!$objCommande->stpi_getObjInfoCarte()->stpi_setDtDateExpir($_POST["strCarteDateExpir"]))
				{
					$ok = false;
				}
				
				if(!$objCommande->stpi_getObjInfoCarte()->stpi_setStrCodeSecur($_POST["strCarteCodeSecur"]))
				{
					$ok = false;
				}
			}
		}
		else
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setStrTel($_POST["strTel"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setStrCourriel($_POST["strCourriel"]))
		{
			$ok = false;
		}

		if(!$objCommande->stpi_setNbPrixShipping($_POST["nbPrixShipping"]))
		{
			$ok = false;
		}
	
		if(!$objCommande->stpi_setNbPrixRabais($_POST["nbPrixRabais"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setNbPrixTaxes($_POST["nbPrixTaxes"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setStrCodeSuivi($_POST["strCodeSuivi"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setStrMessage($_POST["strMessage"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setStrLangID($_POST["strLangID"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setDtShipped($_POST["strDtShipped"]))
		{
			$ok = false;
		}
		
		if(!$objCommande->stpi_setDtArrived($_POST["strDtArrived"]))
		{
			$ok = false;
		}		
		
		if ($arrNbTypeAdresseID = $objCommande->stpi_getObjAdresse()->stpi_getObjTypeAdresse()->stpi_selAll())
		{
			foreach($arrNbTypeAdresseID as $nbTypeAdresseID)
			{
				if ($_POST["boolUse" . $nbTypeAdresseID] == "t")
				{
					if (!$objCommande->stpi_getObjAdresse()->stpi_chkStrNom($_POST["strNom" . $nbTypeAdresseID]))
					{
						$ok = false;
					}
					if (!$objCommande->stpi_getObjAdresse()->stpi_chkStrPrenom($_POST["strPrenom" . $nbTypeAdresseID]))
					{
						$ok = false;
					}
					if (!$objCommande->stpi_getObjAdresse()->stpi_chkStrCie($_POST["strCie" . $nbTypeAdresseID]))
					{
						$ok = false;
					}
					if (!$objCommande->stpi_getObjAdresse()->stpi_chkStrAdresse($_POST["strAdresse" . $nbTypeAdresseID]))
					{
						$ok = false;
					}
					if (!$objCommande->stpi_getObjAdresse()->stpi_chkStrVille($_POST["strVille" . $nbTypeAdresseID]))
					{
						$ok = false;
					}
					list($strProvinceID, $strCountryID) = explode("-", $_POST["strProvinceID" . $nbTypeAdresseID]);
					if ($strProvinceID != "" AND $objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_setStrId($strCountryID) AND $objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_chkProvinceInCountry($strProvinceID))
					{
						if (!$objCommande->stpi_getObjAdresse()->stpi_setStrCountryID($strCountryID) OR !$objCommande->stpi_getObjAdresse()->stpi_setStrProvinceID($strProvinceID))
						{
							$ok = false;
						}
					}
					else
					{
						if (!$objCommande->stpi_getObjAdresse()->stpi_setStrCountryID($_POST["strCountryID" . $nbTypeAdresseID]) OR !$objCommande->stpi_getObjAdresse()->stpi_setStrProvinceID(""))
						{
							$ok = false;
						}
					}
		
					if (!$objCommande->stpi_getObjAdresse()->stpi_setStrCodePostal($_POST["strCodePostal" . $nbTypeAdresseID]))
					{
						$ok = false;
					}
				}
			}
		}
		else
		{
			$ok = false;
		}
		
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if ($ok)
				{
					if ($objCommande->stpi_getObjMethodPaye()->stpi_getBoolCarte() == 1)
					{					
						if($objBdd->stpi_chkExists($objCommande->stpi_getNbInfoCarteID(), "nbInfoCarteID", "stpi_commande_InfoCarte"))
						{
							if (!$objCommande->stpi_getObjInfoCarte()->stpi_update())
							{
								$ok = false;
							}
						}
						else
						{
							if ($objCommande->stpi_getObjInfoCarte()->stpi_insert())
							{
								if (!$objCommande->stpi_setNbInfoCarteID($objBdd->stpi_getInsertID()))
								{
									$ok = false;
								}
							}
							else
							{
								$ok = false;
							}
						}
					}
					else
					{
						if($objCommande->stpi_getNbInfoCarteID() != 0 AND $objBdd->stpi_chkExists($objCommande->stpi_getNbInfoCarteID(), "nbInfoCarteID", "stpi_commande_InfoCarte"))
						{
							if ($objCommande->stpi_getObjInfoCarte()->stpi_delete($objCommande->stpi_getNbInfoCarteID()))
							{
								if (!$objCommande->stpi_setNbInfoCarteID(0))
								{
									$ok = false;
								}
							}
							else
							{
								$ok = false;
							}
						}
					}
				}
				if ($ok)
				{
					if (!$objCommande->stpi_update())
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					foreach($arrNbTypeAdresseID as $nbTypeAdresseID)
					{
						if ($_POST["boolUse" . $nbTypeAdresseID] == "t")
						{
							if ($objBdd->stpi_chkArrExists(array($nbTypeAdresseID, $objCommande->stpi_getNbID()), array("nbTypeAdresseID", "nbCommandeID"), "stpi_commande_Adresse"))
							{
								if (!$objCommande->stpi_getObjAdresse()->stpi_setNbID($objCommande->stpi_getNbID(), $nbTypeAdresseID))
								{
									$ok = false;
								}
							}
							else
							{
								if (!$objCommande->stpi_getObjAdresse()->stpi_setNbCommandeID($objCommande->stpi_getNbID()))
								{
									$ok = false;
								}
								if (!$objCommande->stpi_getObjAdresse()->stpi_setNbTypeAdresseID($nbTypeAdresseID))
								{
									$ok = false;
								}
							}
							if (!$objCommande->stpi_getObjAdresse()->stpi_setStrNom($_POST["strNom" . $nbTypeAdresseID]))
							{
								$ok = false;
							}
							if (!$objCommande->stpi_getObjAdresse()->stpi_setStrPrenom($_POST["strPrenom" . $nbTypeAdresseID]))
							{
								$ok = false;
							}
							if (!$objCommande->stpi_getObjAdresse()->stpi_setStrCie($_POST["strCie" . $nbTypeAdresseID]))
							{
								$ok = false;
							}
							if (!$objCommande->stpi_getObjAdresse()->stpi_setStrAdresse($_POST["strAdresse" . $nbTypeAdresseID]))
							{
								$ok = false;
							}
							if (!$objCommande->stpi_getObjAdresse()->stpi_setStrVille($_POST["strVille" . $nbTypeAdresseID]))
							{
								$ok = false;
							}
							list($strProvinceID, $strCountryID) = explode("-", $_POST["strProvinceID" . $nbTypeAdresseID]);
							if ($strProvinceID != "" AND $objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_setStrId($strCountryID) AND $objCommande->stpi_getObjAdresse()->stpi_getObjCountry()->stpi_chkProvinceInCountry($strProvinceID))
							{
								if (!$objCommande->stpi_getObjAdresse()->stpi_setStrCountryID($strCountryID) OR !$objCommande->stpi_getObjAdresse()->stpi_setStrProvinceID($strProvinceID))
								{
									$ok = false;
								}
							}
							else
							{
								if (!$objCommande->stpi_getObjAdresse()->stpi_setStrCountryID($_POST["strCountryID" . $nbTypeAdresseID]) OR !$objCommande->stpi_getObjAdresse()->stpi_setStrProvinceID(""))
								{
									$ok = false;
								}
							}
				
							if (!$objCommande->stpi_getObjAdresse()->stpi_setStrCodePostal($_POST["strCodePostal" . $nbTypeAdresseID]))
							{
								$ok = false;
							}
							
							if ($ok)
							{
								if ($objBdd->stpi_chkArrExists(array($nbTypeAdresseID, $objCommande->stpi_getNbID()), array("nbTypeAdresseID", "nbCommandeID"), "stpi_commande_Adresse"))
								{
									if (!$objCommande->stpi_getObjAdresse()->stpi_update())
									{
										$ok = false;
									}
								}
								else
								{
									if (!$objCommande->stpi_getObjAdresse()->stpi_insert())
									{
										$ok = false;
									}
								}
								
							}
						}
						else
						{
							if ($objBdd->stpi_chkArrExists(array($nbTypeAdresseID, $objCommande->stpi_getNbID()), array("nbTypeAdresseID", "nbCommandeID"), "stpi_commande_Adresse"))
							{
								if (!$objCommande->stpi_getObjAdresse()->stpi_delete($objCommande->stpi_getNbID(), $nbTypeAdresseID))
								{
									$ok = false;
								}
							}
						}
					}
				}
				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-nbCommandeID=" . $objCommande->stpi_getNbID());
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
?>