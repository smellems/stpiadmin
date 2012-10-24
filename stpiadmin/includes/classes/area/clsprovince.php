<?php
require_once(dirname(__FILE__) . "/clsprovincelg.php");

class clsprovince
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objProvinceLg;
	
	private $strProvinceID;
	private $strCountryID;
	private $nbTax;
	private $boolTaxTaxable;
	
	private $arrObjProvinceLg;

	public function __construct($nstrProvinceID = "", $nstrCountryID = "")
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtprovince");
		$this->objLang = new clslang();
		$this->objProvinceLg = new clsprovincelg();
		$this->arrObjProvinceLg = array();
				
		if ($nstrProvinceID == "" OR $nstrCountryID == "")
		{
			$this->strProvinceID = "";
			$this->strCountryID = "";
			$this->nbTax = 0;
			$this->boolTaxTaxable = 0;		
		}
		else
		{
			if(!$this->stpi_setStrID($nstrProvinceID, $nstrCountryID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkStrProvinceID($nstrProvinceID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrProvinceID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nstrProvinceID, "strProvinceID", "stpi_area_Province"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
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
	
	
	public function stpi_chkNbTax($nnbTax)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbTax))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtax") . "</span><br/>\n");
			return false;				
		}
		
		if (!is_numeric($nnbTax))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtax") . "</span><br/>\n");
			return false;
		}
		
		if ($nnbTax < 0 || $nnbTax > 100)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtax") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	public function stpi_chkBoolTaxTaxable($nboolTaxTaxable)
	{
		if ($nboolTaxTaxable != 0 && $nboolTaxTaxable != 1) 
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtaxtaxable") . "</span><br/>\n");
			return false;				
		}
		
		return true;
	}
	
	public function stpi_setStrID($nstrProvinceID, $nstrCountryID)
	{
		if (!$this->stpi_setStrProvinceID($nstrProvinceID))
		{
			return false;
		}
		if (!$this->stpi_setStrCountryID($nstrCountryID))
		{
			return false;
		}

		$SQL = "SELECT nbTax,";
		$SQL .= " boolTaxTaxable";
		$SQL .= " FROM stpi_area_Province";
		$SQL .= " WHERE strProvinceID = '" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
		$SQL .= " AND strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTax = $row["nbTax"];
				$this->boolTaxTaxable = $row["boolTaxTaxable"];
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
	
	public function stpi_setNbTax($nnbTax)
	{
		if (!$this->stpi_chkNbTax($nnbTax))
		{
			return false;				
		}
		
		$this->nbTax = $nnbTax;
		
		return true;
	}
	
	public function stpi_setBoolTaxTaxable($nboolTaxTaxable)
	{
		if (!$this->stpi_chkBoolTaxTaxable($nboolTaxTaxable))
		{
			return false;				
		}
		
		$this->boolTaxTaxable = $nboolTaxTaxable;
		
		return true;
	}
	
	public function stpi_setArrObjProvinceLgFromBdd()
	{
		if (!$this->objProvinceLg->stpi_setStrProvinceID($this->nbID))
		{
			return false;
		}
		if (!$arrStrProvinceID = $this->objProvinceLg->stpi_selStrProvinceID())
		{
			return false;
		}
		foreach ($arrStrProvinceID as $strLg => $strProvinceLgID)
		{
			if (!$this->arrObjProvinceLg[$strLg] = new clsprovincelg($strProvinceLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	
	public function stpi_setObjProvinceLgFromBdd()
	{
		$SQL = "SELECT nbProvinceLgID";
		$SQL .= " FROM stpi_area_Province_Lg";
		$SQL .= " WHERE strProvinceID = '" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
		$SQL .= " AND strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$row = mysql_fetch_assoc($result);
			if (!$this->objProvinceLg->stpi_setNbID($row["nbProvinceLgID"]))
			{
				return false;
			}
			mysql_free_result($result);
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . LG . ")</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_getStrProvinceID()
	{
		return $this->strProvinceID;
	}
	
	public function stpi_getStrCountryID()
	{
		return $this->strCountryID;
	}
	
	public function stpi_getNbTax()
	{
		return $this->nbTax;
	}
	
	public function stpi_getBoolTaxTaxable()
	{
		return $this->boolTaxTaxable;
	}
	
	public function stpi_getObjProvinceLg()
	{
		return $this->objProvinceLg;
	}
	
	public function stpi_getArrObjProvinceLg()
	{
		return $this->arrObjProvinceLg;
	}
	
		
	public function stpi_selAll()
	{
		$SQL = "SELECT stpi_area_Province.strProvinceID, stpi_area_Province.strCountryID";
		$SQL .= " FROM stpi_area_Province, stpi_area_Province_Lg";
		$SQL .= " WHERE stpi_area_Province.strProvinceID = stpi_area_Province_Lg.strProvinceID";
		$SQL .= " AND stpi_area_Province_Lg.strLg = '" . $this->objBdd->stpi_trsInputToBdd(LG) . "'";
		$SQL .= " ORDER BY stpi_area_Province.strCountryID, stpi_area_Province_Lg.strName";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["strProvinceID"] . "-" . $row["strCountryID"];
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