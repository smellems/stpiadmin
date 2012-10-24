<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/news/clsnews.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objNews = new clsnews();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjNewsLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjNewsLg[$strLg] = new clsnewslg();
		if (!$arrObjNewsLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjNewsLg[$strLg]->stpi_setStrTitre($_POST["strTitre" . $strLg]))
		{
			$ok = false;
		}
		if (!$arrObjNewsLg[$strLg]->stpi_setStrNews($_POST["strNews" . $strLg]))
		{
			$ok = false;
		}
	}
	if(!$objNews->stpi_setNbTypeNewsID($_POST["nbTypeNewsID"]))
	{
		$ok = false;
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbNewsID = $objNews->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjNewsLg[$strLg]->stpi_setNbNewsID($nbNewsID))
						{
							if (!$arrObjNewsLg[$strLg]->stpi_insert())
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
					print("redirect-nbNewsID=" . $objNews->stpi_getNbID());
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
