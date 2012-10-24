<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clscommandesousitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objCommandeSousItem = new clscommandesousitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;

	if(!$objCommandeSousItem->stpi_setNbID($_POST["nbCommandeID"], $_POST["nbSousItemID"]))
	{
		$ok = false;
	}
	
	if(!$objCommandeSousItem->stpi_setStrItemCode($_POST["strItemCode"]))
	{
		$ok = false;
	}

	if(!$objCommandeSousItem->stpi_setNbQte($_POST["nbQte"]))
	{
		$ok = false;
	}
	
	if(!$objCommandeSousItem->stpi_setNbPrix($_POST["nbPrix"]))
	{
		$ok = false;
	}
	
	if(!$objCommandeSousItem->stpi_setStrSousItemDesc($_POST["strSousItemDesc"]))
	{
		$ok = false;
	}
	
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$objCommandeSousItem->stpi_update())
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbCommandeID=" . $_POST["nbCommandeID"]);
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