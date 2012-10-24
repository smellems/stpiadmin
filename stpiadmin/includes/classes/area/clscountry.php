<?php
require_once(dirname(__FILE__) . "/clsprovince.php");
require_once(dirname(__FILE__) . "/clscountrylg.php");

class clscountry
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objProvince;
	private $objCountryLg;
	
	private $strID;
	private $nbTax;
	
	private $arrObjCountryLg;
	private $arrStrProvinceID;
	
	
	public function __construct($nstrID = "")
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcountry");
		$this->objLang = new clslang();
		$this->objProvince = new clsprovince();
		$this->objCountryLg = new clscountrylg();
		$this->arrObjCountryLg = array();
		$this->arrStrProvinceID = array();
		
		if ($nstrID == "")
		{
			$this->strID = "";
			$this->nbTax = 0;	
		}
		else
		{
			if(!$this->stpi_setStrID($nstrID))
			{
				return false;
			}
		}
		return true;
	}
	
	
	public function stpi_chkStrID($nstrID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nstrID, "strCountryID", "stpi_area_Country"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
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
	
	
	public function stpi_chkProvinceInCountry($nstrProvinceID)
	{
		if (empty($this->strID))
		{
			return false;
		}
		
		if ($this->stpi_setArrStrProvinceIDFromBdd())
		{
			if (empty($nstrProvinceID))
			{
				return false;
			}
			if (!in_array($nstrProvinceID, $this->arrStrProvinceID))
			{
				return false;
			}			
		}
		else
		{
			if (!empty($nstrProvinceID))
			{
				return false;
			}			
		}
		
		return true;
	}
	
		
	public function stpi_setStrID($nstrID)
	{
		if (!$this->stpi_chkStrID($nstrID))
		{
			return false;				
		}
		
		$this->strID = $nstrID;
		
		$SQL = "SELECT nbTax";
		$SQL .= " FROM stpi_area_Country";
		$SQL .= " WHERE strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($this->strID) . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTax = $row["nbTax"];
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
	
	
	public function stpi_setnbTax($nnbTax)
	{
		if (!$this->stpi_chkNbTax($nnbTax))
		{
			return false;		
		}
		
		$this->nbTax = $nbTax;
		
		return true;
	}
	
	
	public function stpi_setArrObjCountryLgFromBdd()
	{
		if (!$this->objCountryLg->stpi_setStrCountryID($this->strID))
		{
			return false;
		}
		if (!$arrStrCountryID = $this->objCountryLg->stpi_selStrCountryID())
		{
			return false;
		}
		foreach ($arrStrCountryID as $strLg => $strCountryLgID)
		{
			if (!$this->arrObjCountryLg[$strLg] = new clscountrylg($strCountryLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	
	public function stpi_setArrStrProvinceIDFromBdd()
	{
		if (!$this->arrStrProvinceID = $this->stpi_selStrProvinceID())
		{
			return false;
		}
		return true;
	}
	
	
	public function stpi_setArrStrProvinceIDShippableFromBdd()
	{
		if (!$this->arrStrProvinceID = $this->stpi_selStrProvinceIDShippable())
		{
			return false;
		}
		return true;
	}
	
		
	public function stpi_setObjCountryLgFromBdd()
	{
		$SQL = "SELECT nbCountryLgID";
		$SQL .= " FROM stpi_area_Country_Lg";
		$SQL .= " WHERE strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($this->strID) . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$row = mysql_fetch_assoc($result);
			if (!$this->objCountryLg->stpi_setNbID($row["nbCountryLgID"]))
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
	
	
	public function stpi_getstrID()
	{
		return $this->strID;
	}
	
	
	public function stpi_getNbTax()
	{
		return $this->nbTax;
	}
	
	
	public function stpi_getObjCountryLg()
	{
		return $this->objCountryLg;
	}
	
	
	public function stpi_getArrObjCountryLg()
	{
		return $this->arrObjCountryLg;
	}
	

	public function stpi_getObjProvince()
	{
		return $this->objProvince;
	}
	
	
	public function stpi_affJsSelectCountry()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affProvince(nstrCountryID)
		{
			if (nstrCountryID.length == 0)
			{ 
				document.getElementById("stpi_affProvince").innerHTML = "";
		  		return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affProvince").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "areaprovinceselectaff.php?l=" + "<?php print(LG); ?>" + "&strCountryID=" + nstrCountryID + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affProvince").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsSelectCountryPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affProvince(nstrCountryID)
		{
			if (nstrCountryID.length == 0)
			{ 
				document.getElementById("stpi_affProvince").innerHTML = "";
		  		return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affProvince").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "areaprovinceselectaffpublic.php?l=" + "<?php print(LG); ?>" + "&strCountryID=" + nstrCountryID + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affProvince").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affSelectCountry($nstrCountryIDSelected = "", $nstrProvinceIDSelected = "", $nboolTexte = 1, $nboolDisabled = 0)
	{
		if (!$arrStrCountryID = $this->stpi_selAll())
		{
			$arrStrCountryID = array();
		}
		
		if ($nboolTexte == 1)
		{
			print($this->objTexte->stpi_getArrTxt("selectcountry") . "<br/>\n");
		}
		print("<select ");
		if ($nboolDisabled == 1)
		{
			print("disabled=\"disabled\"");
		}
		print(" onchange=\"stpi_affProvince(this.value)\" id=\"strCountryID\">\n");
		print("<option value=\"\"></option>\n");
		foreach ($arrStrCountryID as $strCountryID)
		{
			if (!$this->stpi_setStrID($strCountryID))
			{
				return false;
			}			
			if (!$this->stpi_setObjCountryLgFromBdd())
			{
				return false;
			}
			
			if ($nstrCountryIDSelected != "" && $nstrCountryIDSelected == $this->strID)
			{
				print("<option selected=\"selected\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objCountryLg->stpi_getStrName()) . "</option>\n");
			}
			else
			{
				print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objCountryLg->stpi_getStrName()) . "</option>\n");
			}
		}
		print("</select><br/>\n");
		print("<span id=\"stpi_affProvince\">\n");
		if ($nstrProvinceIDSelected != "")
		{
			$this->stpi_setStrID($nstrCountryIDSelected);
			$this->stpi_affSelectProvince($nstrProvinceIDSelected, 1, $nboolDisabled);
		}
		print("</span><br/>\n");
		
		return true;
	}
	
	
	public function stpi_affSelectCountryShippable($nstrCountryIDSelected = "", $nstrProvinceIDSelected = "", $nboolTexte = 1, $nboolDisabled = 0)
	{
		if (!$arrStrCountryID = $this->stpi_selAllShippable())
		{
			$arrStrCountryID = array();
		}
		
		if ($nboolTexte == 1)
		{
			print($this->objTexte->stpi_getArrTxt("selectcountry") . "<br/>\n");
		}
		print("<select ");
		if ($nboolDisabled == 1)
		{
			print("disabled=\"disabled\"");
		}
		print(" onchange=\"stpi_affProvince(this.value)\" id=\"strCountryID\">\n");
		print("<option value=\"\"></option>\n");
		foreach ($arrStrCountryID as $strCountryID)
		{
			if (!$this->stpi_setStrID($strCountryID))
			{
				return false;
			}			
			if (!$this->stpi_setObjCountryLgFromBdd())
			{
				return false;
			}
			
			if ($nstrCountryIDSelected != "" && $nstrCountryIDSelected == $this->strID)
			{
				print("<option selected=\"selected\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objCountryLg->stpi_getStrName()) . "</option>\n");
			}
			else
			{
				print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objCountryLg->stpi_getStrName()) . "</option>\n");
			}
		}
		print("</select><br/>\n");
		print("<span id=\"stpi_affProvince\">\n");
		if ($nstrProvinceIDSelected != "")
		{
			$this->stpi_setStrID($nstrCountryIDSelected);
			$this->stpi_affSelectProvinceShippable($nstrProvinceIDSelected, 1, $nboolDisabled);
		}
		print("</span><br/>\n");
		
		return true;
	}
	
	
	public function stpi_affSelectProvince($nstrProvinceIDSelected = "", $nboolTexte = 1, $nboolDisabled = 0)
	{
		if (!$this->stpi_setArrStrProvinceIDFromBdd())
		{
			$this->arrStrProvinceID = array();
			return false;
		}

		if ($nboolTexte == 1)
		{
			print($this->objTexte->stpi_getArrTxt("selectprovince") . "<br/>\n");
		}
		print("<select ");
		if ($nboolDisabled == 1)
		{
			print("disabled=\"disabled\"");
		}
		print(" id=\"strProvinceID\">\n");
		print("<option value=\"\"></option>\n");
		foreach ($this->arrStrProvinceID as $strProvinceID)
		{
			$this->objProvince->stpi_setStrID($strProvinceID, $this->strID);
			$this->objProvince->stpi_setObjProvinceLgFromBdd();
			$objProvinceLg = $this->objProvince->stpi_getObjProvinceLg();
			
			if ($nstrProvinceIDSelected != "" && $nstrProvinceIDSelected == $this->objProvince->stpi_getStrProvinceID())
			{
				print("<option selected=\"selected\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objProvince->stpi_getStrProvinceID()) . "\">" . $this->objBdd->stpi_trsBddToHTML($objProvinceLg->stpi_getStrName()) . "</option>\n");
			}
			else
			{
				print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objProvince->stpi_getStrProvinceID()) . "\">" . $this->objBdd->stpi_trsBddToHTML($objProvinceLg->stpi_getStrName()) . "</option>\n");
			}
		}
		print("</select><br/>\n");
		
		return true;
	}
	
	
	public function stpi_affSelectProvinceShippable($nstrProvinceIDSelected = "", $nboolTexte = 1, $nboolDisabled = 0)
	{
		if (!$this->stpi_setArrStrProvinceIDShippableFromBdd())
		{
			$this->arrStrProvinceID = array();
			return false;
		}

		if ($nboolTexte == 1)
		{
			print($this->objTexte->stpi_getArrTxt("selectprovince") . "<br/>\n");
		}
		print("<select ");
		if ($nboolDisabled == 1)
		{
			print("disabled=\"disabled\"");
		}
		print(" id=\"strProvinceID\">\n");
		print("<option value=\"\"></option>\n");
		foreach ($this->arrStrProvinceID as $strProvinceID)
		{
			$this->objProvince->stpi_setStrID($strProvinceID, $this->strID);
			$this->objProvince->stpi_setObjProvinceLgFromBdd();
			$objProvinceLg = $this->objProvince->stpi_getObjProvinceLg();
			
			if ($nstrProvinceIDSelected != "" && $nstrProvinceIDSelected == $this->objProvince->stpi_getStrProvinceID())
			{
				print("<option selected=\"selected\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objProvince->stpi_getStrProvinceID()) . "\">" . $this->objBdd->stpi_trsBddToHTML($objProvinceLg->stpi_getStrName()) . "</option>\n");
			}
			else
			{
				print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objProvince->stpi_getStrProvinceID()) . "\">" . $this->objBdd->stpi_trsBddToHTML($objProvinceLg->stpi_getStrName()) . "</option>\n");
			}
		}
		print("</select><br/>\n");
		
		return true;
	}
	
	
	public function stpi_selAll()
	{
		$SQL = "SELECT stpi_area_Country.strCountryID";
		$SQL .= " FROM stpi_area_Country, stpi_area_Country_Lg";
		$SQL .= " WHERE stpi_area_Country.strCountryID = stpi_area_Country_Lg.strCountryID";
		$SQL .= " AND stpi_area_Country_Lg.strLg = '" . $this->objBdd->stpi_trsInputToBdd(LG) . "'";
		$SQL .= " ORDER BY stpi_area_Country_Lg.strName";
		
		$arrID = array();
	
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["strCountryID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selAllShippable()
	{
		$SQL = "SELECT DISTINCT stpi_area_Country.strCountryID";
		$SQL .= " FROM stpi_ship_Zone_Country_Province, stpi_area_Country, stpi_area_Country_Lg";
		$SQL .= " WHERE stpi_area_Country.strCountryID = stpi_area_Country_Lg.strCountryID";
		$SQL .= " AND stpi_ship_Zone_Country_Province.strCountryID = stpi_area_Country.strCountryID";
		$SQL .= " AND stpi_area_Country_Lg.strLg = '" . $this->objBdd->stpi_trsInputToBdd(LG) . "'";
		$SQL .= " ORDER BY stpi_area_Country_Lg.strName";
		
		$arrID = array();
	
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["strCountryID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selStrProvinceID()
	{
		$SQL = "SELECT stpi_area_Province.strProvinceID";
		$SQL .= " FROM stpi_area_Province, stpi_area_Province_Lg";
		$SQL .= " WHERE stpi_area_Province.strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($this->strID) . "'";
		$SQL .= " AND stpi_area_Province.strProvinceID = stpi_area_Province_Lg.strProvinceID";
		$SQL .= " AND stpi_area_Province_Lg.strLg = '" . $this->objBdd->stpi_trsInputToBdd(LG) . "'";
		$SQL .= " ORDER BY stpi_area_Province_Lg.strName";
			
		$arrID = array();
			
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["strProvinceID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selStrProvinceIDShippable()
	{
		$SQL = "SELECT DISTINCT stpi_area_Province.strProvinceID";
		$SQL .= " FROM stpi_ship_Zone_Country_Province, stpi_area_Province, stpi_area_Province_Lg";
		$SQL .= " WHERE stpi_area_Province.strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($this->strID) . "'";
		$SQL .= " AND stpi_area_Province.strProvinceID = stpi_area_Province_Lg.strProvinceID";
		$SQL .= " AND stpi_ship_Zone_Country_Province.strProvinceID = stpi_area_Province.strProvinceID";
		$SQL .= " AND stpi_area_Province_Lg.strLg = '" . $this->objBdd->stpi_trsInputToBdd(LG) . "'";
		$SQL .= " ORDER BY stpi_area_Province_Lg.strName";
			
		$arrID = array();
			
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["strProvinceID"];
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