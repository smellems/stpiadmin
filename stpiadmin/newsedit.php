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
	
	if ($objNews->stpi_setNbID($_POST["nbNewsID"]))
	{
		if ($objNews->stpi_setArrObjNewsLgFromBdd())
		{
			$ok = true;
			$objLang->stpi_setArrLang();
			$arrLang = $objLang->stpi_getArrLang();
			foreach ($arrLang as $strLg)	
			{
				$arrObjNewsLg = $objNews->stpi_getArrObjNewsLg();
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
				if (!$arrObjNewsLg[$strLg]->stpi_setNbNewsID($objNews->stpi_getNbID()))
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
					if (!$objNews->stpi_update())
					{
						$ok = false;
					}
					if ($ok)
					{
						foreach ($arrLang as $strLg)	
						{
							if ($ok)
							{
								if (!$arrObjNewsLg[$strLg]->stpi_update())
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
		}
	}
?>
