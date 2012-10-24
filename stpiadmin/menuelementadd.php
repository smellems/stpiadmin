<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/menu/clsmenuelement.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMenuElement = new clsmenuelement();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjMenuElementLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjMenuElementLg[$strLg] = new clsmenuelementlg();
		if (!$arrObjMenuElementLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjMenuElementLg[$strLg]->stpi_setStrText($_POST["strText" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjMenuElementLg[$strLg]->stpi_setStrLien($_POST["strLien" . $strLg]))
		{
			$ok = false;
		}
	}

	if(!$objMenuElement->stpi_setNbMenuID($_POST["nbMenuID"]))
	{
		$ok = false;
	}

	if(!$objMenuElement->stpi_setNbParentID($_POST["nbParentID"]))
	{
		$ok = false;
	}

	if(!$objMenuElement->stpi_setNbOrdre($_POST["nbOrdre"]))
	{
		$ok = false;
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbMenuElementID = $objMenuElement->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjMenuElementLg[$strLg]->stpi_setNbMenuElementID($nbMenuElementID))
						{
							if (!$arrObjMenuElementLg[$strLg]->stpi_insert())
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
					print("redirect-nbMenuElementID=" . $objMenuElement->stpi_getNbID());
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
