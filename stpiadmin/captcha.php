<?php

require_once("./includes/includes.php");
require_once("./includes/classes/img/clscaptcha.php");

$objCaptcha = new clscaptcha();

$objCaptcha->stpi_setCaptcha();
$objCaptcha->stpi_setCaptchaCookie();
$objCaptcha->stpi_CaptchaToBrowser();

unset($objCaptcha);

?>
