<?php
	require_once("./stpiadmin/includes/includes.php");	
	require_once("./stpiadmin/includes/classes/commande/clscommande.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	
	$objCommande = new clscommande();
	$objCommandeSession = new clscommandesession();
	$objTexte = new clstexte("./texte/checkout");
	
	$objRegistre =& $objCommande->stpi_getObjRegistre();
	
	
	if (!isset($_GET["nbSousItemID"]))
	{
		exit;
	}
	
	if (!isset($_SESSION["stpiObjCommandeRegistreSession"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (!$objCommandeSession = $objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession())
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
		exit;
	}
	
	if (!$objRegistre->stpi_setNbID($objCommandeSession->stpi_getNbRegistreID()))
	{
		exit;
	}
	
	$arrObjCommandeSousItem = array();
		
	$arrObjCommandeSousItemSession = $objCommandeSession->stpi_getArrObjCommandeSousItemSession();
		
	foreach ($arrObjCommandeSousItemSession as $objCommandeSousItemSession)
	{
		if ($_GET["nbSousItemID"] != $objCommandeSousItemSession->stpi_getNbSousItemID())
		{
			$objCommandeSousItemNew = new clscommandesousitem();
			if (!$objCommandeSousItemNew->stpi_setNbPrix($objCommandeSousItemSession->stpi_getNbPrix()))
			{
				exit;
			}
			if (!$objCommandeSousItemNew->stpi_setNbQte($objCommandeSousItemSession->stpi_getNbQte()))
			{
				exit;
			}
			if (!$objCommandeSousItemNew->stpi_setNbSousItemID($objCommandeSousItemSession->stpi_getNbSousItemID()))
			{
				exit;
			}
			$arrObjCommandeSousItem[$objCommandeSousItemSession->stpi_getNbSousItemID()] = $objCommandeSousItemNew;
		}
	}
	
	if (empty($arrObjCommandeSousItem))
	{
		unset($_SESSION["stpiObjCommandeRegistreSession"]);
		print("redirect");
		exit;
	}
			
	$objCommande->stpi_setArrObjCommandeSousItem($arrObjCommandeSousItem);
	
	$arrObjCommandeSousItemSession = array();
	
	foreach ($arrObjCommandeSousItem as $objCommandeSousItem)
	{
		$objCommandeSousItemSessionNew = new clscommandesousitemsession();
		$objCommandeSousItemSessionNew->stpi_setNbPrix($objCommandeSousItem->stpi_getNbPrix());
		$objCommandeSousItemSessionNew->stpi_setNbQte($objCommandeSousItem->stpi_getNbQte());
		$objCommandeSousItemSessionNew->stpi_setNbSousItemID($objCommandeSousItem->stpi_getNbSousItemID());
		$arrObjCommandeSousItemSession[$objCommandeSousItem->stpi_getNbSousItemID()] = $objCommandeSousItemSessionNew;
	}
	
	$objCommandeSession->stpi_setArrObjCommandeSousItemSession($arrObjCommandeSousItemSession);
		
	$objCommandeSession->stpi_setNbSousTotal($objCommande->stpi_getNbSousTotalFromArrObjCommandeSousItem());
	$objCommandeSession->stpi_setNbSousItemQte($objCommande->stpi_getNbQteFromArrObjCommandeSousItem());
	$objCommandeSession->stpi_setNbPrixRabais($objCommande->stpi_getNbPrixRabaisFromArrObjCommandeSousItem());
	
	if ($objCommandeSession->stpi_setObjCommandeRegistreSessionToSession())
	{
		print("redirect");
	}
?>