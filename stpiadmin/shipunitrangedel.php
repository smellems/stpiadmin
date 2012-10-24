<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/ship/clsunitrange.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objUnitRange= new clsunitrange();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$objBdd = clsbdd::singleton();
	
	if (!$objUnitRange->stpi_setNbUnitRangeID($_GET["nbUnitRangeID"]))
	{
		exit;	
	}
	
	if ($_GET["nbConfirmed"] == 1)
	{
		if (!$objBdd->stpi_startTransaction())
		{
			exit;
		}
		if (!$objUnitRange->stpi_deleteUnitRangeID($objUnitRange->stpi_getNbUnitRangeID()))
		{
			$objBdd->stpi_rollback();
			exit;
		}
		if (!$objBdd->stpi_commit())
		{
			$objBdd->stpi_rollback();
			exit;
		}
		else
		{
			print("redirect");
		}
	}
	else
	{
		$objUnitRange->stpi_affDelete();
	}
?>