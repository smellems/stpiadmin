<?php
class clscommandesousitemsession
{
	private $nbSousItemID;
	private $nbQte;
	private $nbPrix;
	
	public function __construct()
	{
		$this->nbSousItemID = 0;
		$this->nbQte = 0;
		$this->nbPrix = 0;
		return true;
	}
	
	
	public function stpi_setNbSousItemID($nnbSousItemID)
	{
		$this->nbSousItemID = $nnbSousItemID;
	}
	
	
	public function stpi_setNbQte($nnbQte)
	{
		$this->nbQte = $nnbQte;
	}
	
	
	public function stpi_setNbPrix($nnbPrix)
	{
		$this->nbPrix = $nnbPrix;
	}
		
		
	public function stpi_getNbSousItemID()
	{
		return $this->nbSousItemID;
	}
	
	
	public function stpi_getNbQte()
	{
		return $this->nbQte;
	}
	
	
	public function stpi_getNbPrix()
	{
		return $this->nbPrix;
	}
}
?>