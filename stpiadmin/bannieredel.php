<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/banniere/clsbanniere.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$objBanniere = new clsbanniere();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if (!$objBanniere->stpi_setNbID($_GET["nbBanniereID"]))
		{
			exit;
		}
		
		if (!$objBanniere->stpi_setArrObjBanniereLgFromBdd())
		{
			exit;
		}
		
		$arrObjBanniereLg = $objBanniere->stpi_getArrObjBanniereLg();
		
		if (!$objBdd->stpi_startTransaction())
		{
			exit;
		}
		
		foreach ($arrObjBanniereLg as $objBanniereLg)
		{
			$objImg =& $objBanniereLg->stpi_getObjImg();
			
			if ($objBanniereLg->stpi_getNbImageID() != 0)
			{
				if (!$objImg->stpi_delete($objBanniereLg->stpi_getNbImageID()))
				{
					$objBdd->stpi_rollback();
					exit;
				}
			}
		}
		
		if (!$objBanniereLg->stpi_deleteBanniereId($objBanniere->stpi_getNbID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
			
		if (!$objBanniere->stpi_delete($objBanniere->stpi_getNbID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		
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
		$objBanniere->stpi_affDelete();
	}
?>