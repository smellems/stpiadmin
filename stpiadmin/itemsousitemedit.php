<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clssousitem.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objSousItem = new clssousitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objSousItem->stpi_setNbID($_POST["nbSousItemID"]))
	{
		$ok = true;
		if(!$objSousItem->stpi_setNbUnits($_POST["nbUnits"]))
		{
			$ok = false;
		}
		
		if(!$objSousItem->stpi_setNbQte($_POST["nbQte"]))
		{
			$ok = false;
		}
		
		if(!$objSousItem->stpi_setNbQteMin($_POST["nbQteMin"]))
		{
			$ok = false;
		}
		
		if(!$objSousItem->stpi_setStrItemCode($_POST["strItemCode"]))
		{
			$ok = false;
		}
		
		if(!$objSousItem->stpi_setBoolTaxable($_POST["boolTaxable"]))
		{
			$ok = false;
		}
		
		if ($arrNbTypePrixID = $objSousItem->stpi_getObjPrix()->stpi_getObjTypePrix()->stpi_selAll())
		{
			$arrNbPrix = array();
			$arrNbRabaisPour = array();
			$arrNbRabaisStat = array();
			$nb = 0;
			foreach($arrNbTypePrixID as $nbTypePrixID)
			{
				if (!$objSousItem->stpi_getObjPrix()->stpi_chkNbPrix($_POST["nbPrix" . $nbTypePrixID]))
				{
					$ok = false;
				}
				if (!$objSousItem->stpi_getObjPrix()->stpi_chkNbRabaisPour($_POST["nbRabaisPour" . $nbTypePrixID]))
				{
					$ok = false;
				}
				if (!$objSousItem->stpi_getObjPrix()->stpi_chkNbRabaisStat($_POST["nbRabaisStat" . $nbTypePrixID]))
				{
					$ok = false;
				}
				if ($ok)
				{
					$arrNbPrix[$nb] = $_POST["nbPrix" . $nbTypePrixID];
					$arrNbRabaisPour[$nb] = $_POST["nbRabaisPour" . $nbTypePrixID];
					$arrNbRabaisStat[$nb++] = $_POST["nbRabaisStat" . $nbTypePrixID];
				}
				else
				{
					$ok = false;
				}
			}
		}
		else
		{
			$ok = false;
		}
		
		if ($arrNbTypeAttributID = $objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_selAll())
		{
			$arrNbAttributID = array();
			$nb = 0;
			foreach($arrNbTypeAttributID as $nbTypeAttributID)
			{
				if ($_POST["nbAttributID" . $nbTypeAttributID] != 0)
				{
					$arrNbAttributID[$nb++] = $_POST["nbAttributID" . $nbTypeAttributID];
				}
			}
			if (!$objSousItem->stpi_setArrNbAttributID($arrNbAttributID))
			{
				$ok = false;
			}
		}
		else
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objSousItem->stpi_update(false, true))
				{
					$ok = false;
				}
				if ($ok)
				{
					foreach ($arrNbTypePrixID as $nbTypePrixID)	
					{
						if ($ok)
						{
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbTypePrixID($nbTypePrixID))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbSousItemID($objSousItem->stpi_getNbID()))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbPrix($_POST["nbPrix" . $nbTypePrixID]))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbRabaisPour($_POST["nbRabaisPour" . $nbTypePrixID]))
							{
								$ok = false;
							}
							if (!$objSousItem->stpi_getObjPrix()->stpi_setNbRabaisStat($_POST["nbRabaisStat" . $nbTypePrixID]))
							{
								$ok = false;
							}
							if ($ok)
							{
								if (!$objSousItem->stpi_getObjPrix()->stpi_update())
								{
									$ok = false;
								}
							}
						}
					}
				}
				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-nbSousItemID=" . $objSousItem->stpi_getNbID());
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
?>