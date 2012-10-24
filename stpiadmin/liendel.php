<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/lien/clslien.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objLien = new clslien();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objLien->stpi_setNbID($_GET["nbLienID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				$nbImageID = $objLien->stpi_getNbImageID();
				if ($nbImageID != 0)
				{
					if (!$objLien->stpi_getObjImg()->stpi_delete($nbImageID))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objLien->stpi_getObjLienLg()->stpi_deleteLienId($_GET["nbLienID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objLien->stpi_delete($_GET["nbLienID"]))
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
		$objLien->stpi_affDelete();
	}
?>