<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/registre/clsregistre.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objRegistre = new clsregistre();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objRegistre->stpi_setNbID($_GET["nbRegistreID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if ($objBdd->stpi_chkExists($_GET["nbRegistreID"], "nbRegistreID", "stpi_registre_Registre_SousItem"))
				{
					if (!$objRegistre->stpi_getObjRegistreSousItem()->stpi_deleteRegistreID($_GET["nbRegistreID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objRegistre->stpi_delete($_GET["nbRegistreID"]))
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
		$objRegistre->stpi_affDelete();
	}
?>
