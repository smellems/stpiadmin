<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsattribut.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objAttribut = new clsattribut();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objAttribut->stpi_getObjTypeAttribut()->stpi_setNbID($_GET["nbTypeAttributID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($arrNbAttributID = $objAttribut->stpi_getObjTypeAttribut()->stpi_selNbAttributID())
			{
				$ok = false;
				$objAttribut->stpi_getObjTypeAttribut()->stpi_affNoDelete();
				foreach ($arrNbAttributID as $nbAttributID)
				{
					if ($objAttribut->stpi_setNbID($nbAttributID))
					{
						if ($objAttribut->stpi_setObjAttributLgFromBdd())
						{
							print("<a href=\"./itemattribut.php?l=" . LG);
							print("&amp;nbAttributID=" . $objBdd->stpi_trsBddToHTML($nbAttributID) . "\">");
							print($objBdd->stpi_trsBddToHTML($objAttribut->stpi_getObjAttributLg()->stpi_getStrName()) . "</a><br/>\n");
						}
					}
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if ($ok)
				{
					if (!$objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_deleteTypeAttributId($_GET["nbTypeAttributID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objAttribut->stpi_getObjTypeAttribut()->stpi_delete($_GET["nbTypeAttributID"]))
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
		$objAttribut->stpi_getObjTypeAttribut()->stpi_affDelete();
	}
?>