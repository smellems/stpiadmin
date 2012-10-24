<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/registre/clsregistresousitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objRegistreSousItem = new clsregistresousitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;

	if(!$objRegistreSousItem->stpi_setNbRegistreID($_POST["nbRegistreID"]))
	{
		$ok = false;
	}
	
	if(!$objRegistreSousItem->stpi_setNbSousItemID($_POST["nbSousItemID"]))
	{
		$ok = false;
	}
	
	if(!$objRegistreSousItem->stpi_setStrItemCode($_POST["strItemCode"]))
	{
		$ok = false;
	}

	if(!$objRegistreSousItem->stpi_setNbQteVoulu($_POST["nbQteVoulu"]))
	{
		$ok = false;
	}
	
	if(!$objRegistreSousItem->stpi_setNbQteRecu(0))
	{
		$ok = false;
	}
	
	if(!$objRegistreSousItem->stpi_setStrSousItemDesc($_POST["strSousItemDesc"]))
	{
		$ok = false;
	}

	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$objRegistreSousItem->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbRegistreID=" . $_POST["nbRegistreID"]);
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
