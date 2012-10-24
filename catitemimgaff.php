<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/img/clsimg.php");
	
	$objImg = new clsimg("stpi_item_ImgCatItem", $_GET["nbCatItemID"]);
	
	$objImg->stpi_affImg();
?>