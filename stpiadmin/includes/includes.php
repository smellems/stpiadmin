<?php

	require_once(dirname(__FILE__) . "/classes/bdd/clsbdd.php");
	require_once(dirname(__FILE__) . "/classes/lang/clstexte.php");
	
	//Nom de l'entreprise
	define("STR_NOM_ENT", "STPIAdmin");
	
	//Courriels
	define("STR_EMAIL_TO", "info@localhost");
	define("STR_EMAIL_FROM", "STPIAdmin@localhost");
	define("STR_EMAIL_PAYPAL", "paypal@localhost");
	
	//Home page
	define("STR_HOME_PAGE", "http://localhost/home.php");
	
	//Encodage par defaut
	define("STR_CHAR_ENC", "UTF-8");

	//Numéro de version STPIAdmin
	define("STR_NUM_VER", "120913");
	
	//Démarrer la session
	session_start();
	
	//Pour la langue courante
	require_once(dirname(__FILE__) . "/classes/lang/clslang.php");
	$objLang = new clslang();
	$objLang->stpi_run();
	define("LG", $objLang->stpi_getStrLg());
	unset($objLang);
	
?>
