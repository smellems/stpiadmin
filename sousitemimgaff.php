<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/img/clsimg.php");
	
	$objImg = new clsimg("stpi_item_ImgSousItem", $_GET["nbImageID"]);
	
	$objImg->stpi_affImg();
?>