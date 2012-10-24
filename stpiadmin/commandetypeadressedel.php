<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clsadresse.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objAdresse = new clsadresse();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objAdresse->stpi_getObjTypeAdresse()->stpi_setNbID($_GET["nbTypeAdresseID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objAdresse->stpi_getObjTypeAdresse()->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_chkExists($_GET["nbTypeAdresseID"], "nbTypeAdresseID", "stpi_commande_Adresse"))
			{
				$ok = false;
				$objAdresse->stpi_getObjTypeAdresse()->stpi_affNoDelete();
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objAdresse->stpi_getObjTypeAdresse()->stpi_getObjTypeAdresseLg()->stpi_deleteTypeAdresseId($_GET["nbTypeAdresseID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objAdresse->stpi_getObjTypeAdresse()->stpi_delete($_GET["nbTypeAdresseID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-");
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
	else
	{
		$objAdresse->stpi_getObjTypeAdresse()->stpi_affDelete();
	}
?>