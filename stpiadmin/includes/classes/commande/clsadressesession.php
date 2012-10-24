<?php
class clsadressesession
{
	private $nbTypeAdresseID;
	private $strNom;
	private $strPrenom;
	private $strCie;
	private $strAdresse;
	private $strVille;
	private $strCountryID;
	private $strProvinceID;
	private $strCodePostal;
	
	public function __construct()
	{
		$this->nbTypeAdresseID = 0;
		$this->strNom = "";
		$this->strPrenom = "";
		$this->strCie = "";
		$this->strAdresse = "";
		$this->strVille = "";
		$this->strCountryID = "";
		$this->strProvinceID = "";
		$this->strCodePostal = "";
		return true;
	}
	
	
	public function stpi_setNbTypeAdresseID($nnbTypeAdresseID)
	{
		$this->nbTypeAdresseID = $nnbTypeAdresseID;
	}
	
	
	public function stpi_setStrNom($nstrNom)
	{
		$this->strNom = $nstrNom;
	}
	
	
	public function stpi_setStrPrenom($nstrPrenom)
	{
		$this->strPrenom = $nstrPrenom;
	}
	
	
	public function stpi_setStrCie($nstrCie)
	{
		$this->strCie = $nstrCie;
	}
	
	
	public function stpi_setStrAdresse($nstrAdresse)
	{
		$this->strAdresse = $nstrAdresse;
	}
	
	
	public function stpi_setStrVille($nstrVille)
	{
		$this->strVille = $nstrVille;
	}
	
	
	public function stpi_setStrCountryID($nstrCountryID)
	{
		$this->strCountryID = $nstrCountryID;
	}
	
	
	public function stpi_setStrProvinceID($nstrProvinceID)
	{
		$this->strProvinceID = $nstrProvinceID;
	}
	
	
	public function stpi_setStrCodePostal($nstrCodePostal)
	{
		$this->strCodePostal = $nstrCodePostal;
	}
	
	
	public function stpi_getNbTypeAdresseID()
	{
		return $this->nbTypeAdresseID;
	}
	
	
	public function stpi_getStrNom()
	{
		return $this->strNom;
	}
	
	
	public function stpi_getStrPrenom()
	{
		return $this->strPrenom;
	}
	
	
	public function stpi_getStrCie()
	{
		return $this->strCie;
	}
	
	
	public function stpi_getStrAdresse()
	{
		return $this->strAdresse;
	}
	
	
	public function stpi_getStrVille()
	{
		return $this->strVille;
	}
	
	
	public function stpi_getStrCountryID()
	{
		return $this->strCountryID;
	}

	
	public function stpi_getStrProvinceID()
	{
		return $this->strProvinceID;
	}


	public function stpi_getStrCodePostal()
	{
		return $this->strCodePostal;
	}
	
	
	public function stpi_getObjTypeAdresse()
	{
		return $this->objTypeAdresse;
	}
	
	
	public function stpi_getObjCountry()
	{
		return $this->objCountry;
	}
}
?>