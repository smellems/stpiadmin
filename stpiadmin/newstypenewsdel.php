<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/news/clsnews.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNews = new clsnews();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objNews->stpi_getObjTypeNews()->stpi_setNbID($_GET["nbTypeNewsID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objNews->stpi_getObjTypeNews()->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{	
			if ($arrNbNewsID = $objNews->stpi_getObjTypeNews()->stpi_selNbNewsID())
			{
				$ok = false;
				$objNews->stpi_getObjTypeNews()->stpi_affNoDelete();
				foreach ($arrNbNewsID as $nbNewsID)
				{
					if ($objNews->stpi_setNbID($nbNewsID))
					{
						if ($objNews->stpi_setObjNewsLgFromBdd())
						{
							print("<a href=\"./news.php?l=" . LG);
							print("&amp;nbNewsID=" . $objBdd->stpi_trsBddToHTML($nbNewsID) . "\">");
							print($objBdd->stpi_trsBddToHTML($objNews->stpi_getObjNewsLg()->stpi_getStrName()) . "</a> - " . $objBdd->stpi_trsBddToHTML($objNews->stpi_getObjNewsLg()->stpi_getStrNews()) . "<br/>\n");
						}
					}
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objNews->stpi_getObjTypeNews()->stpi_getObjTypeNewsLg()->stpi_deleteTypeNewsId($_GET["nbTypeNewsID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objNews->stpi_getObjTypeNews()->stpi_delete($_GET["nbTypeNewsID"]))
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
		$objNews->stpi_getObjTypeNews()->stpi_affDelete();
	}
?>
