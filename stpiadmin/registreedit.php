<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/registre/clsregistre.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objRegistre = new clsregistre();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objRegistre->stpi_setNbID($_POST["nbRegistreID"]))
	{
		$ok = true;
		if(!$objRegistre->stpi_setStrMessage($_POST["strMessage"]))
		{
			$ok = false;
		}
		
		if(!$objRegistre->stpi_setStrLangID($_POST["strLangID"]))
		{
			$ok = false;
		}
		
		if(!$objRegistre->stpi_setDtFin($_POST["dtFinAnnee"] . "-" . $_POST["dtFinMois"] . "-" . $_POST["dtFinJour"]))
		{
			$ok = false;
		}

		if(!$objRegistre->stpi_setBoolActif($_POST["boolActif"]))
		{
			$ok = false;
		}

		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objRegistre->stpi_update())
				{
					$ok = false;
				}

				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-nbRegistreID=" . $objRegistre->stpi_getNbID());
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
