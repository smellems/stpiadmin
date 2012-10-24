<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clstypeevent.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeEvent = new clstypeevent();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objTypeEvent->stpi_setNbID($_POST["nbTypeEventID"]))
	{
		if ($objTypeEvent->stpi_setArrObjTypeEventLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjTypeEventLg = $objTypeEvent->stpi_getArrObjTypeEventLg();
				if (!$arrObjTypeEventLg[$strLg]->stpi_setStrLg($strLg))
				{
					$ok = false;
				}
				if (!$arrObjTypeEventLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjTypeEventLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjTypeEventLg[$strLg]->stpi_setNbTypeEventID($objTypeEvent->stpi_getNbID()))
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
							if (!$arrObjTypeEventLg[$strLg]->stpi_update())
							{
								$ok = false;
							}
						}
					}
					if ($ok)
					{
						if ($objBdd->stpi_commit())
						{
							print("redirect-nbTypeEventID=" . $objTypeEvent->stpi_getNbID());
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