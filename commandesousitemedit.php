<?php
	require_once("./stpiadmin/includes/includes.php");	
	require_once("./stpiadmin/includes/classes/commande/clscommande.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	
	$objCommande = new clscommande();
	$objBdd = clsbdd::singleton();
	$objCommandeSession = new clscommandesession();
	$objTexte = new clstexte("./texte/checkout");
	
	$objItem =& $objCommande->stpi_getObjCommandeSousItem()->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objPrix =& $objSousItem->stpi_getObjPrix();
	
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
		if (!isset($_POST["nbSousItem" . $objCommandeSousItemSession->stpi_getNbSousItemID()]))
		{
			exit;
		}
		if (!$objSousItem->stpi_setNbID($objCommandeSousItemSession->stpi_getNbSousItemID()))
		{
			exit;
		}
		if (!$objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
		{
			exit;
		}
		
		$objCommandeSousItemNew = new clscommandesousitem();
		
		if (!$objItem->stpi_chkDisponibilite(1))
		{
			print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notavailable") . "</span><br/>\n");
			exit;
		}
		
		if (!$objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 1))
		{
			exit;
		}		
		if (!$objCommandeSousItemNew->stpi_setNbPrix($objPrix->stpi_getNbPrix()))
		{
			exit;
		}
		if (($objSousItem->stpi_getNbQte() - $_POST["nbSousItem" . $objCommandeSousItemSession->stpi_getNbSousItemID()]) < 0)
		{
			if ($objSousItem->stpi_getNbQte() == 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("outofstock") . "</span><br/>\n");							
			}
			elseif ($objSousItem->stpi_getNbQte() > 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($objSousItem->stpi_getNbQte()) . " " . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("quantityleft") . "</span><br/>\n");
			}
			exit;
		}
		if (!$objCommandeSousItemNew->stpi_setNbQte($_POST["nbSousItem" . $objCommandeSousItemSession->stpi_getNbSousItemID()]))
		{
			exit;
		}
		if (!$objCommandeSousItemNew->stpi_setNbSousItemID($objSousItem->stpi_getNbID()))
		{
			exit;
		}
		$arrObjCommandeSousItem[$objSousItem->stpi_getNbID()] = $objCommandeSousItemNew;
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