<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clsevent.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objEvent = new clsevent();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objEvent->stpi_setNbID($_POST["nbEventID"]))
	{
		if ($objEvent->stpi_setArrObjEventLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjEventLg = $objEvent->stpi_getArrObjEventLg();
				if (!$arrObjEventLg[$strLg]->stpi_setStrLg($strLg))
				{
					$ok = false;
				}
				if (!$arrObjEventLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjEventLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjEventLg[$strLg]->stpi_setStrLien($_POST["strLien" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjEventLg[$strLg]->stpi_setNbEventID($objEvent->stpi_getNbID()))
				{
					$ok = false;
				}
			}
			if(!$objEvent->stpi_setNbTypeEventID($_POST["nbTypeEventID"]))
			{
				$ok = false;
			}
			if(!$objEvent->stpi_setNbAdresseID($_POST["nbAdresseID"]))
			{
				$ok = false;
			}
			if ($ok)
			{
				if ($objBdd->stpi_startTransaction())
				{
					if (!$objEvent->stpi_update())
					{
						$ok = false;
					}
					if ($ok)
					{
						foreach ($arrLang as $strLg)	
						{
							if ($ok)
							{
								if (!$arrObjEventLg[$strLg]->stpi_update())
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
							print("redirect-nbEventID=" . $objEvent->stpi_getNbID());
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