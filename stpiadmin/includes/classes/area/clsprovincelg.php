<?php
class clsprovincelg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $strProvinceID;
	private $strCountryID;
	private $strName;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtprovince");
		
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->strProvinceID = "";
			$this->strCountryID = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbProvinceLgID", "stpi_area_Province_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrProvinceID($nstrProvinceID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrProvinceID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprovince") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nstrProvinceID, "strProvinceID", "stpi_area_Province"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexistsprovince") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrCountryID($nStrCountryID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nStrCountryID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcountry") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nStrCountryID, "strCountryID", "stpi_area_Country"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexistscountry") . "</span><br/>\n");
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
		
		$SQL = "SELECT strProvinceID, strCountryID, strName, strLg";
		$SQL .= " FROM stpi_area_Province_Lg";
		$SQL .= " WHERE nbProvinceLgID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->strProvinceID = $row["strProvinceID"];
				$this->strCountryID = $row["strCountryID"];
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
	
	public function stpi_setStrProvinceID($nstrProvinceID)
	{
		if (!$this->stpi_chkStrProvinceID($nstrProvinceID))
		{
			return false;				
		}
		$this->strProvinceID = $nstrProvinceID;
		return true;
	}
	
	public function stpi_setStrCountryID($nstrCountryID)
	{
		if (!$this->stpi_chkStrCountryID($nstrCountryID))
		{
			return false;				
		}
		$this->strCountryID = $nstrCountryID;
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
	
	public function stpi_getStrProvinceID()
	{
		return $this->strProvinceID;
	}
	
	public function stpi_getStrCountryID()
	{
		return $this->strCountryID;
	}
	
	public function stpi_getStrName()
	{
		return $this->strName;
	}
	
	
	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	public function stpi_selStrID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		foreach ($arrLang as $strLg)	
		{
			$SQL = "SELECT nbProvinceLgID";
			$SQL .= " FROM stpi_area_Province_Lg";
			$SQL .= " WHERE strProvinceID='" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
			$SQL .= " AND strCountryID='" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
			$SQL .= " AND strLg = '" . $this->objBdd->stpi_trsInputToBdd($strLg) . "'";
			
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbProvinceLgID"];
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
	
	public function stpi_selStrIDLG()
	{
		$SQL = "SELECT nbProvinceLgID";
		$SQL .= " FROM stpi_area_Province_Lg";
		$SQL .= " WHERE strProvinceID = '" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
		$SQL .= " AND strCountryID='" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$strID = $row["nbProvinceLgID"];
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
		return $strID;
	}
}
?>