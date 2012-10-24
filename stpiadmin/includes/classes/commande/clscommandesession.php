<?php
require_once(dirname(__FILE__) . "/clsadressesession.php");
require_once(dirname(__FILE__) . "/clscommandesousitemsession.php");
class clscommandesession
{
	private $objInfoCarteSession;
	
	private $strTel;
	private $strCourriel;
	private $nbRegistreID;
	private $nbPrixShipping;
	private $nbPrixRabais;
	private $nbPrixTaxes;
	private $nbPrixSousTotal;
	private $nbSousItemQte;
		
	private $arrObjCommandeSousItemSession;
	private $arrObjAdresseSession;	
	
	public function __construct()
	{
		$this->arrObjAdresseSession = array();
		$this->arrObjCommandeSousItemSession = array();
		$this->strTel = "";
		$this->strCourriel = "";
		$this->nbRegistreID = 0;
		$this->nbPrixShipping = 0.00;
		$this->nbPrixRabais = 0.00;
		$this->nbPrixTaxes = 0.00;
		$this->nbPrixTaxes = 0.00;
		$this->nbSousItemQte = 0;
		return true;
	}	
	
	
	public function stpi_setStrTel($nstrTel)
	{
		$this->strTel = $nstrTel;
	}

	
	public function stpi_setStrCourriel($nstrCourriel)
	{
		$this->strCourriel = $nstrCourriel;
	}
	
	
	public function stpi_setNbRegistreID($nnbRegistreID)
	{
		$this->nbRegistreID = $nnbRegistreID;
	}
	
	
	public function stpi_setNbPrixShipping($nnbPrixShipping)
	{
		$this->nbPrixShipping = $nnbPrixShipping;
	}
	
	
	public function stpi_setNbPrixRabais($nnbPrixRabais)
	{
		$this->nbPrixRabais = $nnbPrixRabais;
	}
	
	
	public function stpi_setNbPrixTaxes($nnbPrixTaxes)
	{
		$this->nbPrixTaxes = $nnbPrixTaxes;
	}
	
	
	public function stpi_setNbSousTotal($nnbSousTotal)
	{
		$this->nbSousTotal = $nnbSousTotal;
	}
	
	
	public function stpi_setNbSousItemQte($nnbSousItemQte)
	{
		$this->nbSousItemQte = $nnbSousItemQte;
	}
	
	
	public function stpi_setArrObjCommandeSousItemSession($narrObjCommandeSousItemSession)
	{
		$this->arrObjCommandeSousItemSession = $narrObjCommandeSousItemSession;
	}
	
	
	public function stpi_setArrObjAdresseSession($narrObjAdresseSession)
	{
		$this->arrObjAdresseSession = $narrObjAdresseSession;
	}
	
	
	public function stpi_setObjCommandeSessionToSession()
	{
		if (!$_SESSION["stpiObjCommandeSession"] = serialize($this))
		{
			return false;
		}
		return true;
	}
	
	
	public function stpi_setObjCommandeRegistreSessionToSession()
	{
		if (!$_SESSION["stpiObjCommandeRegistreSession"] = serialize($this))
		{
			return false;
		}
		return true;
	}
		
		
	public function stpi_getStrTel()
	{
		return $this->strTel;
	}
	
	
	public function stpi_getStrCourriel()
	{
		return $this->strCourriel;
	}
	
	
	public function stpi_getNbRegistreID()
	{
		return $this->nbRegistreID;
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
	
	
	public function stpi_getNbSousTotal()
	{
		return $this->nbSousTotal;
	}
	
	
	public function stpi_getNbSousItemQte()
	{
		return $this->nbSousItemQte;
	}
			
	
	public function stpi_getArrObjAdresseSession()
	{
		return $this->arrObjAdresseSession;
	}
	
	
	public function stpi_getArrObjCommandeSousItemSession()
	{
		return $this->arrObjCommandeSousItemSession;
	}
		
		
	public function stpi_getObjCommandeSessionFromSession()
	{
		if (!isset($_SESSION["stpiObjCommandeSession"]))
		{
			return false;
		}	
					
		return unserialize($_SESSION["stpiObjCommandeSession"]);
	}
	
	
	public function stpi_getObjCommandeRegistreSessionFromSession()
	{
		if (!isset($_SESSION["stpiObjCommandeRegistreSession"]))
		{
			return false;
		}		
		
		return unserialize($_SESSION["stpiObjCommandeRegistreSession"]);
	}
}
?>