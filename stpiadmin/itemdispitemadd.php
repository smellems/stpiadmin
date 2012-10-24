<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsdispitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objDispItem = new clsdispitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjDispItemLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjDispItemLg[$strLg] = new clsdispitemlg();
		if (!$arrObjDispItemLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjDispItemLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjDispItemLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
		{
			$ok = false;
		}
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbDispItemID = $objDispItem->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjDispItemLg[$strLg]->stpi_setNbDispItemID($nbDispItemID))
						{
							if (!$arrObjDispItemLg[$strLg]->stpi_insert())
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
					print("redirect-nbDispItemID=" . $objDispItem->stpi_getNbID());
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