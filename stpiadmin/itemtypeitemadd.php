<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clstypeitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeItem = new clstypeitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjTypeItemLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjTypeItemLg[$strLg] = new clstypeitemlg();
		if (!$arrObjTypeItemLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjTypeItemLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjTypeItemLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
		{
			$ok = false;
		}
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbTypeItemID = $objTypeItem->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjTypeItemLg[$strLg]->stpi_setNbTypeItemID($nbTypeItemID))
						{
							if (!$arrObjTypeItemLg[$strLg]->stpi_insert())
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
					print("redirect-nbTypeItemID=" . $objTypeItem->stpi_getNbID());
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