<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/banniere/clsbanniere.php");
	require_once("./includes/classes/banniere/clsbannierelg.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();	
	$objLang = new clslang();	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	$objBanniere = new clsbanniere();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjBanniereLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjBanniereLg[$strLg] = new clsbannierelg();
		if (!$arrObjBanniereLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjBanniereLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjBanniereLg[$strLg]->stpi_setStrLien($_POST["strLien" . $strLg]))
		{
			$ok = false;
		}
	}
	if(!$objBanniere->stpi_setNbTypeBanniereID($_POST["nbTypeBanniereID"]))
	{
		$ok = false;
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbBanniereID = $objBanniere->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjBanniereLg[$strLg]->stpi_setNbBanniereID($nbBanniereID))
						{
							if (!$arrObjBanniereLg[$strLg]->stpi_insert())
							{
								$ok = false;
							}
						}
						else
						{
							$ok = false;
						}
					}
				}
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbBanniereID=" . $objBanniere->stpi_getNbID());
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