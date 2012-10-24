<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/page/clspagepublic.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objPage = new clspagepublic();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objPage->stpi_setNbID($_GET["nbPageID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objPage->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objPage->stpi_getObjPageLg()->stpi_deletePageId($_GET["nbPageID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objPage->stpi_delete($_GET["nbPageID"]))
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
		$objPage->stpi_affDelete();
	}
?>
