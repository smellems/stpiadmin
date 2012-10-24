<?php
require_once(dirname(__FILE__) . "/../client/clsclient.php");
require_once(dirname(__FILE__) . "/../date/clsdate.php");
require_once(dirname(__FILE__) . "/../email/clsemail.php");
require_once(dirname(__FILE__) . "/../registre/clsregistre.php");
require_once(dirname(__FILE__) . "/clstypecommande.php");
require_once(dirname(__FILE__) . "/clsstatutcommande.php");
require_once(dirname(__FILE__) . "/clsmethodpaye.php");
require_once(dirname(__FILE__) . "/clsadresse.php");
require_once(dirname(__FILE__) . "/clsinfocarte.php");
require_once(dirname(__FILE__) . "/clscommandesousitem.php");
class clscommande
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objClient;
	private $objDate;
	private $objEmail;
	private $objTypeCommande;
	private $objStatutCommande;
	private $objMethodPaye;
	private $objAdresse;
	private $objInfoCarte;
	private $objRegistre;
	private $objCommandeSousItem;
	private $objCommandeSession;
	
	private $nbID;
	private $nbTypeCommandeID;
	private $nbClientID;
	private $nbStatutCommandeID;
	private $nbMethodPayeID;
	private $nbInfoCarteID;
	private $nbRegistreID;
	private $strTel;
	private $strCourriel;
	private $nbPrixShipping;
	private $nbPrixRabais;
	private $nbPrixTaxes;
	private $strMessage;
	private $strLangID;
	private $strCodeSuivi;
	private $dtEntryDate;
	private $dtShipped;
	private $dtArrived;
	
	private $arrObjCommandeSousItem;
	private $arrObjAdresse;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcommande");
		$this->objLang = new clslang();
		$this->objClient = new clsclient();
		$this->objDate = new clsdate();
		$this->objEmail = new clsemail();
		$this->objTypeCommande = new clstypecommande();
		$this->objStatutCommande = new clsstatutcommande();
		$this->objMethodPaye = new clsmethodpaye();
		$this->objAdresse = new clsadresse();
		$this->objInfoCarte = new clsinfocarte();
		$this->objRegistre = new clsregistre();
		$this->objCommandeSousItem = new clscommandesousitem();
		$this->arrObjCommandeSousItem = array();
		$this->arrObjAdresse = array();
		if ($nnbID == 0)
		{
			$this->nbTypeCommandeID = 0;
			$this->nbClientID = 0;
			$this->nbStatutCommandeID = 0;
			$this->nbMethodPayeID = 0;
			$this->nbInfoCarteID = 0;
			$this->nbRegistreID = 0;
			$this->strTel = "";
			$this->strCourriel = "";
			$this->nbPrixShipping = 0.00;
			$this->nbPrixRabais = 0.00;
			$this->nbPrixTaxes = 0.00;
			$this->strMessage = "";
			$this->strLangID = "";
			$this->strCodeSuivi = "";
			$this->dtEntryDate = "";
			$this->dtShipped = "";
			$this->dtArrived = "";
		}
		else
		{
			if(!$this->stpi_setNbID($nnbID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkNbID($nnbID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbCommandeID", "stpi_commande_Commande"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrTel($nstrTel)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrTel))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtel") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkNbPrixShipping($nnbPrixShipping)
	{
		if (!is_numeric($nnbPrixShipping))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprixshipping") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbPrixShipping < 0 OR $nnbPrixShipping > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprixshipping") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbPrixRabais($nnbPrixRabais)
	{
		if (!is_numeric($nnbPrixRabais))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprixrabais") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbPrixRabais < 0 OR $nnbPrixRabais > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprixrabais") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbPrixTaxes($nnbPrixTaxes)
	{
		if (!is_numeric($nnbPrixTaxes))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprixtaxes") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbPrixTaxes < 0 OR $nnbPrixTaxes > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprixtaxes") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrMessage($nstrMessage)
	{
		if ($nstrMessage != "" AND !$this->objBdd->stpi_chkInputToBdd($nstrMessage))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmessage") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	
	public function stpi_chkStrCodeSuivi($nstrCodeSuivi)
	{
		if ($nstrCodeSuivi != "" AND !$this->objBdd->stpi_chkInputToBdd($nstrCodeSuivi))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcodesuivi") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrLangID($nstrLangID)
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		if (!$this->objBdd->stpi_chkInputToBdd($nstrLangID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
			return false;				
		}
		if (!in_array($nstrLangID, $arrLang))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkdtShipped($ndtShipped)
	{
		if ($ndtShipped != "" AND !$this->objDate->stpi_chkDateISO($ndtShipped))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddateshipped") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkdtArrived($ndtArrived)
	{
		if ($ndtArrived != "" AND !$this->objDate->stpi_chkDateISO($ndtArrived))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddatearrived") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_setNbID($nnbID)
	{
		if (!$this->stpi_chkNbID($nnbID))
		{
			return false;				
		}
		$this->nbID = $nnbID;
		
		$SQL = "SELECT nbTypeCommandeID, nbClientID, nbStatutCommandeID, nbMethodPayeID, nbInfoCarteID, nbRegistreID, strTel, strCourriel, nbPrixShipping, nbPrixRabais, nbPrixTaxes, strMessage, strLg, strCodeSuivi, dtEntryDate, dtShipped, dtArrived";
		$SQL .= " FROM stpi_commande_Commande WHERE nbCommandeID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeCommandeID = $row["nbTypeCommandeID"];
				$this->nbClientID = $row["nbClientID"];
				$this->nbStatutCommandeID = $row["nbStatutCommandeID"];
				$this->nbMethodPayeID = $row["nbMethodPayeID"];
				$this->nbInfoCarteID = $row["nbInfoCarteID"];
				$this->nbRegistreID = $row["nbRegistreID"];
				$this->strTel = $row["strTel"];
				$this->strCourriel = $row["strCourriel"];
				$this->nbPrixShipping = $row["nbPrixShipping"];
				$this->nbPrixRabais = $row["nbPrixRabais"];
				$this->nbPrixTaxes = $row["nbPrixTaxes"];
				$this->strMessage = $row["strMessage"];
				$this->strLangID = $row["strLg"];
				$this->strCodeSuivi = $row["strCodeSuivi"];
				$this->dtShipped = $row["dtShipped"];
				$this->dtArrived = $row["dtArrived"];
				$this->dtEntryDate = $row["dtEntryDate"];
			}
			else
			{
				return false;
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return true;
	}
	
	public function stpi_setNbTypeCommandeID($nnbTypeCommandeID)
	{
		if (!$this->objTypeCommande->stpi_chkNbID($nnbTypeCommandeID))
		{
			return false;				
		}
		$this->nbTypeCommandeID = $nnbTypeCommandeID;
		return true;
	}
	
	public function stpi_setNbClientID($nnbClientID)
	{
		if ($nnbClientID != 0 AND !$this->objClient->stpi_chkNbID($nnbClientID))
		{
			return false;				
		}
		$this->nbClientID = $nnbClientID;
		return true;
	}
	
	public function stpi_setNbStatutCommandeID($nnbStatutCommandeID)
	{
		if (!$this->objStatutCommande->stpi_chkNbID($nnbStatutCommandeID))
		{
			return false;				
		}
		$this->nbStatutCommandeID = $nnbStatutCommandeID;
		return true;
	}
	
	public function stpi_setNbMethodPayeID($nnbMethodPayeID)
	{
		if (!$this->objMethodPaye->stpi_chkNbID($nnbMethodPayeID))
		{
			return false;				
		}
		$this->nbMethodPayeID = $nnbMethodPayeID;
		return true;
	}
	
	public function stpi_setNbInfoCarteID($nnbInfoCarteID)
	{
		if ($nnbInfoCarteID != 0 AND !$this->objInfoCarte->stpi_chkNbID($nnbInfoCarteID))
		{
			return false;				
		}
		$this->nbInfoCarteID = $nnbInfoCarteID;
		return true;
	}
	
	public function stpi_setNbRegistreID($nnbRegistreID)
	{
		if ($nnbRegistreID != 0 AND !$this->objRegistre->stpi_chkNbID($nnbRegistreID))
		{
			return false;				
		}
		$this->nbRegistreID = $nnbRegistreID;
		return true;
	}
	
	public function stpi_setStrTel($nstrTel)
	{
		$nstrTel = $this->objBdd->stpi_trsTelToBdd($nstrTel);
		if (!$this->stpi_chkStrTel($nstrTel))
		{
			return false;				
		}
		$this->strTel = $nstrTel;
		return true;
	}

	public function stpi_setStrCourriel($nstrCourriel)
	{
		if (!$this->objEmail->stpi_chkStrEmail($nstrCourriel))
		{
			return false;
		}
		$this->strCourriel = $nstrCourriel;
		return true;
	}
	
	public function stpi_setNbPrixShipping($nnbPrixShipping)
	{
		if (!$this->stpi_chkNbPrixShipping($nnbPrixShipping))
		{
			return false;				
		}
		$this->nbPrixShipping = $nnbPrixShipping;
		return true;
	}
	
	public function stpi_setNbPrixRabais($nnbPrixRabais)
	{
		if (!$this->stpi_chkNbPrixRabais($nnbPrixRabais))
		{
			return false;				
		}
		$this->nbPrixRabais = $nnbPrixRabais;
		return true;
	}
	
	public function stpi_setNbPrixTaxes($nnbPrixTaxes)
	{
		if (!$this->stpi_chkNbPrixTaxes($nnbPrixTaxes))
		{
			return false;				
		}
		$this->nbPrixTaxes = $nnbPrixTaxes;
		return true;
	}
	
	public function stpi_setStrCodeSuivi($nstrCodeSuivi)
	{
		if (!$this->stpi_chkStrCodeSuivi($nstrCodeSuivi))
		{
			return false;				
		}
		$this->strCodeSuivi = $nstrCodeSuivi;
		return true;
	}
	
	public function stpi_setStrMessage($nstrMessage)
	{
		if (!$this->stpi_chkStrMessage($nstrMessage))
		{
			return false;				
		}
		$this->strMessage = $nstrMessage;
		return true;
	}
	
	public function stpi_setStrLangID($nstrLangID)
	{
		if (!$this->stpi_chkStrLangID($nstrLangID))
		{
			return false;				
		}
		$this->strLangID = $nstrLangID;
		return true;
	}
	
	public function stpi_setDtShipped($ndtShipped)
	{
		if (!$this->stpi_chkDtShipped($ndtShipped))
		{
			return false;				
		}
		$this->dtShipped = $ndtShipped;
		return true;
	}
	
	public function stpi_setDtArrived($ndtArrived)
	{
		if (!$this->stpi_chkDtArrived($ndtArrived))
		{
			return false;				
		}
		$this->dtArrived = $ndtArrived;
		return true;
	}

	
	public function stpi_setArrObjCommandeSousItem($narrObjCommandeSousItem)
	{
		$this->arrObjCommandeSousItem = $narrObjCommandeSousItem;
	}
	
	
	public function stpi_setArrObjAdresse($narrObjAdresse)
	{
		$this->arrObjAdresse = $narrObjAdresse;
	}

	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbTypeCommandeID()
	{
		return $this->nbTypeCommandeID;
	}
	
	public function stpi_getNbClientID()
	{
		return $this->nbClientID;
	}
	public function stpi_getNbStatutCommandeID()
	{
		return $this->nbStatutCommandeID;
	}
	public function stpi_getNbMethodPayeID()
	{
		return $this->nbMethodPayeID;
	}
	public function stpi_getNbInfoCarteID()
	{
		return $this->nbInfoCarteID;
	}
	public function stpi_getStrTel()
	{
		return $this->objBdd->stpi_trsBddToTel($this->strTel);
	}
	public function stpi_getStrCourriel()
	{
		return $this->strCourriel;
	}
	public function stpi_getNbPrixShipping()
	{
		return $this->nbPrixShipping;
	}
	public function stpi_getNbPrixRabais()
	{
		return $this->nbPrixRabais;
	}
	public function stpi_getNbPrixTaxes()
	{
		return $this->nbPrixTaxes;
	}
	public function stpi_getStrMessage()
	{
		return $this->strMessage;
	}
	public function stpi_getStrLangID()
	{
		return $this->strLangID;
	}
	public function stpi_getStrCodeSuivi()
	{
		return $this->strCodeSuivi;
	}
	public function stpi_getDtEntryDate()
	{
		return $this->dtEntryDate;
	}
	public function stpi_getDtShipped()
	{
		return $this->dtShipped;
	}
	public function stpi_getDtArrived()
	{
		return $this->dtArrived;
	}
	
	
	public function stpi_getObjClient()
	{
		return $this->objClient;
	}
	public function stpi_getObjDate()
	{
		return $this->objDate;
	}
	public function stpi_getObjEmail()
	{
		return $this->objEmail;
	}
	public function stpi_getObjTypeCommande()
	{
		return $this->objTypeCommande;
	}
	public function stpi_getObjStatutCommande()
	{
		return $this->objStatutCommande;
	}
	public function stpi_getObjMethodPaye()
	{
		return $this->objMethodPaye;
	}
	public function stpi_getObjAdresse()
	{
		return $this->objAdresse;
	}
	public function stpi_getObjInfoCarte()
	{
		return $this->objInfoCarte;
	}
	public function stpi_getObjRegistre()
	{
		return $this->objRegistre;
	}
	public function stpi_getObjCommandeSousItem()
	{
		return $this->objCommandeSousItem;
	}
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	public function stpi_getObjBdd()
	{
		return $this->objBdd;
	}
	
	public function stpi_getNbSousTotal()
	{
		if (!$nbSousTotal = $this->stpi_selNbSousTotal())
		{
			$nbSousTotal = 0;
		}
		return $nbSousTotal;
	}
	
	
	public function stpi_getNbSousTotalFromArrObjCommandeSousItem()
	{
		$nbSousTotal = 0;
		if (empty($this->arrObjCommandeSousItem))
		{
			return $nbSousTotal;
		}
		foreach ($this->arrObjCommandeSousItem as $objCommandeSousItem)
		{
			$nbSousTotal += $objCommandeSousItem->stpi_getNbPrix() * $objCommandeSousItem->stpi_getNbQte();
		}
		return $nbSousTotal;
	}
	
	
	public function stpi_getNbSousTotalTaxableFromArrObjCommandeSousItem()
	{
		$nbSousTotal = 0;
		
		$objSousItem =& $this->stpi_getObjCommandeSousItem()->stpi_getObjItem()->stpi_getObjSousItem();
		
		if (empty($this->arrObjCommandeSousItem))
		{
			return $nbSousTotal;
		}
		foreach ($this->arrObjCommandeSousItem as $objCommandeSousItem)
		{
			if ($objSousItem->stpi_setNbID($objCommandeSousItem->stpi_getNbSousItemID()))
			{
				if ($objSousItem->stpi_getBoolTaxable())
				{
					$nbSousTotal += $objCommandeSousItem->stpi_getNbPrix() * $objCommandeSousItem->stpi_getNbQte();
				}
			}
		}
		return $nbSousTotal;
	}
	
	
	public function stpi_getNbUnitsFromArrObjCommandeSousItem()
	{
		$nbUnits = 0;
		$objSousItem =& $this->objCommandeSousItem->stpi_getObjItem()->stpi_getObjSousItem();
		if (empty($this->arrObjCommandeSousItem))
		{
			return $nbUnits;
		}
		foreach ($this->arrObjCommandeSousItem as $objCommandeSousItem)
		{
			if (!$objSousItem->stpi_setNbID($objCommandeSousItem->stpi_getNbSousItemID()))
			{
				return $nbUnits;
			}
			$nbUnits += $objSousItem->stpi_getNbUnits() * $objCommandeSousItem->stpi_getNbQte();
		}
		return $nbUnits;
	}
	
	
	public function stpi_getNbQteFromArrObjCommandeSousItem()
	{
		$nbQte = 0;
		if (empty($this->arrObjCommandeSousItem))
		{
			return $nbQte;
		}
		foreach ($this->arrObjCommandeSousItem as $objCommandeSousItem)
		{
			$nbQte += $objCommandeSousItem->stpi_getNbQte();
		}
		return $nbQte;
	}
	
	
	public function stpi_getNbPrixRabaisFromArrObjCommandeSousItem()
	{
		$nbPrixRabais = 0;
		$objPrix =& $this->objCommandeSousItem->stpi_getObjItem()->stpi_getObjSousItem()->stpi_getObjPrix();
		
		if (empty($this->arrObjCommandeSousItem))
		{
			return $nbPrixRabais;
		}
		foreach ($this->arrObjCommandeSousItem as $objCommandeSousItem)
		{
			if ($objPrix->stpi_setNbID($objCommandeSousItem->stpi_getNbSousItemID(), 1))
			{
				$nbPrix = $objPrix->stpi_getNbPrix();
				$nbQte = $objCommandeSousItem->stpi_getNbQte();
				$nbRabaisPour = $objPrix->stpi_getNbRabaisPour();
				$nbRabaisStat = $objPrix->stpi_getNbRabaisStat();
				if (!empty($nbRabaisPour))
				{
					$nbPrixRabais += $nbRabaisPour * $nbPrix * $nbQte;
				} 
				if (!empty($nbRabaisStat))
				{
					$nbPrixRabais += $nbRabaisStat * $nbQte;
				}
			}
		}
		return $nbPrixRabais;
	}
	
	
	public function stpi_getNbPrixRabaisTaxableFromArrObjCommandeSousItem()
	{
		$nbPrixRabais = 0;
		$objSousItem =& $this->objCommandeSousItem->stpi_getObjItem()->stpi_getObjSousItem();
		$objPrix =& $objSousItem->stpi_getObjPrix();
		
		if (empty($this->arrObjCommandeSousItem))
		{
			return $nbPrixRabais;
		}
		foreach ($this->arrObjCommandeSousItem as $objCommandeSousItem)
		{
			if ($objSousItem->stpi_setNbID($objCommandeSousItem->stpi_getNbSousItemID()))
			{
				if ($objSousItem->stpi_getBoolTaxable())
				{
					if ($objPrix->stpi_setNbID($objCommandeSousItem->stpi_getNbSousItemID(), 1))
					{
						$nbPrix = $objPrix->stpi_getNbPrix();
						$nbQte = $objCommandeSousItem->stpi_getNbQte();
						$nbRabaisPour = $objPrix->stpi_getNbRabaisPour();
						$nbRabaisStat = $objPrix->stpi_getNbRabaisStat();
						if (!empty($nbRabaisPour))
						{
							$nbPrixRabais += $nbRabaisPour * $nbPrix * $nbQte;
						} 
						if (!empty($nbRabaisStat))
						{
							$nbPrixRabais += $nbRabaisStat * $nbQte;
						}
					}
				}
			}
		}
		
		return $nbPrixRabais;
	}
	
	
	public function stpi_getArrNbPrixTaxesFromArrObjCommandeSousItemAndAdresseFacturation($nboolShipZoneTaxable = 0, $nnbPrixShipping)
	{
		$boolTaxTaxable = false;
		
		$arrNbPrixTaxes = array();
		$arrNbPrixTaxes["nbGST"] = 0;
		$arrNbPrixTaxes["nbPST"] = 0;
		$arrNbPrixTaxes["nbHST"] = 0;
		$arrNbPrixTaxes["nbPrcGST"] = 0;
		$arrNbPrixTaxes["nbPrcPST"] = 0;
		$arrNbPrixTaxes["nbPrcHST"] = 0;
		$arrNbPrixTaxes["nbPrixTaxes"] = 0;
		
		$nbTotal = 0;
		
		$nbTotalTaxable = 0;
		
		$objCountry =& $this->objAdresse->stpi_getObjCountry();
		$objProvince =& $objCountry->stpi_getObjProvince();
		
		if (!isset($this->arrObjAdresse[1]))
		{
			return false;
		}
		
		if (empty($this->arrObjCommandeSousItem))
		{
			return false;
		}
		
		$nbTotal += $this->stpi_getNbSousTotalFromArrObjCommandeSousItem();
		
		$nbTotal -= $this->stpi_getNbPrixRabaisFromArrObjCommandeSousItem();
		
		$nbTotalTaxable += $this->stpi_getNbSousTotalTaxableFromArrObjCommandeSousItem();
		
		$nbTotalTaxable -= $this->stpi_getNbPrixRabaisTaxableFromArrObjCommandeSousItem();
		
		if ($nboolShipZoneTaxable)
		{
			$nbTotal += $nnbPrixShipping;
			$nbTotalTaxable += $nnbPrixShipping;
		}
		
		$strCountryID = $this->arrObjAdresse[1]->stpi_getStrCountryID();
		$strProvinceID = $this->arrObjAdresse[1]->stpi_getStrProvinceID();
		
		if ($objCountry->stpi_setStrID($strCountryID))
		{
			$arrNbPrixTaxes["nbPrcGST"] = $objCountry->stpi_getNbTax();
		}
		
		if (!empty($strProvinceID) && $strProvinceID != "isNULL")
		{
			if ($objProvince->stpi_setStrID($strProvinceID, $strCountryID))
			{
				$boolTaxTaxable = $objProvince->stpi_getBoolTaxTaxable();
				$arrNbPrixTaxes["nbPrcPST"] = $objProvince->stpi_getNbTax();
			}
		}
		
		if ($arrNbPrixTaxes["nbPrcGST"] != 0)
		{
			$arrNbPrixTaxes["nbGST"] = $nbTotal * $arrNbPrixTaxes["nbPrcGST"] / 100;
			if ($arrNbPrixTaxes["nbPrcPST"] != 0)
			{
				if ($boolTaxTaxable)
				{
					$arrNbPrixTaxes["nbPST"] = ($nbTotalTaxable + ($nbTotalTaxable * $arrNbPrixTaxes["nbPrcGST"]  / 100)) * $arrNbPrixTaxes["nbPrcPST"] / 100;
				}
				else
				{
					$arrNbPrixTaxes["nbHST"] = $nbTotal * ($arrNbPrixTaxes["nbPrcGST"] + $arrNbPrixTaxes["nbPrcPST"]) / 100;
					$arrNbPrixTaxes["nbPrcHST"] = $arrNbPrixTaxes["nbPrcGST"] + $arrNbPrixTaxes["nbPrcPST"];
				}
			}
		}
		
		if ($arrNbPrixTaxes["nbHST"] != 0)
		{
			$arrNbPrixTaxes["nbPrixTaxes"] = $arrNbPrixTaxes["nbHST"];
		}
		else
		{
			$arrNbPrixTaxes["nbPrixTaxes"] = $arrNbPrixTaxes["nbGST"] + $arrNbPrixTaxes["nbPST"]; 
		}
		
		return $arrNbPrixTaxes;
	}
	
	
	public function stpi_getNbTotal()
	{
		if (!$nbSousTotal = $this->stpi_selNbSousTotal())
		{
			$nbSousTotal = 0;
		}
		return $nbSousTotal + $this->nbPrixShipping + $this->nbPrixTaxes - $this->nbPrixRabais;
	}

	
	public function stpi_getArrObjCommandeSousItem()
	{
		return $this->arrObjCommandeSousItem;
	}
	
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_commande_Commande (nbTypeCommandeID, nbClientID, nbStatutCommandeID, nbMethodPayeID, nbInfoCarteID, nbRegistreID, strTel, strCourriel, nbPrixShipping, nbPrixRabais, nbPrixTaxes, strMessage, strLg, strCodeSuivi, dtEntryDate, dtShipped, dtArrived)";
		$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeCommandeID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbClientID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbStatutCommandeID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbMethodPayeID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbInfoCarteID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbRegistreID);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strTel) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strCourriel) . "'";
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbPrixShipping);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbPrixRabais);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbPrixTaxes);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strMessage) . "'";
		$SQL .= ", '" . LG . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strCodeSuivi) . "'";
		$SQL .= ", NOW(), NULL, NULL)";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			return $this->nbID;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_update()
	{
		if ($this->nbID == 0)
		{
			return false;
		}

		$SQL = "UPDATE stpi_commande_Commande";
		$SQL .= " SET nbTypeCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeCommandeID);
		$SQL .= ", nbClientID=" . $this->objBdd->stpi_trsInputToBdd($this->nbClientID);
		$SQL .= ", nbStatutCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbStatutCommandeID);
		$SQL .= ", nbMethodPayeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbMethodPayeID);
		$SQL .= ", nbInfoCarteID=" . $this->objBdd->stpi_trsInputToBdd($this->nbInfoCarteID);
		$SQL .= ", nbRegistreID=" . $this->objBdd->stpi_trsInputToBdd($this->nbRegistreID);
		$SQL .= ", strTel='" . $this->objBdd->stpi_trsInputToBdd($this->strTel) . "'";
		$SQL .= ", strCourriel='" . $this->objBdd->stpi_trsInputToBdd($this->strCourriel) . "'";
		$SQL .= ", nbPrixShipping=" . $this->objBdd->stpi_trsInputToBdd($this->nbPrixShipping);
		$SQL .= ", nbPrixRabais=" . $this->objBdd->stpi_trsInputToBdd($this->nbPrixRabais);
		$SQL .= ", nbPrixTaxes=" . $this->objBdd->stpi_trsInputToBdd($this->nbPrixTaxes);
		$SQL .= ", strMessage='" . $this->objBdd->stpi_trsInputToBdd($this->strMessage) . "'";
		$SQL .= ", strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLangID) . "'";
		$SQL .= ", strCodeSuivi='" . $this->objBdd->stpi_trsInputToBdd($this->strCodeSuivi) . "'";
		if ($this->dtShipped == "")
		{
			$SQL .= ", dtShipped=NULL";
		}
		else
		{
			$SQL .= ", dtShipped='" . $this->objBdd->stpi_trsInputToBdd($this->dtShipped) . "'";
		}
		if ($this->dtArrived == "")
		{
			$SQL .= ", dtArrived=NULL";
		}
		else
		{
			$SQL .= ", dtArrived='" . $this->objBdd->stpi_trsInputToBdd($this->dtArrived) . "'";
		}
		$SQL .= " WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		if (!$this->objBdd->stpi_update($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_delete($nnbCommandeID)
	{
		if (!$this->stpi_chkNbID($nnbCommandeID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_Commande WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($nnbCommandeID);
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_affJsAddPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addCommande()
		{
			xmlHttp = stpi_XmlHttpObject();
			
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_addCommande").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_addCommandeButton").style.visibility = "hidden";
			
			var strParam = "nbMethodPayeID=" + encodeURIComponent(document.getElementById("nbMethodPayeID").value);

			try
			{
				strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
				strParam = strParam + "&strNum=" + encodeURIComponent(document.getElementById("strNum").value);
				strParam = strParam + "&dtDateExpir1=" + encodeURIComponent(document.getElementById("dtDateExpir1").value);
				strParam = strParam + "&dtDateExpir2=" + encodeURIComponent(document.getElementById("dtDateExpir2").value);
				strParam = strParam + "&strCodeSecur=" + encodeURIComponent(document.getElementById("strCodeSecur").value);
			}
			catch(e)
			{
			}
			
			if (document.getElementById("boolAgreement").checked)
			{
				strParam = strParam + "&boolAgreement=1";
			}
			else
			{
				strParam = strParam + "&boolAgreement=0";
			}		
			strParam = strParam + "&strMessage" + "=" + encodeURIComponent(document.getElementById("strMessage").value);
			strParam = strParam + "&strCaptcha" + "=" + encodeURIComponent(document.getElementById("strCaptcha").value);
			strParam = strParam + "&sid=" + Math.random();	
				
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./checkout5.php?l=<? print(LG); ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_addCommande").innerHTML = xmlHttp.responseText;
		  				document.getElementById("stpi_addCommandeButton").style.visibility = "visible";
		  			}
				}
			}
			
			xmlHttp.open("POST", "commandeadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsAddRegistrePublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addCommandeRegistre()
		{
			xmlHttp = stpi_XmlHttpObject();
			
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_addCommandeRegistre").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_addCommandeRegistreButton").style.visibility = "hidden";
			
			var strParam = "nbMethodPayeID=" + encodeURIComponent(document.getElementById("nbMethodPayeID").value);

			try
			{
				strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
				strParam = strParam + "&strNum=" + encodeURIComponent(document.getElementById("strNum").value);
				strParam = strParam + "&dtDateExpir1=" + encodeURIComponent(document.getElementById("dtDateExpir1").value);
				strParam = strParam + "&dtDateExpir2=" + encodeURIComponent(document.getElementById("dtDateExpir2").value);
				strParam = strParam + "&strCodeSecur=" + encodeURIComponent(document.getElementById("strCodeSecur").value);
			}
			catch(e)
			{
			}
			
			if (document.getElementById("boolAgreement").checked)
			{
				strParam = strParam + "&boolAgreement=1";
			}
			else
			{
				strParam = strParam + "&boolAgreement=0";
			}		
			strParam = strParam + "&strMessage" + "=" + encodeURIComponent(document.getElementById("strMessage").value);
			strParam = strParam + "&strCaptcha" + "=" + encodeURIComponent(document.getElementById("strCaptcha").value);
			strParam = strParam + "&sid=" + Math.random();	
				
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./checkoutregistre5.php?l=<? print(LG); ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_addCommandeRegistre").innerHTML = xmlHttp.responseText;
		  				document.getElementById("stpi_addCommandeRegistreButton").style.visibility = "visible";
		  			}
				}
			}
			
			xmlHttp.open("POST", "commanderegistreadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	

	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_AdresseChangeToEditable(nTypeAdresse)
		{
			if (document.getElementById("chkUse" +  + nTypeAdresse).checked)
			{
				document.getElementById("strNom" + nTypeAdresse).disabled = false;
				document.getElementById("strPrenom" + nTypeAdresse).disabled = false;
				document.getElementById("strCie" + nTypeAdresse).disabled = false;
				document.getElementById("strAdresse" + nTypeAdresse).disabled = false;
				document.getElementById("strVille" + nTypeAdresse).disabled = false;
				document.getElementById("strProvinceID" + nTypeAdresse).disabled = false;
				document.getElementById("strCountryID" + nTypeAdresse).disabled = false;
				document.getElementById("strCodePostal" + nTypeAdresse).disabled = false;
			}
			else
			{
				document.getElementById("strNom" + nTypeAdresse).disabled = true;
				document.getElementById("strPrenom" + nTypeAdresse).disabled = true;
				document.getElementById("strCie" + nTypeAdresse).disabled = true;
				document.getElementById("strAdresse" + nTypeAdresse).disabled = true;
				document.getElementById("strVille" + nTypeAdresse).disabled = true;
				document.getElementById("strProvinceID" + nTypeAdresse).disabled = true;
				document.getElementById("strCountryID" + nTypeAdresse).disabled = true;
				document.getElementById("strCodePostal" + nTypeAdresse).disabled = true;
			}
		}
		function stpi_ChangeToEditable(narrAdresse)
		{
			document.getElementById("nbTypeCommandeID").disabled = false;
			document.getElementById("nbStatutCommandeID").disabled = false;
			document.getElementById("nbMethodPayeID").disabled = false;
			document.getElementById("strCarteNom").disabled = false;
			document.getElementById("strCarteNum").disabled = false;
			document.getElementById("strCarteDateExpir").disabled = false;
			document.getElementById("strCarteCodeSecur").disabled = false;
			document.getElementById("dtShipped").disabled = false;
			document.getElementById("dtArrived").disabled = false;
			document.getElementById("strCodeSuivi").disabled = false;
			document.getElementById("strTel").disabled = false;
			document.getElementById("strCourriel").disabled = false;
		
			for (i in narrAdresse)
			{
				if (narrAdresse[i] != 0)
				{
					document.getElementById("chkUse" + narrAdresse[i]).disabled = false;
					stpi_AdresseChangeToEditable(narrAdresse[i]);
				}
			}
			document.getElementById("nbPrixShipping").disabled = false;
			document.getElementById("nbPrixRabais").disabled = false;
			document.getElementById("nbPrixTaxes").disabled = false;
			document.getElementById("strMessage").disabled = false;
			document.getElementById("strLangID").disabled = false;
			
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editCommande(narrAdresse)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject[stpi_chkLang()];
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbCommandeID=" + encodeURIComponent(document.getElementById("nbCommandeID").value);
			strParam = strParam + "&nbStatutCommandeID=" + encodeURIComponent(document.getElementById("nbStatutCommandeID").value);
			strParam = strParam + "&nbTypeCommandeID=" + encodeURIComponent(document.getElementById("nbTypeCommandeID").value);
			strParam = strParam + "&nbMethodPayeID=" + encodeURIComponent(document.getElementById("nbMethodPayeID").value);
			strParam = strParam + "&strCarteNom=" + encodeURIComponent(document.getElementById("strCarteNom").value);
			strParam = strParam + "&strCarteNum=" + encodeURIComponent(document.getElementById("strCarteNum").value);
			strParam = strParam + "&strCarteDateExpir=" + encodeURIComponent(document.getElementById("strCarteDateExpir").value);
			strParam = strParam + "&strCarteCodeSecur=" + encodeURIComponent(document.getElementById("strCarteCodeSecur").value);
			strParam = strParam + "&strDtShipped=" + encodeURIComponent(document.getElementById("dtShipped").value);
			strParam = strParam + "&strDtArrived=" + encodeURIComponent(document.getElementById("dtArrived").value);
			strParam = strParam + "&strCodeSuivi=" + encodeURIComponent(document.getElementById("strCodeSuivi").value);
			strParam = strParam + "&strTel=" + encodeURIComponent(document.getElementById("strTel").value);
			strParam = strParam + "&strCourriel=" + encodeURIComponent(document.getElementById("strCourriel").value);
			for (i in narrAdresse)
			{
				if (narrAdresse[i] != 0 && document.getElementById("chkUse" +  + narrAdresse[i]).checked)
				{
					if (document.getElementById("chkUse" + narrAdresse[i]).checked)
					{
						strParam = strParam + "&boolUse" + narrAdresse[i] + "=t";
					}
					else
					{
						strParam = strParam + "&boolUse" + narrAdresse[i] + "=f";
					}
					strParam = strParam + "&strNom" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strNom" + narrAdresse[i]).value);
					strParam = strParam + "&strPrenom" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strPrenom" + narrAdresse[i]).value);
					strParam = strParam + "&strCie" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strCie" + narrAdresse[i]).value);
					strParam = strParam + "&strAdresse" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strAdresse" + narrAdresse[i]).value);
					strParam = strParam + "&strVille" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strVille" + narrAdresse[i]).value);
					strParam = strParam + "&strProvinceID" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strProvinceID" + narrAdresse[i]).value);
					strParam = strParam + "&strCountryID" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strCountryID" + narrAdresse[i]).value);
					strParam = strParam + "&strCodePostal" + narrAdresse[i] + "=" + encodeURIComponent(document.getElementById("strCodePostal" + narrAdresse[i]).value);
				}
			}
			strParam = strParam + "&nbPrixShipping=" + encodeURIComponent(document.getElementById("nbPrixShipping").value);
			strParam = strParam + "&nbPrixRabais=" + encodeURIComponent(document.getElementById("nbPrixRabais").value);
			strParam = strParam + "&nbPrixTaxes=" + encodeURIComponent(document.getElementById("nbPrixTaxes").value);
			strParam = strParam + "&strMessage=" + encodeURIComponent(document.getElementById("strMessage").value);
			strParam = strParam + "&strLangID=" + encodeURIComponent(document.getElementById("strLangID").value);
		
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commande.php?l=" + "<?php print(LG) ?>" + "&nbCommandeID=";
		  				var nbCommandeIDIndex = xmlHttp.responseText.indexOf("nbCommandeID") + 13;
		  				var nbCommandeIDLen = xmlHttp.responseText.length - nbCommandeIDIndex;
		  				var nbCommandeID = xmlHttp.responseText.substr(nbCommandeIDIndex, nbCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbCommandeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "commandeedit.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		function stpi_deleteInfoCarte()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messageDeleteInfoCarte").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandeinfocartedel.php?nbCommandeID=" + document.getElementById("nbCommandeID").value;
			strUrl = strUrl + "&nbConfirmed=0&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_messageDeleteInfoCarte").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		function stpi_deleteInfoCarteConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messageDeleteInfoCarte").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandeinfocartedel.php?nbCommandeID=" + document.getElementById("nbCommandeID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commande.php?l=" + "<?php print(LG); ?>" + "&nbCommandeID=";
		  				var nbCommandeIDIndex = xmlHttp.responseText.indexOf("nbCommandeID") + 13;
		  				var nbCommandeIDLen = xmlHttp.responseText.length - nbCommandeIDIndex;
		  				var nbCommandeID = xmlHttp.responseText.substr(nbCommandeIDIndex, nbCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbCommandeID;
		  				window.location = strUrlRedirect;
		  			}
		  			document.getElementById("stpi_messageDeleteInfoCarte").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		function stpi_ClearMessageDeleteInfoCarte()
		{
		  	document.getElementById("stpi_messageDeleteInfoCarte").innerHTML = "";
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affEdit()
	{
		
		if ($this->nbID == 0)
		{
			return false;				
		}
		print("<input type=\"hidden\" id=\"nbCommandeID\" value=\"" . $this->nbID . "\" />\n");
		
		print("<p>" . $this->objTexte->stpi_getArrTxt("commande") . " : <b>" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "</b><br/>" . $this->objBdd->stpi_trsBddToHTML($this->dtEntryDate) . "<br/>");
		if ($this->nbClientID != 0)
		{
			if ($this->objClient->stpi_setNbID($this->nbClientID))
			{
				print($this->objTexte->stpi_getArrTxt("client") . ": <a href=\"./client.php?l=" . LG . "&amp;nbClientID=" . $this->objBdd->stpi_trsBddToHTML($this->nbClientID) . "\">");
				print($this->objBdd->stpi_trsBddToHTML($this->nbClientID) . "</a>");
			}
		}
		print("</p>\n");
		
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("lang") . "<br/>\n");
		print("<select disabled=\"disabled\" id=\"strLangID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbLangID = $this->objLang->stpi_selAll())
		{
			foreach($arrNbLangID as $nbLangID)
			{
				if ($this->objLang->stpi_setStrID($nbLangID))
				{
					print("<option");
					if ($this->strLangID == $nbLangID)
					{
						print(" selected=\"selected\"");
					}
					print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbLangID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objLang->stpi_getStrLang()) . "</option>\n");
				}
			}
		}		
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("message") . "<br/>\n");
		print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strMessage\">" . $this->objBdd->stpi_trsBddToHTML($this->strMessage) . "</textarea><br/>\n");
		print("</p>\n");
		
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typecommande") . "<br/>\n");
		print("<select id=\"nbTypeCommandeID\" disabled=\"disabled\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeCommandeID = $this->objTypeCommande->stpi_selAll())
		{
			foreach($arrNbTypeCommandeID as $nbTypeCommandeID)
			{
				if ($this->objTypeCommande->stpi_setNbID($nbTypeCommandeID))
				{
					if ($this->objTypeCommande->stpi_getObjTypeCommandeLg()->stpi_setNbTypeCommandeID($nbTypeCommandeID))
					{
						if ($this->objTypeCommande->stpi_getObjTypeCommandeLg()->stpi_setNbID($this->objTypeCommande->stpi_getObjTypeCommandeLg()->stpi_selNbTypeCommandeIDLG()))
						{
							print("<option");
							if ($this->nbTypeCommandeID == $this->objTypeCommande->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeCommandeID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeCommande->stpi_getObjTypeCommandeLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}		
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("statutcommande") . "<br/>\n");
		print("<select id=\"nbStatutCommandeID\" disabled=\"disabled\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbStatutCommandeID = $this->objStatutCommande->stpi_selAll())
		{
			foreach($arrNbStatutCommandeID as $nbStatutCommandeID)
			{
				if ($this->objStatutCommande->stpi_setNbID($nbStatutCommandeID))
				{
					if ($this->objStatutCommande->stpi_getObjStatutCommandeLg()->stpi_setNbStatutCommandeID($nbStatutCommandeID))
					{
						if ($this->objStatutCommande->stpi_getObjStatutCommandeLg()->stpi_setNbID($this->objStatutCommande->stpi_getObjStatutCommandeLg()->stpi_selNbStatutCommandeIDLG()))
						{
							print("<option");
							if ($this->nbStatutCommandeID == $this->objStatutCommande->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbStatutCommandeID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objStatutCommande->stpi_getObjStatutCommandeLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}		
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("methodpaye") . "<br/>\n");
		print("<select id=\"nbMethodPayeID\" disabled=\"disabled\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbMethodPayeID = $this->objMethodPaye->stpi_selAll())
		{
			foreach($arrNbMethodPayeID as $nbMethodPayeID)
			{
				if ($this->objMethodPaye->stpi_setNbID($nbMethodPayeID))
				{
					if ($this->objMethodPaye->stpi_getObjMethodPayeLg()->stpi_setNbMethodPayeID($nbMethodPayeID))
					{
						if ($this->objMethodPaye->stpi_getObjMethodPayeLg()->stpi_setNbID($this->objMethodPaye->stpi_getObjMethodPayeLg()->stpi_selNbMethodPayeIDLG()))
						{
							print("<option");
							if ($this->nbMethodPayeID == $this->objMethodPaye->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbMethodPayeID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objMethodPaye->stpi_getObjMethodPayeLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}		
		print("</select><br/>&nbsp;&nbsp;");
		if ($this->nbInfoCarteID != 0)
		{
			$this->objInfoCarte->stpi_setNbID($this->nbInfoCarteID);
		}
		print($this->objTexte->stpi_getArrTxt("carte") . "<br/>&nbsp;&nbsp;");
		print($this->objTexte->stpi_getArrTxt("cartenom") . "<br/>&nbsp;&nbsp;");
		print("<input type=\"text\" disabled=\"disabled\" maxlength=\"50\" size=\"25\" id=\"strCarteNom\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objInfoCarte->stpi_getStrNom()) . "\"/><br/>&nbsp;&nbsp;");
		print($this->objTexte->stpi_getArrTxt("cartenum") . "<br/>&nbsp;&nbsp;");
		print("<input type=\"text\" disabled=\"disabled\" maxlength=\"20\" size=\"20\" id=\"strCarteNum\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objInfoCarte->stpi_getStrNum()) . "\"/><br/>&nbsp;&nbsp;");
		print($this->objTexte->stpi_getArrTxt("cartedateexpir") . "<br/>&nbsp;&nbsp;");
		print("<input type=\"text\" disabled=\"disabled\" maxlength=\"10\" size=\"10\" id=\"strCarteDateExpir\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objInfoCarte->stpi_getDtDateExpir()) . "\"/><br/>&nbsp;&nbsp;");
		print($this->objTexte->stpi_getArrTxt("cartecodesecur") . "<br/>&nbsp;&nbsp;");
		print("<input type=\"text\" disabled=\"disabled\" maxlength=\"5\" size=\"5\" id=\"strCarteCodeSecur\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objInfoCarte->stpi_getStrCodeSecur()) . "\"/><br/>\n");
		if ($this->nbInfoCarteID != 0)
		{
			print("<span id=\"stpi_messageDeleteInfoCarte\"></span><br/>\n");
			print("<input type=\"button\" id=\"stpi_InfoCarteDelete\" onclick=\"stpi_deleteInfoCarte()\" value=\"" . $this->objTexte->stpi_getArrTxt("deleteinfocarte") . "\" /><br/>\n");
		}		
		print("</p>\n");		
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("codesuivi") . "<br/>\n");
		print("<input type=\"text\" disabled=\"disabled\" maxlength=\"50\" size=\"20\" id=\"strCodeSuivi\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCodeSuivi) . "\"/><br/>\n");
		print($this->objTexte->stpi_getArrTxt("dateshipped") . "<br/>\n");
		print("<input type=\"text\" disabled=\"disabled\" maxlength=\"10\" size=\"10\" id=\"dtShipped\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->dtShipped) . "\"/><br/>\n");
		print($this->objTexte->stpi_getArrTxt("datearrived") . "<br/>\n");
		print("<input type=\"text\" disabled=\"disabled\" maxlength=\"10\" size=\"10\" id=\"dtArrived\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->dtArrived) . "\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("tel") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strTel\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objBdd->stpi_trsBddToTel($this->strTel)) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("courriel") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"200\" size=\"35\" id=\"strCourriel\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCourriel) . "\" />\n");
		print("</p>\n");
		
		$ajsTypeAdresse .= "";
		if ($arrNbTypeAdresseID = $this->objAdresse->stpi_getObjTypeAdresse()->stpi_selAll())
		{
			foreach($arrNbTypeAdresseID as $nbTypeAdresseID)
			{
				$ajsTypeAdresse .= "," . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID);
				if ($this->objAdresse->stpi_getObjTypeAdresse()->stpi_setNbID($nbTypeAdresseID))
				{
					print("<p>\n");	
					if ($this->objAdresse->stpi_getObjTypeAdresse()->stpi_getObjTypeAdresseLg()->stpi_setNbTypeAdresseID($nbTypeAdresseID))
					{
						if ($this->objAdresse->stpi_getObjTypeAdresse()->stpi_getObjTypeAdresseLg()->stpi_setNbID($this->objAdresse->stpi_getObjTypeAdresse()->stpi_getObjTypeAdresseLg()->stpi_selNbTypeAdresseIDLG()))
						{		
							print($this->objTexte->stpi_getArrTxt("adresse") . " " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getObjTypeAdresse()->stpi_getObjTypeAdresseLg()->stpi_getStrName()) . "<br/>\n");
				
						}
					}
					
					print("<input onclick=\"stpi_AdresseChangeToEditable(" . $nbTypeAdresseID . ")\" disabled=\"disabled\" id=\"chkUse" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\" type=\"checkbox\"");
					if ($this->objBdd->stpi_chkArrExists(array($this->nbID, $nbTypeAdresseID), array("nbCommandeID", "nbTypeAdresseID"), "stpi_commande_Adresse") AND $this->objAdresse->stpi_setNbID($this->nbID, $nbTypeAdresseID))
					{
						print(" checked=\"checked\"");
					}
					else
					{
						$this->objAdresse->__construct();
					}
					print("/><br/>\n");
					
					print($this->objTexte->stpi_getArrTxt("nom") . "<br/>\n");
					print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrNom()) . "\" /><br/>\n");

					print($this->objTexte->stpi_getArrTxt("prenom") . "<br/>\n");
					print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrPrenom()) . "\" /><br/>\n");

					print($this->objTexte->stpi_getArrTxt("cie") . "<br/>\n");
					print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strCie" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrCie()) . "\" /><br/>\n");

					print($this->objTexte->stpi_getArrTxt("adresse") . "<br/>\n");
					print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"55\" id=\"strAdresse" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrAdresse()) . "\" /><br/>\n");

					print($this->objTexte->stpi_getArrTxt("ville") . "<br/>\n");
					print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrVille()) . "\" /><br/>\n");

					print($this->objTexte->stpi_getArrTxt("province") . "<br/>\n");
					print("<select disabled=\"disabled\" id=\"strProvinceID" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\">\n");
					print("<option value=\"\"></option>\n");
					if ($arrStrIDs = $this->objAdresse->stpi_getObjCountry()->stpi_getObjProvince()->stpi_selAll())
					{
						foreach($arrStrIDs as $strIDs)
						{
							list($strProvinceID, $strCountryID) = explode("-", $strIDs);
							if ($this->objAdresse->stpi_getObjCountry()->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setStrProvinceID($strProvinceID))
							{
								if ($this->objAdresse->stpi_getObjCountry()->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setStrCountryID($strCountryID))
								{
									if ($this->objAdresse->stpi_getObjCountry()->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setNbID($this->objAdresse->stpi_getObjCountry()->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_selStrIDLG()))
									{
										print("<option");
										if ($this->objAdresse->stpi_getStrProvinceID() == $strProvinceID AND $this->objAdresse->stpi_getStrCountryID() == $strCountryID)
										{
											print(" selected=\"selected\"");
										}
										print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($strProvinceID) . "-" . $this->objBdd->stpi_trsBddToHTML($strCountryID) . "\">" . $strCountryID . " - " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getObjCountry()->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_getStrName()) . "</option>\n");
									}
								}
							}
						}
					}	
					print("</select><br/>\n");
			
					print($this->objTexte->stpi_getArrTxt("country") . "<br/>\n");
					print("<select disabled=\"disabled\" id=\"strCountryID" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\">\n");
					print("<option value=\"\"></option>\n");
					if ($arrStrCountryID = $this->objAdresse->stpi_getObjCountry()->stpi_selAll())
					{
						foreach($arrStrCountryID as $strCountryID)
						{
							if ($this->objAdresse->stpi_getObjCountry()->stpi_getObjCountryLg()->stpi_setStrCountryID($strCountryID))
							{
								if ($this->objAdresse->stpi_getObjCountry()->stpi_getObjCountryLg()->stpi_setNbID($this->objAdresse->stpi_getObjCountry()->stpi_getObjCountryLg()->stpi_selStrCountryIDLG()))
								{
									print("<option");
									if ($this->objAdresse->stpi_getStrCountryID() == $strCountryID)
									{
										print(" selected=\"selected\"");
									}
									print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($strCountryID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getObjCountry()->stpi_getObjCountryLg()->stpi_getStrName()) . "</option>\n");
								}
							}
						}
					}
					print("</select><br/>\n");

					print($this->objTexte->stpi_getArrTxt("codepostal") . "<br/>\n");
					print("<input disabled=\"disabled\" type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal" . $this->objBdd->stpi_trsBddToHTML($nbTypeAdresseID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrCodePostal()) . "\" /><br/>\n");
					print("</p>\n");
				}
			}
		}
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("soustotal") . ": " . $this->objBdd->stpi_trsBddToHTML($this->stpi_selNbSousTotal()) . "$<br/>\n");
		print($this->objTexte->stpi_getArrTxt("prixshipping") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" id=\"nbPrixShipping\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbPrixShipping) . "\" /><br/>\n");
		print($this->objTexte->stpi_getArrTxt("prixrabais") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" id=\"nbPrixRabais\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbPrixRabais) . "\" /><br/>\n");
		print($this->objTexte->stpi_getArrTxt("prixtaxes") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" id=\"nbPrixTaxes\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbPrixTaxes) . "\" /><br/>\n");
		print($this->objTexte->stpi_getArrTxt("total") . ": " . $this->objBdd->stpi_trsBddToHTML($this->stpi_getNbTotal()) . "$<br/>\n");
		print("</p>\n");

		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable(Array(0" . $ajsTypeAdresse . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editCommande(Array(0" . $ajsTypeAdresse . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delCommande()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delCommande()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandedel.php?nbCommandeID=" + document.getElementById("nbCommandeID").value;
			strUrl = strUrl + "&nbConfirmed=0&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		function stpi_delCommandeConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandedel.php?nbCommandeID=" + document.getElementById("nbCommandeID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				window.location = "./commandes.php?l=" + "<?php print(LG); ?>";
		  			}
		  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		function stpi_ClearMessage()
		{
		  	document.getElementById("stpi_messages").innerHTML = "";
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirm") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_delCommandeConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affDeleteInfocarte()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirminfocarte") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_deleteInfoCarteConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessageDeleteInfoCarte()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_selAll($nnbLimit = 0, $nstrAnneeD = 0, $nstrMoisD = 0, $nstrJourD = 0, $nstrAnneeF = 0, $nstrMoisF = 0, $nstrJourF = 0)
	{
		$arrID = array();
		$SQL = "SELECT nbCommandeID FROM stpi_commande_Commande WHERE 1=1";
		if ($nstrAnneeD != 0 AND $nstrMoisD != 0 AND $nstrJourD != 0 AND $nstrAnneeF != 0 AND $nstrMoisF != 0 AND $nstrJourF != 0)
		{
			$SQL .= " AND DATE(dtEntryDate)>='" . $nstrAnneeD . "-" . $nstrMoisD . "-" . $nstrJourD . "'";
			$SQL .= " AND DATE(dtEntryDate)<='" . $nstrAnneeF . "-" . $nstrMoisF . "-" . $nstrJourF . "'";
		}
		$SQL .= " ORDER BY dtEntryDate DESC";
		if ($nnbLimit != 0)
		{
			 $SQL .= " LIMIT 0, " . $nnbLimit;
		}
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbCommandeID"];
			}
			mysql_free_result($result);
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("none") . "</span><br/>\n");
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selAllMois()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT MONTH(dtEntryDate) as MoisID, YEAR(dtEntryDate) as Annee";
		$SQL .= " FROM stpi_commande_Commande";
		$SQL .= " ORDER BY Annee, MoisID";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["MoisID"] . "-" . $row["Annee"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbTypeAdresseID()
	{
		$arrID = array();
		$SQL = "SELECT nbTypeAdresseID";
		$SQL .= " FROM stpi_commande_Adresse";
		$SQL .= " WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeAdresseID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbSousItemID()
	{
		$arrID = array();
		$SQL = "SELECT nbSousItemID FROM stpi_commande_Commande_SousItem WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSousItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbSousTotal()
	{
		$SQL = "SELECT SUM(nbPrix*nbQte) as nbSousTotal FROM stpi_commande_Commande_SousItem WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				mysql_free_result($result);
				return $row["nbSousTotal"];
			}
			return false;			
		}
		else
		{
			return false;
		}
	}
}
?>