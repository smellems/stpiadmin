<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/lien/clslien.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objLien = new clslien();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objLien->stpi_getObjTypeLien()->stpi_setNbID($_GET["nbTypeLienID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objLien->stpi_getObjTypeLien()->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{	
			if ($arrNbLienID = $objLien->stpi_getObjTypeLien()->stpi_selNbLienID())
			{
				$ok = false;
				$objLien->stpi_getObjTypeLien()->stpi_affNoDelete();
				foreach ($arrNbLienID as $nbLienID)
				{
					if ($objLien->stpi_setNbID($nbLienID))
					{
						if ($objLien->stpi_setObjLienLgFromBdd())
						{
							print("<a href=\"./lien.php?l=" . LG);
							print("&amp;nbLienID=" . $objBdd->stpi_trsBddToHTML($nbLienID) . "\">");
							print($objBdd->stpi_trsBddToHTML($objLien->stpi_getObjLienLg()->stpi_getStrName()) . "</a> - " . $objBdd->stpi_trsBddToHTML($objLien->stpi_getObjLienLg()->stpi_getStrLien()) . "<br/>\n");
						}
					}
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objLien->stpi_getObjTypeLien()->stpi_getObjTypeLienLg()->stpi_deleteTypeLienId($_GET["nbTypeLienID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objLien->stpi_getObjTypeLien()->stpi_delete($_GET["nbTypeLienID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-");
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
	else
	{
		$objLien->stpi_getObjTypeLien()->stpi_affDelete();
	}
?>
