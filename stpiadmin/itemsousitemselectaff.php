<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsitem.php");
	
	$objItem = new clsitem();
	
	if (!$objItem->stpi_setNbID($_GET["nbItemID"]))
	{
		exit;
	}
	
	$objItem->stpi_affSelectSousItem();
?>