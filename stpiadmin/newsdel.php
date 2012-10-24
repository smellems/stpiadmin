<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/news/clsnews.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNews = new clsnews();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if ($objBdd->stpi_startTransaction())
		{
			$ok = true;
			if (!$objNews->stpi_getObjNewsLg()->stpi_deleteNewsId($_GET["nbNewsID"]))
			{
				$ok = false;
			}
			if ($ok)
			{
				if (!$objNews->stpi_delete($_GET["nbNewsID"]))
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
	else
	{
		$objNews->stpi_affDelete();
	}
?>