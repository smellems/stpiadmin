<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/img/clsimg.php");
	
	$objImg = new clsimg("stpi_event_ImgEvent", $_GET["nbImageID"]);
	
	$objImg->stpi_affImg();

?>