<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clsmethodpaye.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMethodPaye = new clsmethodpaye();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objMethodPaye->stpi_setNbID($_POST["nbMethodPayeID"]))
	{
		if ($objMethodPaye->stpi_setArrObjMethodPayeLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjMethodPayeLg = $objMethodPaye->stpi_getArrObjMethodPayeLg();
				if (!$arrObjMethodPayeLg[$strLg]->stpi_setStrLg($strLg))
				{
					$ok = false;
				}
				if (!$arrObjMethodPayeLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjMethodPayeLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjMethodPayeLg[$strLg]->stpi_setNbMethodPayeID($objMethodPaye->stpi_getNbID()))
				{
					$ok = false;
				}
			}
			if(!$objMethodPaye->stpi_setBoolCarte($_POST["boolCarte"]))
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_startTransaction())
				{
					if (!$objMethodPaye->stpi_update())
					{
						$ok = false;
					}
					if ($ok)
					{
						foreach ($arrLang as $strLg)	
						{
							if ($ok)
							{
								if (!$arrObjMethodPayeLg[$strLg]->stpi_update())
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
							print("redirect-nbMethodPayeID=" . $objMethodPaye->stpi_getNbID());
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