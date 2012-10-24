<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/event/clseventdateheure.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objDateHeure = new clseventdateheure();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;

	if(!$objDateHeure->stpi_setNbID($_POST["nbDateHeureID"]))
	{
		$ok = false;
	}
	
	if(!$objDateHeure->stpi_setDtDebut($_POST["dtDebut"]))
	{
		$ok = false;
	}
	
	if(!$objDateHeure->stpi_setDtFin($_POST["dtFin"]))
	{
		$ok = false;
	}
	
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$objDateHeure->stpi_update())
			{
				$ok = false;
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
?>