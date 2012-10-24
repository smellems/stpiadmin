<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/lien/clstypelien.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeLien = new clstypelien();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjTypeLienLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjTypeLienLg[$strLg] = new clstypelienlg();
		if (!$arrObjTypeLienLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjTypeLienLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjTypeLienLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
		{
			$ok = false;
		}
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbTypeLienID = $objTypeLien->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjTypeLienLg[$strLg]->stpi_setNbTypeLienID($nbTypeLienID))
						{
							if (!$arrObjTypeLienLg[$strLg]->stpi_insert())
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
					print("redirect-nbTypeLienID=" . $objTypeLien->stpi_getNbID());
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