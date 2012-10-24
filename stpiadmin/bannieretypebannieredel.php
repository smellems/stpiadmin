<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/banniere/clsbanniere.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$objBanniere = new clsbanniere();
	$objBanniereLg =& $objBanniere->stpi_getObjBanniereLg();
	$objTypeBanniere =& $objBanniere->stpi_getObjTypeBanniere();
	$objTypeBanniereLg =& $objTypeBanniere->stpi_getObjTypeBanniereLg();
		
	if ($_GET["nbConfirmed"] == 1)
	{
		if (!$objTypeBanniere->stpi_setNbID($_GET["nbTypeBanniereID"]))
		{
			exit;
		}
		if (!$objTypeBanniere->stpi_getBoolDelete())
		{
			$objTypeBanniere->stpi_affNoDelete();
			exit;
		}
		
		if (!$objBdd->stpi_startTransaction())
		{
			exit;
		}
				
		if ($arrNbBanniereID = $objTypeBanniere->stpi_selNbBanniereID())
		{
			foreach ($arrNbBanniereID as $nbBanniereID)
			{
				print($nbBanniereID . "<br/>");
				if (!$objBanniere->stpi_setNbID($nbBanniereID))
				{
					$objBdd->stpi_rollback();
					exit;
				}
				
				if (!$objBanniere->stpi_setArrObjBanniereLgFromBdd())
				{
					$objBdd->stpi_rollback();
					exit;
				}
				
				$arrObjBanniereLg = $objBanniere->stpi_getArrObjBanniereLg();
				
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
				
				if (!$objBanniereLg->stpi_deleteBanniereId($nbBanniereID))
				{
					$objBdd->stpi_rollback();
					exit;
				}
				if (!$objBanniere->stpi_delete($nbBanniereID))
				{
					$objBdd->stpi_rollback();
					exit;
				}
			}
		}
		if (!$objTypeBanniereLg->stpi_deleteTypeBanniereId($_GET["nbTypeBanniereID"]))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objTypeBanniere->stpi_delete($_GET["nbTypeBanniereID"]))
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
		$objTypeBanniere->stpi_affDelete();
	}
?>