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
	$objRegistre =& $objCommande->stpi_getObjRegistre();
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	$objItem =& $objCommandeSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objPrix =& $objSousItem->stpi_getObjPrix();
	
	$nbAvailable = 0;
	
	if (!$objRegistre->stpi_setNbID($_GET["nbRegistreID"]))
	{
		exit;
	}
	if (!$objSousItem->stpi_setNbID($_GET["nbSousItemID"]))
	{
		exit;
	}
	if (!$objRegistreSousItem->stpi_setNbID($objRegistre->stpi_getNbID(), $objSousItem->stpi_getNbID()))
	{
		exit;
	}
	if (!$objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
	{
		exit;
	}
	
	$nbAvailable = $objSousItem->stpi_getNbQte();
	if ($nbAvailable > ($objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu()))
	{
		$nbAvailable = $objRegistreSousItem->stpi_getNbQteVoulu() - $objRegistreSousItem->stpi_getNbQteRecu();
	}
	
	$arrObjCommandeSousItem = array();
	
	if (isset($_SESSION["stpiObjCommandeRegistreSession"]))
	{
		$objCommandeSession = $objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession();
		
		if ($objCommandeSession->stpi_getNbRegistreID() != $objRegistre->stpi_getNbID())
		{
			$objCommandeSession = new clscommandesession();
		}
		
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
		if (($nbAvailable - ($arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte() + $_GET["nbQte"])) < 0)
		{
			if (($nbAvailable - $arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte()) == 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("outofstock") . "</span><br/>\n");							
			}
			elseif (($nbAvailable - $arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte()) > 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML(($nbAvailable - $arrObjCommandeSousItem[$objSousItem->stpi_getNbID()]->stpi_getNbQte())) . " " . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("quantityleft") . "</span><br/>\n");
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
		
		if (($nbAvailable - $_GET["nbQte"]) < 0)
		{
			if ($nbAvailable == 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("outofstock") . "</span><br/>\n");							
			}
			elseif ($nbAvailable > 0)
			{
				print("<span style=\"color:#FF0000;\">" . $objBdd->stpi_trsBddToHTML($nbAvailable) . " " . $objBdd->stpi_trsBddToHTML($objItem->stpi_getStrSousItemDesc()) . " " . $objTexte->stpi_getArrErrTxt("quantityleft") . "</span><br/>\n");
			}
			exit;
		}
		
		if (!$objCommandeSousItem->stpi_setNbQte($_GET["nbQte"]))
		{
			exit;
		}
		
		if (!$objItem->stpi_chkDisponibilite(2))
		{
			print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notavailable") . "</span><br/>\n");
			exit;
		}
		
		if (!$objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 2))
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
	
	$objCommandeSession->stpi_setNbRegistreID($objRegistre->stpi_getNbID());
	$objCommandeSession->stpi_setNbSousTotal($objCommande->stpi_getNbSousTotalFromArrObjCommandeSousItem());
	$objCommandeSession->stpi_setNbSousItemQte($objCommande->stpi_getNbQteFromArrObjCommandeSousItem());
	$objCommandeSession->stpi_setNbPrixRabais($objCommande->stpi_getNbPrixRabaisFromArrObjCommandeSousItem());
	
	$objCommandeSession->stpi_setObjCommandeRegistreSessionToSession();
		
	$objBody->stpi_affCartUrl();

?>