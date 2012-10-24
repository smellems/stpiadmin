<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/img/clsimg.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($objImg = new clsimg("stpi_item_ImgCatItem", $_GET["nbImageID"]))
	{
		$objImg->stpi_affImg();
	}
?>