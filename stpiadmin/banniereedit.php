<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/banniere/clsbanniere.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$objBanniere = new clsbanniere();
	
	if ($objBanniere->stpi_setNbID($_POST["nbBanniereID"]))
	{
		if ($objBanniere->stpi_setArrObjBanniereLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjBanniereLg = $objBanniere->stpi_getArrObjBanniereLg();
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
				if (!$arrObjBanniereLg[$strLg]->stpi_setNbBanniereID($objBanniere->stpi_getNbID()))
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
					if (!$objBanniere->stpi_update())
					{
						$ok = false;
					}
					if ($ok)
					{
						foreach ($arrLang as $strLg)	
						{
							if ($ok)
							{
								if (!$arrObjBanniereLg[$strLg]->stpi_update())
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
		}
	}
?>