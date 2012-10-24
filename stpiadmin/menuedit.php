<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/menu/clsmenupublic.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMenuPublic = new clsmenupublic();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objMenuPublic->stpi_setNbID($_POST["nbMenuID"]))
	{
		if ($objMenuPublic->stpi_setArrObjMenuLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjMenuLg = $objMenuPublic->stpi_getArrObjMenuLg();
				if (!$arrObjMenuLg[$strLg]->stpi_setStrLg($strLg))
				{
					$ok = false;
				}
				if (!$arrObjMenuLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjMenuLg[$strLg]->stpi_setNbMenuID($objMenuPublic->stpi_getNbID()))
				{
					$ok = false;
				}
			}
			if ($ok)
			{
				if ($objBdd->stpi_startTransaction())
				{
					foreach ($arrLang as $strLg)	
					{
						if ($ok)
						{
							if (!$arrObjMenuLg[$strLg]->stpi_update())
							{
								$ok = false;
							}
						}
					}
					if ($ok)
					{
						if ($objBdd->stpi_commit())
						{
							print("redirect-nbMenuID=" . $objMenuPublic->stpi_getNbID());
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
