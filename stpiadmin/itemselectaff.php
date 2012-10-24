<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsitem.php");
	
	$objItem = new clsitem();
	
	if (!$objItem->stpi_getObjTypeItem()->stpi_setNbID($_GET["nbTypeItemID"]))
	{
		exit;
	}
	
	$objItem->stpi_affSelectItem();
?>