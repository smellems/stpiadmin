<?php
class clstypebannierelg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $nbTypeBanniereID;
	private $strName;
	private $strDesc;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypebanniere");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbTypeBanniereID = 0;
			$this->strName = "";
			$this->strDesc = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeBanniereLgID", "stpi_banniere_TypeBanniere_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbTypeBanniereID($nnbTypeBanniereID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbTypeBanniereID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbTypeBanniereID, "nbTypeBanniereID", "stpi_banniere_TypeBanniere"))
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
	
	public function stpi_chkStrDesc($nstrDesc)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrDesc) && $nstrDesc != "")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddesc") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
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
		
		$SQL = "SELECT nbTypeBanniereID, strName, strDesc, strLg FROM stpi_banniere_TypeBanniere_Lg WHERE nbTypeBanniereLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeBanniereID = $row["nbTypeBanniereID"];
				$this->strName = $row["strName"];
				$this->strDesc = $row["strDesc"];
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
	
	
	public function stpi_setNbTypeBanniereID($nnbTypeBanniereID)
	{
		if (!$this->stpi_chkNbTypeBanniereID($nnbTypeBanniereID))
		{
			return false;				
		}
		$this->nbTypeBanniereID = $nnbTypeBanniereID;
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
	
	
	public function stpi_setStrDesc($nstrDesc)
	{
		if (!$this->stpi_chkStrDesc($nstrDesc))
		{
			return false;
		}
		$this->strDesc = $nstrDesc;
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
	
	
	public function stpi_getNbTypeBanniereID()
	{
		return $this->nbTypeBanniereID;
	}
	
	
	public function stpi_getStrName()
	{
		return $this->strName;
	}
	
	
	public function stpi_getStrDesc()
	{
		return $this->strDesc;
	}
	
	
	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_banniere_TypeBanniere_Lg (nbTypeBanniereID, strName, strDesc, strLg)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeBanniereID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strDesc) . "',";
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
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$SQL = "UPDATE stpi_banniere_TypeBanniere_Lg";
		$SQL .= " SET nbTypeBanniereID='" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeBanniereID) . "',";
		$SQL .= " strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " strName='" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "',";
		$SQL .= " strDesc='" . $this->objBdd->stpi_trsInputToBdd($this->strDesc) . "'";
		$SQL .= " WHERE nbTypeBanniereLgID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	
	public function stpi_deleteTypeBanniereId($nnbTypeBanniereID)
	{
		if (!$this->stpi_chkNbTypeBanniereID($nnbTypeBanniereID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_banniere_TypeBanniere_Lg WHERE nbTypeBanniereID='" . $this->objBdd->stpi_trsInputToBdd($nnbTypeBanniereID) . "'";
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
	
	
	public function stpi_selNbTypeBanniereID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		
		foreach ($arrLang as $strLg)	
		{
			$SQL = "SELECT nbTypeBanniereLgID";
			$SQL .= " FROM stpi_banniere_TypeBanniere_Lg";
			$SQL .= " WHERE nbTypeBanniereID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeBanniereID) . "'";
			$SQL .= " AND strLg = '" . $this->objBdd->stpi_trsInputToBdd($strLg) . "'";
			
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbTypeBanniereLgID"];
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . $strLg . ")</span><br/>\n");
					return false;
				}
				mysql_free_result($result);
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . $strLg . ")</span><br/>\n");
				return false;
			}
		}
		return $arrID;
	}
	
	public function stpi_selNbTypeBanniereIDLG()
	{
		$SQL = "SELECT nbTypeBanniereLgID";
		$SQL .= " FROM stpi_banniere_TypeBanniere_Lg";
		$SQL .= " WHERE nbTypeBanniereID='" . $this->nbTypeBanniereID . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbID = $row["nbTypeBanniereLgID"];
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . LG . ")</span><br/>\n");
				return false;
			}
			mysql_free_result($result);
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . LG . ")</span><br/>\n");
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
		$SQL = "SELECT DISTINCT nbTypeBanniereID";
		$SQL .= " FROM stpi_banniere_TypeBanniere_Lg";
		$SQL .= " WHERE  strName LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrName) . "%'";
		$SQL .= " ORDER BY strName LIMIT 0,10";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeBanniereID"];
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