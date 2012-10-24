<?php
class clslienlg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $nbLienID;
	private $strName;
	private $strDesc;
	private $strLien;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtlien");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbLienID = 0;
			$this->strName = "";
			$this->strDesc = "";
			$this->strLien = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbLienLgID", "stpi_lien_Lien_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbLienID($nnbLienID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbLienID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg-nbLienID)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbLienID, "nbLienID", "stpi_lien_Lien"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-nbLienID)</span><br/>\n");
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
	
	public function stpi_chkStrLien($nstrLien)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrLien))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlien") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
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
		
		$SQL = "SELECT nbLienID, strName, strDesc, strLien, strLg FROM stpi_lien_Lien_Lg WHERE nbLienLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbLienID = $row["nbLienID"];
				$this->strName = $row["strName"];
				$this->strDesc = $row["strDesc"];
				$this->strLien = $row["strLien"];
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
	
	
	public function stpi_setNbLienID($nnbLienID)
	{
		if (!$this->stpi_chkNbLienID($nnbLienID))
		{
			return false;				
		}
		$this->nbLienID = $nnbLienID;
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
	
	public function stpi_setStrLien($nstrLien)
	{
		if (!$this->stpi_chkStrLien($nstrLien))
		{
			return false;
		}
		$this->strLien = $nstrLien;
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
	
	public function stpi_getStrLien()
	{
		return $this->strLien;
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
		$SQL = "INSERT INTO stpi_lien_Lien_Lg (nbLienID, strName, strDesc, strLien, strLg)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbLienID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strDesc) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strLien) . "',";
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
		$SQL = "UPDATE stpi_lien_Lien_Lg";
		$SQL .= " SET nbLienID='" . $this->objBdd->stpi_trsInputToBdd($this->nbLienID) . "',";
		$SQL .= " strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " strName='" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "',";
		$SQL .= " strDesc='" . $this->objBdd->stpi_trsInputToBdd($this->strDesc) . "',";
		$SQL .= " strLien='" . $this->objBdd->stpi_trsInputToBdd($this->strLien) . "'";
		$SQL .= " WHERE nbLienLgID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_deleteLienId($nnbLienID)
	{
		if (!$this->stpi_chkNbLienID($nnbLienID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_lien_Lien_Lg WHERE nbLienID='" . $this->objBdd->stpi_trsInputToBdd($nnbLienID) . "'";
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
	
	
	public function stpi_selNbLienID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		foreach ($arrLang as $strLg)	
		{
			$SQL = "SELECT nbLienLgID";
			$SQL .= " FROM stpi_lien_Lien_Lg";
			$SQL .= " WHERE nbLienID='" . $this->nbLienID . "'";
			$SQL .= " AND strLg = '" . $strLg . "'";
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbLienLgID"];
				}
				else
				{
					// print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . $strLg . ")</span><br/>\n");
					return false;
				}
				mysql_free_result($result);
			}
			else
			{
				// print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . $strLg . ")</span><br/>\n");
				return false;
			}
		}
		return $arrID;
	}
	
	public function stpi_selNbLienIDLG()
	{
		$SQL = "SELECT nbLienLgID";
		$SQL .= " FROM stpi_lien_Lien_Lg";
		$SQL .= " WHERE nbLienID='" . $this->nbLienID . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbID = $row["nbLienLgID"];
			}
			else
			{
				// print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . LG . ")</span><br/>\n");
				return false;
			}
			mysql_free_result($result);
		}
		else
		{
			// print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . LG . ")</span><br/>\n");
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
		$SQL = "SELECT DISTINCT nbLienID";
		$SQL .= " FROM stpi_lien_Lien_Lg";
		$SQL .= " WHERE  strName LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrName) . "%'";
		$SQL .= " ORDER BY strName";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbLienID"];
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
