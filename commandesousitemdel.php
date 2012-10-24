<?php
	require_once("./stpiadmin/includes/includes.php");	
	require_once("./stpiadmin/includes/classes/commande/clscommande.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	
	$objCommande = new clscommande();
	$objCommandeSession = new clscommandesession();
	$objTexte = new clstexte("./texte/checkout");
	
	
	if (!isset($_GET["nbSousItemID"]))
	{
		exit;
	}
	
	if (!isset($_SESSION["stpiObjCommandeSession"]))
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
	}
	
	if (!$objCommandeSession = $objCommandeSession->stpi_getObjCommandeSessionFromSession())
	{
		print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("emptycart") . "</span><br/>\n");
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
		unset($_SESSION["stpiObjCommandeSession"]);
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
	
	if ($objCommandeSession->stpi_setObjCommandeSessionToSession())
	{
		print("redirect");
	}
?>