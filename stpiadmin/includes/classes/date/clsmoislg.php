<?php
class clsmoislg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $nbMoisID;
	private $strName;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtdate");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbMoisID = 0;
			$this->strName = "";
			$this->strLg = "";
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
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmois") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbMoisLgID", "stpi_date_Mois_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmois") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbMoisID($nnbMoisID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbMoisID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmois") . "&nbsp;(ID)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbMoisID, "nbMoisID", "stpi_date_Mois_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmois") . "&nbsp;(!chkExists(ID))</span><br/>\n");
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
		
		$SQL = "SELECT nbMoisID, strName, strLg FROM stpi_date_Mois_Lg WHERE nbMoisLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbMoisID = $row["nbMoisID"];
				$this->strName = $row["strName"];
				$this->strLg = $row["strLg"];
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
	
	public function stpi_setNbMoisID($nnbMoisID)
	{
		if (!$this->stpi_chkNbMoisID($nnbMoisID))
		{
			return false;				
		}
		$this->nbMoisID = $nnbMoisID;
		return true;
	}
	
	
	public function stpi_setStrName($nstrName)
	{
		if (!$this->stpi_chkStrName($nstrName))
		{
			return false;
		}
		$this->strName = $nstrName;
		return true;
	}
	
	
	public function stpi_setStrLg($nstrLg)
	{
		if (!$this->stpi_chkStrLg($nstrLg))
		{
			return false;				
		}
		$this->strLg = $nstrLg;
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;	
	}
	
	public function stpi_getNbMoisID()
	{
		return $this->nbMoisID;	
	}
	
	public function stpi_getStrName()
	{
		return $this->strName;
	}

	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	public function stpi_selNbMoisIDLG()
	{
		$SQL = "SELECT nbMoisLgID";
		$SQL .= " FROM stpi_date_Mois_Lg";
		$SQL .= " WHERE nbMoisID='" . $this->nbMoisID . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbID = $row["nbMoisLgID"];
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
		return $nbID;
	}
}
?>