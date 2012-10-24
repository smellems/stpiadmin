<?php
	require_once("./stpiadmin/includes/includes.php");	
	require_once("./stpiadmin/includes/classes/content/clsbody.php");
	require_once("./stpiadmin/includes/classes/commande/clscommande.php");
	require_once("./stpiadmin/includes/classes/commande/clscommandesession.php");
	
	$objBody = new clsbody();
	$objBdd = clsbdd::singleton();
	$objCommande = new clscommande();
	$objCommandeSession = new clscommandesession();
	$objTexte = new clstexte("./texte/checkout");
	
	$objCommandeSousItem =& $objCommande->stpi_getObjCommandeSousItem();
	$objItem =& $objCommandeSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objPrix =& $objSousItem->stpi_getObjPrix();
	

	if (!$objSousItem->stpi_setNbID($_GET["nbSousItemID"]))
	{
		exit;
	}
	if (!$objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
	{
		exit;
	}
	
	$arrObjCommandeSousItem = array();
	
	if (isset($_SESSION["stpiObjCommandeSession"]))
	{
		$objCommandeSession = $objCommandeSession->stpi_getObjCommandeSessionFromSession();
		
		$arrObjCommandeSousItemSession = $objCommandeSession->stpi_getArrObjCommandeSousItemSession();
		
		foreach ($arrObjCommandeSousItemSession as $objCommandeSousItemSession)
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
	
	
	if (array_key_exists($objSousItem->stpi_getNbID(), $arrObjCommandeSousItem))
	{
		if (($objSousItem->stpi_getNbQte() - ($arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte() + $_GET["nbQte"])) < 0)
		{
			if (($objSousItem->stpi_getNbQte() - $arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte()) == 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("outofstock") . "</span><br/>\n");							
			}
			elseif (($objSousItem->stpi_getNbQte() - $arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte()) > 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML(($objSousItem->stpi_getNbQte() - $arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte())) . " " . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("quantityleft") . "</span><br/>\n");
			}
			exit;
		}
		if (!$arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_setNbQte($arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte() + $_GET["nbQte"]))
		{
			exit;
		}		
	}
	else
	{
		if (!$objCommandeSousItem->stpi_setNbSousItemID($objSousItem->stpi_getNbID()))
		{
			exit;
		}
		
		if (($objSousItem->stpi_getNbQte() - $_GET["nbQte"]) < 0)
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
		
		if (!$objCommandeSousItem->stpi_setNbQte($_GET["nbQte"]))
		{
			exit;
		}
		
		if (!$objItem->stpi_chkDisponibilite(1))
		{
			print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notavailable") . "</span><br/>\n");
			exit;
		}
		
		if (!$objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 1))
		{
			exit;
		}
		
		if (!$objCommandeSousItem->stpi_setNbPrix($objPrix->stpi_getNbPrix()))
		{
			exit;
		}
		
		$arrObjCommandeSousItem[$objSousItem->stpi_getNbID()] = $objCommandeSousItem;
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
	
	$objCommandeSession->stpi_setObjCommandeSessionToSession();
		
	$objBody->stpi_affCartUrl();

?>