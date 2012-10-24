<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/page/clspagepublic.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strContent = basename($_SERVER["SCRIPT_NAME"]);
	
	$objPage = new clspagepublic();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strContent);
	$objLock->stpi_run();
	
	if ($objPage->stpi_setNbID($_POST["nbPageID"]))
	{
		if ($objPage->stpi_setArrObjPageLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjPageLg = $objPage->stpi_getArrObjPageLg();
				if (!$arrObjPageLg[$strLg]->stpi_setStrLg($strLg))
				{
					$ok = false;
				}
				if (!$arrObjPageLg[$strLg]->stpi_setStrTitre($_POST["strTitre" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjPageLg[$strLg]->stpi_setStrDesc($_POST["strDesc" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjPageLg[$strLg]->stpi_setStrKeywords($_POST["strKeywords" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjPageLg[$strLg]->stpi_setStrContent($_POST["strContent" . $strLg]))
				{
					$ok = false;
				}
				if (!$arrObjPageLg[$strLg]->stpi_setNbPageID($objPage->stpi_getNbID()))
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
							if (!$arrObjPageLg[$strLg]->stpi_update())
							{
								$ok = false;
							}
						}
					}
					if ($ok)
					{
						if ($objBdd->stpi_commit())
						{
							print("redirect-nbPageID=" . $objPage->stpi_getNbID());
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
