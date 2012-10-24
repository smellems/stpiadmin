<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/lien/clslien.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objLien = new clslien();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjLienLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjLienLg[$strLg] = new clslienlg();
		if (!$arrObjLienLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjLienLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjLienLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjLienLg[$strLg]->stpi_setStrLien($_POST["strLien" . $strLg]))
		{
			$ok = false;
		}
	}
	if(!$objLien->stpi_setNbTypeLienID($_POST["nbTypeLienID"]))
	{
		$ok = false;
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbLienID = $objLien->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjLienLg[$strLg]->stpi_setNbLienID($nbLienID))
						{
							if (!$arrObjLienLg[$strLg]->stpi_insert())
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
					print("redirect-nbLienID=" . $objLien->stpi_getNbID());
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