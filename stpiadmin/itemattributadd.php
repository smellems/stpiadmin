<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsattribut.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objAttribut = new clsattribut();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjAttributLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjAttributLg[$strLg] = new clsattributlg();
		if (!$arrObjAttributLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjAttributLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjAttributLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
		{
			$ok = false;
		}
	}
	if(!$objAttribut->stpi_setNbTypeAttributID($_POST["nbTypeAttributID"]))
	{
		$ok = false;
	}
	if(!$objAttribut->stpi_setNbOrdre($_POST["nbOrdre"]))
	{
		$ok = false;
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbAttributID = $objAttribut->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjAttributLg[$strLg]->stpi_setNbAttributID($nbAttributID))
						{
							if (!$arrObjAttributLg[$strLg]->stpi_insert())
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
					print("redirect-nbAttributID=" . $objAttribut->stpi_getNbID());
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