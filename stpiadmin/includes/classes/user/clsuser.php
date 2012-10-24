<?php

class clsuser
{
	private $nbID;
	private $nbUserTypeID;
	private $nbIP;
	
	public function __construct()
	{
		
	}
	
	public function stpi_setNbID($nnbID)
	{
		$this->nbID = $nnbID;
	}
	
	public function stpi_setNbTypeUserID($nnbTypeUserID)
	{
		$this->nbTypeUserID = $nnbTypeUserID;
	}
	
	public function stpi_setNbIP()
	{
		$this->nbIP = $_SERVER["REMOTE_ADDR"];
	}
	
	public function stpi_setObjUserToSession()
	{
		if (!$_SESSION["stpiObjUser"] = serialize($this))
		{
			return false;
		}
		return true;
	}	
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbTypeUserID()
	{
		return $this->nbTypeUserID;
	}
	
	public function stpi_getNbIP()
	{
		return $this->nbIP;
	}
	
	public function stpi_getObjUserFromSession()
	{
		if (!isset($_SESSION["stpiObjUser"]))
		{
			return false;
		}
		
		return unserialize($_SESSION["stpiObjUser"]);
	}
}

?>