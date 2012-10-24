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
	$objClient =& $objRegistre->stpi_getObjClient();
	$objCountry =& $objClient->stpi_getObjCountry();
	$objProvince =& $objCountry->stpi_getObjProvince();
	
	$objUser = $objUser->stpi_getObjUserFromSession();
	$ok = true;
	if ($objUser->stpi_getNbTypeUserID() == 2)
	{
		if ($objClient->stpi_chkNbID($objUser->stpi_getNbID()))
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
			if (!$objRegistre->stpi_insert())
			{
				$ok = false;
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