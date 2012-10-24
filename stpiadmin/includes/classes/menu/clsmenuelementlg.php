<?php
class clsmenuelementlg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $nbMenuElementID;
	private $strText;
	private $strLien;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtmenuelement");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbMenuElementID = 0;
			$this->strText = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbMenuElementLgID", "stpi_menu_MenuElement_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbMenuElementID($nnbMenuElementID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbMenuElementID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg-nbMenuElementID)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbMenuElementID, "nbMenuElementID", "stpi_menu_MenuElement"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-nbMenuElementID)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkStrText($nstrText)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrText))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtext") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
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
		
		$SQL = "SELECT nbMenuElementID, strTexte, strLien, strLg FROM stpi_menu_MenuElement_Lg WHERE nbMenuElementLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbMenuElementID = $row["nbMenuElementID"];
				$this->strText = $row["strTexte"];
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
	
	
	public function stpi_setNbMenuElementID($nnbMenuElementID)
	{
		if (!$this->stpi_chkNbMenuElementID($nnbMenuElementID))
		{
			return false;				
		}
		$this->nbMenuElementID = $nnbMenuElementID;
		return true;
	}
	
	
	public function stpi_setStrText($nstrText)
	{
		if (!$this->stpi_chkStrText($nstrText))
		{
			return false;
		}
		$this->strText = $nstrText;
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
	
	public function stpi_getStrText()
	{
		return $this->strText;
	}
	
	public function stpi_getStrLien()
	{
		return $this->strLien;
	}
	
	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_menu_MenuElement_Lg (nbMenuElementID, strTexte, strLien, strLg)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbMenuElementID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strText) . "',";
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
		$SQL = "UPDATE stpi_menu_MenuElement_Lg";
		$SQL .= " SET nbMenuElementID='" . $this->objBdd->stpi_trsInputToBdd($this->nbMenuElementID) . "',";
		$SQL .= " strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " strTexte='" . $this->objBdd->stpi_trsInputToBdd($this->strText) . "',";
		$SQL .= " strLien='" . $this->objBdd->stpi_trsInputToBdd($this->strLien) . "'";
		$SQL .= " WHERE nbMenuElementLgID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";

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
	
	public function stpi_deleteMenuElementId($nnbMenuElementID)
	{
		if (!$this->stpi_chkNbMenuElementID($nnbMenuElementID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_menu_MenuElement_Lg WHERE nbMenuElementID='" . $this->objBdd->stpi_trsInputToBdd($nnbMenuElementID) . "'";
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
	
	
	public function stpi_selNbMenuElementID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		foreach ($arrLang as $strLg)	
		{
			$SQL = "SELECT nbMenuElementLgID";
			$SQL .= " FROM stpi_menu_MenuElement_Lg";
			$SQL .= " WHERE nbMenuElementID='" . $this->nbMenuElementID . "'";
			$SQL .= " AND strLg = '" . $strLg . "'";
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbMenuElementLgID"];
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
	
	public function stpi_selNbMenuElementIDLG()
	{
		$SQL = "SELECT nbMenuElementLgID";
		$SQL .= " FROM stpi_menu_MenuElement_Lg";
		$SQL .= " WHERE nbMenuElementID='" . $this->nbMenuElementID . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbID = $row["nbMenuElementLgID"];
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
}
?>
