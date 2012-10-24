<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsBdd::singleton();
	$objTexte = new clstexte("./texte/registre");	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	$objUser = new clsuser();
	$objRegistre = new clsregistre();
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objCountry =& $objClient->stpi_getObjCountry();
	$objProvince =& $objCountry->stpi_getObjProvince();
	
	$objUser = $objUser->stpi_getObjUserFromSession();
	$ok = true;
	if ($objUser->stpi_getNbTypeUserID() == 2)
	{
		if ($objClient->stpi_chkNbID($objUser->stpi_getNbID()))
		{
			if ($objRegistre->stpi_setNbID($_POST["nbRegistreID"]))
			{
				if ($objUser->stpi_getNbID() == $objRegistre->stpi_getNbClientID())
				{
					if ($objRegistre->stpi_chkIfNotExpired())
					{
						if (!$objRegistre->stpi_setNbClientID($objUser->stpi_getNbID()))
						{
							$ok = false;
						}
						if (!$objRegistre->stpi_setStrMessage($_POST["strMessage"]))
						{
							$ok = false;
						}
						if (!$objRegistre->stpi_setDtFin($_POST["dtFinAnnee"] . "-" . $_POST["dtFinMois"] . "-" . $_POST["dtFinJour"]))
						{
							$ok = false;
						}
						if (!$objRegistre->stpi_setBoolActif($_POST["boolActif"]))
						{
							$ok = false;
						}
						
						if (!$arrNbSousItemID = $objRegistre->stpi_selNbSousItemID())
						{
							$ok = false;
						}
						if ($ok)
						{
							foreach($arrNbSousItemID as $nSousItemID)
							{
								if ($objRegistreSousItem->stpi_setNbID($_POST["nbRegistreID"], $nSousItemID))
								{
									if (!$objRegistreSousItem->stpi_setNbQteVoulu($_POST["nbQteVoulu" . $nSousItemID]))
									{
										$ok = false;
									}
								}
							}
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
					print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notyours") . "</span><br/>\n");
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
	}
	else
	{
		$ok = false;
	}
	
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			foreach($arrNbSousItemID as $nSousItemID)
			{
				if ($objRegistreSousItem->stpi_setNbID($_POST["nbRegistreID"], $nSousItemID))
				{
					if ($objRegistreSousItem->stpi_setNbQteVoulu($_POST["nbQteVoulu" . $nSousItemID]))
					{
						if (!$objRegistreSousItem->stpi_update())
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
			if ($ok)
			{				
				if (!$objRegistre->stpi_update())
				{
					$ok = false;
				}
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbRegistreID=" . $objRegistre->stpi_getNbID());
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
?>