<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/motd/clsmotd.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMotd = new clsmotd();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjMotdLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjMotdLg[$strLg] = new clsmotdlg();
		if (!$arrObjMotdLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjMotdLg[$strLg]->stpi_setStrMotd($_POST["strMotd" . $strLg]))
		{
			$ok = false;
		}
	}
	if(!$objMotd->stpi_setBoolRouge($_POST["rouge"]))
	{
		$ok = false;
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbMotdID = $objMotd->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjMotdLg[$strLg]->stpi_setNbMotdID($nbMotdID))
						{
							if (!$arrObjMotdLg[$strLg]->stpi_insert())
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
					print("redirect-nbMotdID=" . $objMotd->stpi_getNbID());
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