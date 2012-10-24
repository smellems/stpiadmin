<?php
class clspagepubliclg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $nbPageID;
	private $strTitre;
	private $StrContent;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtpage");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbPageID = 0;
			$this->strTitre = "";
			$this->strDesc = "";
			$this->strKeywords = "";
			$this->strContent = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbPageLgID", "stpi_page_Page_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbPageID($nnbPageID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbPageID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbPageID, "nbPageID", "stpi_page_Page"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkStrTitre($nstrTitre)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrTitre))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtitre") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
			return false;				
		}
		return true;
	}

	public function stpi_chkStrDesc($nstrDesc)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrDesc))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddesc") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
			return false;				
		}
		return true;
	}

	public function stpi_chkStrKeywords($nstrKeywords)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrKeywords))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidkeywords") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	public function stpi_chkStrContent($nstrContent)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrContent))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtext") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
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
		
		$SQL = "SELECT nbPageID, strTitre, strDesc, strKeywords, StrContent, strLg FROM stpi_page_Page_Lg WHERE nbPageLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbPageID = $row["nbPageID"];
				$this->strTitre = $row["strTitre"];
				$this->strDesc = $row["strDesc"];
				$this->strKeywords = $row["strKeywords"];
				$this->StrContent = $row["StrContent"];
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
	
	public function stpi_setNbPageID($nnbPageID)
	{
		if (!$this->stpi_chkNbPageID($nnbPageID))
		{
			return false;				
		}
		$this->nbPageID = $nnbPageID;
		return true;
	}
	
	
	public function stpi_setStrTitre($nstrTitre)
	{
		if (!$this->stpi_chkStrTitre($nstrTitre))
		{
			return false;
		}
		$this->strTitre = $nstrTitre;
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

	public function stpi_setStrKeywords($nstrKeywords)
	{
		if (!$this->stpi_chkStrKeywords($nstrKeywords))
		{
			return false;
		}
		$this->strKeywords = $nstrKeywords;
		return true;
	}	
	
	public function stpi_setStrContent($nStrContent)
	{
		if (!$this->stpi_chkStrContent($nStrContent))
		{
			return false;
		}
		$this->StrContent = $nStrContent;
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
	
	
	public function stpi_getNbPageID()
	{
		return $this->nbPageID;
	}
	
	public function stpi_getStrTitre()
	{
		return $this->strTitre;
	}

	public function stpi_getStrDesc()
	{
		return $this->strDesc;
	}

	public function stpi_getStrKeywords()
	{
		return $this->strKeywords;
	}
	
	public function stpi_getStrContent()
	{
		return $this->StrContent;
	}
	
	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_page_Page_Lg (nbPageID, strTitre, strDesc, strKeywords, StrContent, strLg)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbPageID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strTitre) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strDesc) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strKeywords) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->StrContent) . "',";
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
		$SQL = "UPDATE stpi_page_Page_Lg";
		$SQL .= " SET strTitre='" . $this->objBdd->stpi_trsInputToBdd($this->strTitre) . "',";
		$SQL .= " strDesc='" . $this->objBdd->stpi_trsInputToBdd($this->strDesc) . "',";
		$SQL .= " strKeywords='" . $this->objBdd->stpi_trsInputToBdd($this->strKeywords) . "',";
		$SQL .= " StrContent='" . $this->objBdd->stpi_trsInputToBdd($this->StrContent) . "',";
		$SQL .= " strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " nbPageID='" . $this->objBdd->stpi_trsInputToBdd($this->nbPageID) . "'";
		$SQL .= " WHERE nbPageLgID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_deletePageId($nnbPageID)
	{
		if (!$this->stpi_chkNbPageID($nnbPageID))
		{
			return false;
		}
		$SQL = "DELETE FROM stpi_page_Page_Lg WHERE nbPageID='" . $this->objBdd->stpi_trsInputToBdd($nnbPageID) . "'";
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
	
	public function stpi_selSearchTitre($nstrTitre)
	{
		if (!$this->stpi_chkStrTitre($nstrTitre))
		{
			return false;
		}
		$SQL = "SELECT DISTINCT nbPageID";
		$SQL .= " FROM stpi_page_Page_Lg";
		$SQL .= " WHERE  strTitre LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrTitre) . "%'";
		$SQL .= " ORDER BY strTitre";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbPageID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
	
	
	public function stpi_selNbPageID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		foreach ($arrLang as $strLg)	
		{
			$SQL = "SELECT nbPageLgID";
			$SQL .= " FROM stpi_page_Page_Lg";
			$SQL .= " WHERE nbPageID='" . $this->nbPageID . "'";
			$SQL .= " AND strLg = '" . $strLg . "'";
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbPageLgID"];
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
}
?>
