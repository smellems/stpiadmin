<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objItem = new clsitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objItem->stpi_setNbID($_POST["nbItemID"]))
	{
		if ($objItem->stpi_setArrObjItemLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjItemLg = $objItem->stpi_getArrObjItemLg();
				if (!$arrObjItemLg[$strLg]->stpi_setStrLg($strLg))
				{
					$ok = false;
				}
				if (!$arrObjItemLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjItemLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjItemLg[$strLg]->stpi_setNbItemID($objItem->stpi_getNbID()))
				{
					$ok = false;
				}
			}
			if(!$objItem->stpi_setNbTypeItemID($_POST["nbTypeItemID"]))
			{
				$ok = false;
			}
			if (!$arrNbCatItemID = $objItem->stpi_getObjCatItem()->stpi_selAll())
			{
				$ok = false;
			}
			if ($ok)
			{
				$arrNbCatItemIDTrue = array();
				$nb = 0;
				foreach($arrNbCatItemID as $nbCatItemID)
				{
					if ($objItem->stpi_getObjCatItem()->stpi_setNbID($nbCatItemID))
					{
						if ($_POST["nbCat" . $nbCatItemID] == "1")
						{
							$arrNbCatItemIDTrue[$nb++] = $nbCatItemID;
						}
					}
				}
				if (!$objItem->stpi_setArrNbCatItemID($arrNbCatItemIDTrue))
				{
					$ok = false;
				}
			}
			if (!$arrNbDispItemID = $objItem->stpi_getObjDispItem()->stpi_selAll())
			{
				$ok = false;
			}
			if ($ok)
			{
				$arrNbDispItemIDTrue = array();
				$nb = 0;
				foreach($arrNbDispItemID as $nbDispItemID)
				{
					if ($objItem->stpi_getObjDispItem()->stpi_setNbID($nbDispItemID))
					{
						if ($_POST["nbDisp" . $nbDispItemID] == "1")
						{
							$arrNbDispItemIDTrue[$nb++] = $nbDispItemID;
						}
					}
				}
				if (!$objItem->stpi_setArrNbDispItemID($arrNbDispItemIDTrue))
				{
					$ok = false;
				}
			}
			if ($ok)
			{
				if ($objBdd->stpi_startTransaction())
				{
					if (!$objItem->stpi_update())
					{
						$ok = false;
					}
					if ($ok)
					{
						foreach ($arrLang as $strLg)	
						{
							if ($ok)
							{
								if (!$arrObjItemLg[$strLg]->stpi_update())
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
							print("redirect-nbItemID=" . $objItem->stpi_getNbID());
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