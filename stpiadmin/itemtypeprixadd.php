<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clssousitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSousItem = new clssousitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjTypePrixLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjTypePrixLg[$strLg] = new clstypeprixlg();
		if (!$arrObjTypePrixLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjTypePrixLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjTypePrixLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
		{
			$ok = false;
		}
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbTypePrixID = $objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjTypePrixLg[$strLg]->stpi_setNbTypePrixID($nbTypePrixID))
						{
							if (!$arrObjTypePrixLg[$strLg]->stpi_insert())
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
				if ($arrNbSousItemID = $objSousItem->stpi_selAll())
				{
					foreach ($arrNbSousItemID as $nbSousItemID)	
					{
						if ($ok)
						{
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbTypePrixID($nbTypePrixID))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbSousItemID($nbSousItemID))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbPrix(0.01))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbRabaisPour(5.00001))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbRabaisStat(0.50))
							{
								$ok = false;
							}
							if ($ok)
							{
								if (!$objSousItem->stpi_getObjPrix()->stpi_insert())
								{
									$ok = false;
								}
							}
						}
					}
				}
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbTypePrixID=" . $objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_getNbID());
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