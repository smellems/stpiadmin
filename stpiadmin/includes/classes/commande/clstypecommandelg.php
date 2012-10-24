<?php
class clstypecommandelg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $nbTypeCommandeID;
	private $strName;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypecommande");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbTypeCommandeID = 0;
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
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeCommandeLgID", "stpi_commande_TypeCommande_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbTypeCommandeID($nnbTypeCommandeID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbTypeCommandeID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbTypeCommandeID, "nbTypeCommandeID", "stpi_commande_TypeCommande"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkStrName($nstrName)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrName))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidname") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	public function stpi_chkStrLg($nstrLg)
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		if (!$this->objBdd->stpi_chkInputToBdd($nstrLg))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
			return false;				
		}
		if (!in_array($nstrLg, $arrLang))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
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
		
		$SQL = "SELECT nbTypeCommandeID, strName, strLg FROM stpi_commande_TypeCommande_Lg WHERE nbTypeCommandeLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeCommandeID = $row["nbTypeCommandeID"];
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
	
	
	public function stpi_setNbTypeCommandeID($nnbTypeCommandeID)
	{
		if (!$this->stpi_chkNbTypeCommandeID($nnbTypeCommandeID))
		{
			return false;				
		}
		$this->nbTypeCommandeID = $nnbTypeCommandeID;
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
	
	public function stpi_getStrName()
	{
		return $this->strName;
	}

	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_commande_TypeCommande_Lg (nbTypeCommandeID, strName, strLg)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeCommandeID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "')";

		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			return $this->nbID;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "&nbsp;(lg-" . $this->strLg . ")</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_update()
	{
		$SQL = "UPDATE stpi_commande_TypeCommande_Lg";
		$SQL .= " SET nbTypeCommandeID='" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeCommandeID) . "',";
		$SQL .= " strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " strName='" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "'";
		$SQL .= " WHERE nbTypeCommandeLgID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if ($this->objBdd->stpi_update($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(lg-" . $this->strLg . ")</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_deleteTypeCommandeId($nnbTypeCommandeID)
	{
		if (!$this->stpi_chkNbTypeCommandeID($nnbTypeCommandeID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_TypeCommande_Lg WHERE nbTypeCommandeID='" . $this->objBdd->stpi_trsInputToBdd($nnbTypeCommandeID) . "'";
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
	}
	
	
	public function stpi_selNbTypeCommandeID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		foreach ($arrLang as $strLg)	
		{
			$SQL = "SELECT nbTypeCommandeLgID";
			$SQL .= " FROM stpi_commande_TypeCommande_Lg";
			$SQL .= " WHERE nbTypeCommandeID='" . $this->nbTypeCommandeID . "'";
			$SQL .= " AND strLg = '" . $strLg . "'";
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbTypeCommandeLgID"];
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
		}
		return $arrID;
	}
	
	public function stpi_selNbTypeCommandeIDLG()
	{
		$SQL = "SELECT nbTypeCommandeLgID";
		$SQL .= " FROM stpi_commande_TypeCommande_Lg";
		$SQL .= " WHERE nbTypeCommandeID='" . $this->nbTypeCommandeID . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbID = $row["nbTypeCommandeLgID"];
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
	
	public function stpi_selSearchName($nstrName)
	{
		if (!$this->stpi_chkStrName($nstrName))
		{
			return false;
		}
		$SQL = "SELECT DISTINCT nbTypeCommandeID";
		$SQL .= " FROM stpi_commande_TypeCommande_Lg";
		$SQL .= " WHERE  strName LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrName) . "%'";
		$SQL .= " ORDER BY strName LIMIT 0,20";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeCommandeID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
}
?>