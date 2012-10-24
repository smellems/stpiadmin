<?php
require_once(dirname(__FILE__) . "/clstypeprix.php");
class clsprix
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypePrix;
	
	private $nbSousItemID;
	private $nbTypePrixID;
	private $nbPrix;
	private $nbRabaisPour;
	private $nbRabaisStat;
	private $dtEntryDate;
	
	public function __construct($nnbSousItemID = 0, $nnbTypePrixID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtprix");
		$this->objLang = new clslang();
		$this->objTypePrix = new clstypeprix();
		if ($nnbSousItemID == 0 OR $nnbTypePrixID == 0)
		{
			$this->nbSousItemID = 0;
			$this->nbTypePrixID = 0;
			$this->nbPrix = 0.00;
			$this->nbRabaisPour = 0.00;
			$this->nbRabaisStat = 0.00;
			$this->dtEntryDate = "";
		}
		else
		{
			if(!$this->stpi_setNbID($nnbSousItemID, $nnbTypePrixID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkNbSousItemID($nnbSousItemID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbSousItemID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbSousItemID, "nbSousItemID", "stpi_item_SousItem"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemid") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbPrix($nnbPrix)
	{
		if (!is_numeric($nnbPrix))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprix") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbPrix < 0 OR $nnbPrix > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprix") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbRabaisPour($nnbRabaisPour)
	{
		if (!is_numeric($nnbRabaisPour))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidrabaispour") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbRabaisPour < 0 OR $nnbRabaisPour > 100)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidrabaispour") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbRabaisStat($nnbRabaisStat)
	{
		if (!is_numeric($nnbRabaisStat))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidrabaisstat") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbRabaisStat < 0 OR $nnbRabaisStat > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidrabaisstat") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_setNbID($nnbSousItemID, $nnbTypePrixID)
	{
		if (!$this->stpi_setNbSousItemID($nnbSousItemID))
		{
			return false;				
		}
		if (!$this->stpi_setNbTypePrixID($nnbTypePrixID))
		{
			return false;				
		}
		
		$SQL = "SELECT nbPrix, nbRabaisPour, nbRabaisStat, dtEntryDate";
		$SQL .= " FROM stpi_item_Prix WHERE nbSousItemID=" . $this->nbSousItemID . " AND nbTypePrixID=" . $this->nbTypePrixID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbPrix = $row["nbPrix"];
				$this->nbRabaisPour = $row["nbRabaisPour"];
				$this->nbRabaisStat = $row["nbRabaisStat"];
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
	
	public function stpi_setNbSousItemID($nnbSousItemID)
	{
		if (!$this->stpi_chkNbSousItemID($nnbSousItemID))
		{
			return false;
		}
		$this->nbSousItemID = $nnbSousItemID;
		return true;
	}
	
	public function stpi_setNbTypePrixID($nnbTypePrixID)
	{
		if (!$this->objTypePrix->stpi_chkNbID($nnbTypePrixID))
		{
			return false;
		}
		$this->nbTypePrixID = $nnbTypePrixID;
		return true;
	}
	
	public function stpi_setNbPrix($nnbPrix)
	{
		if (!$this->stpi_chkNbPrix($nnbPrix))
		{
			return false;				
		}
		$this->nbPrix = $nnbPrix;
		return true;
	}
	
	public function stpi_setNbRabaisPour($nnbRabaisPour)
	{
		if (!$this->stpi_chkNbRabaisPour($nnbRabaisPour))
		{
			return false;				
		}
		$this->nbRabaisPour = $nnbRabaisPour;
		return true;
	}
	
	public function stpi_setNbRabaisStat($nnbRabaisStat)
	{
		if (!$this->stpi_chkNbRabaisStat($nnbRabaisStat))
		{
			return false;				
		}
		$this->nbRabaisStat = $nnbRabaisStat;
		return true;
	}
	
	public function stpi_getNbSousItemID()
	{
		return $this->nbSousItemID;
	}
	
	public function stpi_getNbTypePrixID()
	{
		return $this->nbTypePrixID;
	}
	
	public function stpi_getNbPrix()
	{
		return $this->nbPrix;
	}
	
	public function stpi_getNbRabaisPour()
	{
		return $this->nbRabaisPour;
	}
	
	public function stpi_getNbRabaisStat()
	{
		return $this->nbRabaisStat;
	}
	
	public function stpi_getObjTypePrix()
	{
		return $this->objTypePrix;
	}
	
	public function stpi_insert()
	{
		if ($this->nbSousItemID == 0 OR $this->nbTypePrixID == 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_item_Prix (nbSousItemID, nbTypePrixID, nbPrix, nbRabaisPour, nbRabaisStat, dtEntryDate)";
		$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbSousItemID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbTypePrixID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbPrix);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbRabaisPour);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbRabaisStat);
		$SQL .= ", NOW())";
		if ($this->objBdd->stpi_insert($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_update()
	{
		if ($this->nbSousItemID == 0 OR $this->nbTypePrixID == 0)
		{
			return false;
		}
		$SQL = "UPDATE stpi_item_Prix";
		$SQL .= " SET nbPrix=" . $this->objBdd->stpi_trsInputToBdd($this->nbPrix);
		$SQL .= ", nbRabaisPour=" . $this->objBdd->stpi_trsInputToBdd($this->nbRabaisPour);
		$SQL .= ", nbRabaisStat=" . $this->objBdd->stpi_trsInputToBdd($this->nbRabaisStat);
		$SQL .= " WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbSousItemID);
		$SQL .= " AND nbTypePrixID=" . $this->objBdd->stpi_trsInputToBdd($this->nbTypePrixID);
		if ($this->objBdd->stpi_update($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_deleteSousItemID($nnbSousItemID)
	{
		if (!$this->stpi_chkNbSousItemID($nnbSousItemID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_Prix WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbSousItemID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_deleteTypePrixID($nnbTypePrixID)
	{
		if (!$this->objTypePrix->stpi_chkNbID($nnbTypePrixID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_Prix WHERE nbTypePrixID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypePrixID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
	}	
}
?>