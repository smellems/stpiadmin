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
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjItemLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjItemLg[$strLg] = new clsitemlg();
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
	}
	if(!$objItem->stpi_setNbTypeItemID($_POST["nbTypeItemID"]))
	{
		$ok = false;
	}
	if ($arrNbCatItemID = $objItem->stpi_getObjCatItem()->stpi_selAll())
	{
		$arrNbCatItemIDTrue = array();
		$nb = 0;
		foreach($arrNbCatItemID as $nbCatItemID)
		{
			if ($objItem->stpi_getObjCatItem()->stpi_chkNbID($nbCatItemID))
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
	if ($arrNbDispItemID = $objItem->stpi_getObjDispItem()->stpi_selAll())
	{
		$arrNbDispItemIDTrue = array();
		$nb = 0;
		foreach($arrNbDispItemID as $nbDispItemID)
		{
			if ($objItem->stpi_getObjDispItem()->stpi_chkNbID($nbDispItemID))
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
			if (!$nbItemID = $objItem->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjItemLg[$strLg]->stpi_setNbItemID($nbItemID))
						{
							if (!$arrObjItemLg[$strLg]->stpi_insert())
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
?>