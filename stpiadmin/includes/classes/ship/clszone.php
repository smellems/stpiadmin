<?php
require_once(dirname(__FILE__) . "/clsunitrange.php");
require_once(dirname(__FILE__) . "/clszonelg.php");
require_once(dirname(__FILE__) . "/../area/clscountry.php");

class clszone
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objUnitRange;
	private $objZoneLg;
	private $objCountry;
	
	private $nbID;
	private $boolTaxable;
	private $nbDefaultUnitPrice;
	
	private $arrObjZoneLg;
	private $arrStrCountryID;
	private $arrStrProvinceID;
	
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtzone");
		$this->objLang = new clslang();
		$this->objZoneLg = new clszonelg();
		$this->objUnitRange = new clsunitrange();
		$this->objCountry = new clscountry();
		$this->arrObjZoneLg = array();
		$this->arrStrCountryID = array();
		$this->arrStrProvinceID = array();
		
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolTaxable = 0;
			$this->nbDefaultUnitPrice = 0;		
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
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbZoneID", "stpi_ship_Zone"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkBoolTaxable($nboolTaxable)
	{
		if ($nboolTaxable != 0 && $nboolTaxable != 1) 
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtaxable") . "</span><br/>\n");
			return false;				
		}
		
		return true;
	}
	
	
	public function stpi_chkNbDefaultUnitPrice($nnbDefaultUnitPrice)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbDefaultUnitPrice))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddefaultunitprice") . "</span><br/>\n");
			return false;				
		}
		
		if (!is_numeric($nnbDefaultUnitPrice))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddefaultunitprice") . "</span><br/>\n");
			return false;
		}
		
		if ($nnbDefaultUnitPrice < 0 || $nnbDefaultUnitPrice > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddefaultunitprice") . "</span><br/>\n");
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
		
		$SQL = "SELECT boolTaxable,";
		$SQL .= " nbDefaultUnitPrice";
		$SQL .= " FROM stpi_ship_Zone";
		$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->boolTaxable = $row["boolTaxable"];
				$this->nbDefaultUnitPrice = $row["nbDefaultUnitPrice"];
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
	
	
	public function stpi_setBoolTaxable($nboolTaxable)
	{
		if (!$this->stpi_chkBoolTaxable($nboolTaxable))
		{
			return false;				
		}
		
		$this->boolTaxable = $nboolTaxable;
		
		return true;
	}
	
	
	public function stpi_setNbDefaultUnitPrice($nnbDefaultUnitPrice)
	{
		if (!$this->stpi_chkNbDefaultUnitPrice($nnbDefaultUnitPrice))
		{
			return false;				
		}
		
		$this->nbDefaultUnitPrice = $nnbDefaultUnitPrice;
		
		return true;
	}
	
	
	public function stpi_setArrObjZoneLgFromBdd()
	{
		if (!$this->objZoneLg->stpi_setNbZoneID($this->nbID))
		{
			return false;
		}
		if (!$arrNbZoneID = $this->objZoneLg->stpi_selNbZoneID())
		{
			return false;
		}
		foreach ($arrNbZoneID as $strLg => $nbZoneLgID)
		{
			if (!$this->arrObjZoneLg[$strLg] = new clszonelg($nbZoneLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	
	public function stpi_setArrStrCountryIDFromBdd()
	{
		if (!$this->arrStrCountryID = $this->stpi_selStrCountryID())
		{
			return false;
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
	
	
	public function stpi_setObjZoneLgFromBdd()
	{
		$SQL = "SELECT nbZoneLgID";
		$SQL .= " FROM stpi_ship_Zone_Lg";
		$SQL .= " WHERE nbZoneID = '" . $this->nbID . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$row = mysql_fetch_assoc($result);
			if (!$this->objZoneLg->stpi_setNbID($row["nbZoneLgID"]))
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
	
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	
	public function stpi_getBoolTaxable()
	{
		return $this->boolTaxable;
	}
	
	
	public function stpi_getNbDefaultUnitPrice()
	{
		return $this->nbDefaultUnitPrice;
	}
	
	public function stpi_getObjZoneLg()
	{
		return $this->objZoneLg;
	}
	
	
	public function stpi_getArrObjZoneLg()
	{
		return $this->arrObjZoneLg;
	}
	
	
	public function stpi_getObjUnitRange()
	{
		return $this->objUnitRange;
	}
	
	
	public function stpi_getObjCountry()
	{
		return $this->objCountry;
	}
	
	
	public function stpi_getNbPrixShipping($nnbUnits, $nstrCountryID, $nstrProvinceID = "")
	{
		$nbPrixShipping = 0;
		
		if ($nnbUnits == 0)
		{
			return $nbPrixShipping;
		}
		
		if (empty($nstrProvinceID))
		{
			$SQL = "SELECT nbZoneID";
			$SQL .= " FROM stpi_ship_Zone_Country_Province";
			$SQL .= " WHERE strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($nstrCountryID) . "'";
			$SQL .= " AND strProvinceID = ''";
		}
		else
		{
			$SQL = "SELECT nbZoneID";
			$SQL .= " FROM stpi_ship_Zone_Country_Province";
			$SQL .= " WHERE strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($nstrCountryID) . "'";
			$SQL .= " AND strProvinceID = '" . $this->objBdd->stpi_trsInputToBdd($nstrProvinceID) . "'";
		}
			
		$arrID = array();
			
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbZoneID = $row["nbZoneID"];
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("notshippable") . "</span><br/>\n");
				return false;
			}
			mysql_free_result($result);
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("notshippable") . "</span><br/>\n");
			return false;
		}
		
		if (!$this->stpi_setNbID($nbZoneID))
		{
			return false;
		}
		
		$nbUnits = 0;
		$boolFoundInRange = false;
		
		if (!$arrNbUnitRangeID = $this->objUnitRange->stpi_selAllUnitRange())
		{
			return false;
		}
		

		foreach ($arrNbUnitRangeID as $nbUnitRangeID)
		{
			if (!$this->objUnitRange->stpi_setNbID($nbUnitRangeID, $nbZoneID))
			{
				return false;
			}
			
			$nbPrixShipping += $this->objUnitRange->stpi_getNbPrix();
			
			if ($nbUnits <= $nnbUnits && $nnbUnits <= $nbUnitRangeID) 
			{
				$boolFoundInRange = true;
				break;
			}

			$nbUnits = $nbUnitRangeID + 1;
		}
		
		if (!$boolFoundInRange)
		{
						
			$nbPrixShipping += $this->stpi_getNbDefaultUnitPrice();
			while ($nbUnits < $nnbUnits)
			{
				$nbPrixShipping += $this->stpi_getNbDefaultUnitPrice();
				$nbUnits++;
			}
		}
		
		return $nbPrixShipping;		
	}
	
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_ship_Zone";
		$SQL .= " (boolTaxable,";
		$SQL .= " nbDefaultUnitPrice)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->boolTaxable) . " ',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->nbDefaultUnitPrice) . "')";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
	}
	
	
	public function stpi_insertCountryProvince($nnbZoneID, $nstrCountryID, $nstrProvinceID = "")
	{
		if (!$this->stpi_chkNbID($nnbZoneID))
		{
			return false;
		}
		
		if (!$this->objCountry->stpi_setStrID($nstrCountryID))
		{
			return false;
		}
		
		if (!empty($nstrProvinceID))
		{
			$objProvince = $this->objCountry->stpi_getObjProvince();
			if (!$objProvince->stpi_chkStrProvinceID($nstrProvinceID))
			{
				return false;
			}
		}
		
		if (!$this->objCountry->stpi_chkProvinceInCountry($nstrProvinceID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
		
		$arrValeur = array();
		$arrValeur[] = $nstrCountryID;
		$arrValeur[] = $nstrProvinceID;
		
		$arrChamp = array();
		$arrChamp[] = "strCountryID";
		$arrChamp[] = "strProvinceID";
		
		if ($this->objBdd->stpi_chkArrExists($arrValeur, $arrChamp, "stpi_ship_Zone_Country_Province"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("areainzoneexists") . "</span><br/>\n");
			return false;
		}
		
		if (empty($nstrProvinceID))
		{
			$SQL = "INSERT INTO stpi_ship_Zone_Country_Province";
			$SQL .= " (nbZoneID,";
			$SQL .= " strCountryID)";
			$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($nnbZoneID) . "',";
			$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($nstrCountryID) . "')";
		}
		else
		{
			$SQL = "INSERT INTO stpi_ship_Zone_Country_Province";
			$SQL .= " (nbZoneID,";
			$SQL .= " strCountryID,";
			$SQL .= " strProvinceID)";
			$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($nnbZoneID) . "',";
			$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($nstrCountryID) . "',";
			$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($nstrProvinceID) . "')";
		}
		
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
		$SQL = "UPDATE stpi_ship_Zone";
		$SQL .= " SET boolTaxable = '" . $this->objBdd->stpi_trsInputToBdd($this->boolTaxable) . "'";
		$SQL .= " , nbDefaultUnitPrice = '" . $this->objBdd->stpi_trsInputToBdd($this->nbDefaultUnitPrice) . "'";
		$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	
	public function stpi_delete($nnbZoneID)
	{
		if (!$this->stpi_chkNbID($nnbZoneID))
		{
			return false;				
		}
		
		$SQL = "DELETE FROM stpi_ship_Zone";
		$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($nnbZoneID) . "'";
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		
		if ($this->objBdd->stpi_chkExists($nnbZoneID, "nbZoneID", "stpi_ship_Zone_Country_Province"))
		{
			$SQL = "DELETE FROM stpi_ship_Zone_Country_Province";
			$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($nnbZoneID) . "'";
			if (!$this->objBdd->stpi_delete($SQL))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
				return false;
			}
		}
		
		return true;
	}
	
	
	public function stpi_deleteCountryProvince($nnbZoneID, $nstrCountryID, $nstrProvinceID = "")
	{
		if (!$this->stpi_chkNbID($nnbZoneID))
		{
			return false;				
		}
		
		if (!$this->objCountry->stpi_chkStrID($nstrCountryID))
		{
			return false;
		}
		
		if (!empty($nstrProvinceID))
		{
			$objProvince = $this->objCountry->stpi_getObjProvince();
			
			if (!$objProvince->stpi_chkStrProvinceID($nstrProvinceID))
			{
				return false;
			}
			if (!$objProvince->stpi_chkStrCountryID($nstrCountryID))
			{
				return false;
			}
		}
		
		if (empty($nstrProvinceID))
		{
			$SQL = "DELETE FROM stpi_ship_Zone_Country_Province";
			$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($nnbZoneID) . "'";
			$SQL .= " AND strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($nstrCountryID) . "'";
			$SQL .= " AND strProvinceID = ''";
		}
		else
		{
			$SQL = "DELETE FROM stpi_ship_Zone_Country_Province";
			$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($nnbZoneID) . "'";
			$SQL .= " AND strCountryID = '" . $this->objBdd->stpi_trsInputToBdd($nstrCountryID) . "'";
			$SQL .= " AND strProvinceID = '" . $this->objBdd->stpi_trsInputToBdd($nstrProvinceID) . "'";
		}
		
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}

	
	public function stpi_affAllZone()
	{
		if (!$arrZoneID = $this->stpi_selAll())
		{
			return false;
		}
		
		print("<p>\n");
		foreach ($arrZoneID as $nbZoneID)
		{
			if (!$this->stpi_setNbID($nbZoneID))
			{
				return false;
			}			
			if (!$this->stpi_setObjZoneLgFromBdd())
			{
				return false;
			}
			
			print("<a href=\"./shipzone.php?l=" . LG . "&amp;nbZoneID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objZoneLg->stpi_getStrName()) . "</a>\n");
			
			$strDesc = $this->objZoneLg->stpi_getStrDesc();
			if (!empty($strDesc))
			{
				print(": " . $this->objBdd->stpi_trsBddToHTML($strDesc) . "\n");
			}
			print("<br/>\n");
		}
		print("</p>\n");
		
		return true;
	}
	
	
	public function stpi_affAllCountryProvince()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (!$this->stpi_setArrStrCountryIDFromBdd())
		{
			$this->arrStrCountryID = array();
		}
		if (!$this->stpi_setArrStrProvinceIDFromBdd())
		{
			$this->arrStrProvinceID = array();
		}
		
		print("<p>\n");
		for ($i = 0; $i < count($this->arrStrCountryID); $i++)
		{
			$this->objCountry->stpi_setStrID($this->arrStrCountryID[$i]);
			$this->objCountry->stpi_setObjCountryLgFromBdd();
			$objCountryLg = $this->objCountry->stpi_getObjCountryLg();
			
			print($objCountryLg->stpi_getStrName());
			
			if (!empty($this->arrStrProvinceID[$i]))
			{
				$objProvince = $this->objCountry->stpi_getObjProvince();
				$objProvince->stpi_setStrID($this->arrStrProvinceID[$i], $this->arrStrCountryID[$i]);
				$objProvince->stpi_setObjProvinceLgFromBdd();
				$objProvinceLg = $objProvince->stpi_getObjProvinceLg();							
				print(" : " . $objProvinceLg->stpi_getStrName());					
			}
			
			print("&nbsp;&nbsp;<input type=\"button\" onclick=\"stpi_delCountryProvince(" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . ", '" . $this->objBdd->stpi_trsBddToHTML($this->objCountry->stpi_getStrID()) . "'");
			if (!empty($this->arrStrProvinceID[$i]))
			{
				print(", '" . $this->objBdd->stpi_trsBddToHTML($objProvince->stpi_getStrProvinceID()) . "'");
			}
			print(")\" value=\"" . $this->objTexte->stpi_getArrTxt("buttondelete") . "\"/><br/>\n");
		}
		
		print("<span id=\"stpi_delCountryProvince\"></span><br/>\n");	
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addShipZone(narrUnitRangeID)
		{
			xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_shipzoneAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_Addshipzone").style.visibility = "hidden";
			
			if (document.getElementById("boolShipZoneTaxable").checked)
			{
				var strParam = "boolTaxable=1";
			}
			else
			{
				var strParam = "boolTaxable=0";
			}
			
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strShipZoneName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strShipZoneDesc" + strLg[i]).value);
			}
			
			strParam = strParam + "&nbDefaultUnitPrice=" + encodeURIComponent(document.getElementById("nbShipZoneDefaultUnitPrice").value);
			
			for (i in narrUnitRangeID)
			{
				if (narrUnitRangeID[i] != 0)
				{
					strParam = strParam + "&nbPrix" + narrUnitRangeID[i] + "=" + encodeURIComponent(document.getElementById("nbShipZoneUnitRangePrix" + narrUnitRangeID[i]).value);
				}
			}
			
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./shipzone.php?l=" + "<?php print(LG) ?>" + "&nbZoneID=";
		  				var nbZoneIDIndex = xmlHttp.responseText.indexOf("nbZoneID") + 9;
		  				var nbZoneIDLen = xmlHttp.responseText.length - nbZoneIDIndex;
		  				var nbZoneID = xmlHttp.responseText.substr(nbZoneIDIndex, nbZoneIDLen);
		  				strUrlRedirect = strUrlRedirect + nbZoneID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Addshipzone").style.visibility = "visible";
			  			document.getElementById("stpi_shipzoneAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "shipzoneadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsAddCountryProvince()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addCountryProvince()
		{
			xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_addCountryProvince").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			strParam = "nbZoneID=" + document.getElementById("nbZoneID").value;
			
			strParam = strParam + "&strCountryID=" + document.getElementById("strCountryID").value;
			
			try
			{
				strParam = strParam + "&strProvinceID=" + document.getElementById("strProvinceID").value;
				strParam = strParam + "&sid=" + Math.random();
			}
			catch(e)
			{
				strParam = strParam + "&sid=" + Math.random();
			}			
			
			strParam = encodeURI(strParam);
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./shipzone.php?l=" + "<?php print(LG); ?>" + "&nbZoneID=";
		  				var nbZoneID = document.getElementById("nbZoneID").value;
		  				strUrlRedirect = strUrlRedirect + nbZoneID;
		  				window.location = strUrlRedirect;
		  			}
		  			document.getElementById("stpi_addCountryProvince").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("POST", "shipcountryprovinceadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affAdd()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		foreach ($arrLang as $strLg)	
		{
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"50\" size=\"30\" id=\"strShipZoneName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("optionel") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strShipZoneDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		
		print("<p>\n");
		print("<input type=\"checkbox\" id=\"boolShipZoneTaxable\" value=\"\" /> " . $this->objTexte->stpi_getArrTxt("taxable") . "<br/>\n");
		print("</p>\n");
		
		
		if (!$arrUnitRangeID = $this->objUnitRange->stpi_selAllUnitRange())
		{
			$arrUnitRangeID = array();	
		}
		$strUnitRangeID = "";
		$nbUnits = 1;
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("unitrangeprice") . "<br/>\n");
		foreach ($arrUnitRangeID as $nbUnitRangeID)
		{					
			$strUnitRangeID .= "," . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID);
			print($nbUnits . " - " . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID) . " " . $this->objTexte->stpi_getArrTxt("unit"));
			print("&nbsp;&nbsp;<input type=\"text\" maxlength=\"12\" size=\"13\" id=\"nbShipZoneUnitRangePrix" . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID) . "\" value=\"0.0\" /><br/>\n");
			$nbUnits = $nbUnitRangeID + 1;
		}
		print($nbUnits . " " . $this->objTexte->stpi_getArrTxt("reste"));
		print("&nbsp;&nbsp;<input type=\"text\" maxlength=\"12\" size=\"13\" id=\"nbShipZoneDefaultUnitPrice\" value=\"0.0\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_shipzoneAdd\"></span><br/>\n");				
		print("<input id=\"stpi_Addshipzone\" type=\"button\" onclick=\"stpi_addShipZone(Array(0" . $strUnitRangeID . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonadd") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affAddCountryProvince()
	{
		print("<p>\n");
		
		$this->objCountry->stpi_affJsSelectCountry();
		
		$this->objCountry->stpi_affSelectCountry();
		
		print("<span id=\"stpi_addCountryProvince\"></span><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_addCountryProvince()\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonadd") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ZoneChangeToEditable(narrUnitRangeID)
		{
			for (i in strLg)
			{
				document.getElementById("strShipZoneName" + strLg[i]).disabled = false;
				document.getElementById("strShipZoneDesc" + strLg[i]).disabled = false;
			}
			
			document.getElementById("boolShipZoneTaxable").disabled = false;
			
			for (i in narrUnitRangeID)
			{
				if (narrUnitRangeID[i] != 0)
				{
					document.getElementById("nbShipZoneUnitRangePrix" + narrUnitRangeID[i]).disabled = false;
				}
			}
			
			document.getElementById("nbShipZoneDefaultUnitPrice").disabled = false;
			
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		
		
		function stpi_editShipZone(narrUnitRangeID)
		{
			xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_Save").style.visibility = "hidden";
			
			if (document.getElementById("boolShipZoneTaxable").checked)
			{
				var strParam = "boolTaxable=1";
			}
			else
			{
				var strParam = "boolTaxable=0";
			}
			
			strParam = strParam + "&nbZoneID=" + encodeURIComponent(document.getElementById("nbZoneID").value);
			
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strShipZoneName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strShipZoneDesc" + strLg[i]).value);
			}
			
			strParam = strParam + "&nbDefaultUnitPrice=" + encodeURIComponent(document.getElementById("nbShipZoneDefaultUnitPrice").value);
			
			for (i in narrUnitRangeID)
			{
				if (narrUnitRangeID[i] != 0)
				{
					strParam = strParam + "&nbPrix" + narrUnitRangeID[i] + "=" + encodeURIComponent(document.getElementById("nbShipZoneUnitRangePrix" + narrUnitRangeID[i]).value);
				}
			}
			
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./shipzone.php?l=" + "<?php print(LG); ?>" + "&nbZoneID=";
		  				var nbZoneIDIndex = xmlHttp.responseText.indexOf("nbZoneID") + 9;
		  				var nbZoneIDLen = xmlHttp.responseText.length - nbZoneIDIndex;
		  				var nbZoneID = xmlHttp.responseText.substr(nbZoneIDIndex, nbZoneIDLen);
		  				strUrlRedirect = strUrlRedirect + nbZoneID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			
			xmlHttp.open("POST", "shipzoneedit.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affEdit()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		print("<input type=\"hidden\" id=\"nbZoneID\" value=\"" . $this->nbID . "\" />\n");
		
		$this->stpi_setArrObjZoneLgFromBdd();

		foreach ($this->arrObjZoneLg as $strLg => $objZoneLg)	
		{				
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"30\" id=\"strShipZoneName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($objZoneLg->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");

			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("optionel") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strShipZoneDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($objZoneLg->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		
		print("<p>\n");
		if ($this->boolTaxable == 1)
		{
			print("<input disabled=\"disabled\" type=\"checkbox\" checked=\"checked\" id=\"boolShipZoneTaxable\" value=\"\" /> " . $this->objTexte->stpi_getArrTxt("taxable") . "<br/>\n");							
		}
		elseif ($this->boolTaxable == 0)
		{
			print("<input disabled=\"disabled\" type=\"checkbox\" id=\"boolShipZoneTaxable\" value=\"\" /> " . $this->objTexte->stpi_getArrTxt("taxable") . "<br/>\n");							
		}
		print("</p>\n");
		
		if (!$arrUnitRangeID = $this->objUnitRange->stpi_selAllUnitRange())
		{
			$arrUnitRangeID = array();
		}
		$strUnitRangeID = "";
		$nbUnits = 1;
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("unitrangeprice") . "<br/>\n");
		foreach ($arrUnitRangeID as $nbUnitRangeID)
		{
			if ($this->objUnitRange->stpi_setNbID($nbUnitRangeID, $this->nbID))
			{
				$strUnitRangeID .= "," . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID);
				print($nbUnits . " - " . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID) . " " . $this->objTexte->stpi_getArrTxt("unitrangeprice"));
				print("&nbsp;&nbsp;<input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" id=\"nbShipZoneUnitRangePrix" . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objUnitRange->stpi_getNbPrix()) . "\" /><br/>\n");
				$nbUnits = $nbUnitRangeID + 1;
			}
		}
		print($nbUnits . " " . $this->objTexte->stpi_getArrTxt("reste"));
		print("&nbsp;&nbsp;<input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" id=\"nbShipZoneDefaultUnitPrice\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbDefaultUnitPrice) . "\" /><br/>\n");
		print("</p>\n");
	
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ZoneChangeToEditable(Array(0" . $strUnitRangeID . "))\" value=\"" .$this->objTexte->stpi_getArrTxt("buttonedit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editShipZone(Array(0" . $strUnitRangeID . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonsave") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delShipZone()\" value=\"" . $this->objTexte->stpi_getArrTxt("buttondelete") . "\" /><br/>\n");
		print("</p>\n");

		return true;
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delShipZone()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "shipzonedel.php?nbZoneID=" + document.getElementById("nbZoneID").value;
			strUrl = strUrl + "&nbConfirmed=0&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		
		
		function stpi_delConfirmedShipZone()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "shipzonedel.php?nbZoneID=" + document.getElementById("nbZoneID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./ships.php?l=" + "<?php print(LG); ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		
		
		function stpi_ClearMessage()
		{
		  	document.getElementById("stpi_messages").innerHTML = "";
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsDeleteCountryProvince()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delCountryProvince(nbZoneID, strCountryID, strProvinceID)
		{
		  	xmlHttp = stpi_XmlHttpObject();		  	
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_delCountryProvince").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "shipcountryprovincedel.php?nbZoneID=" + nbZoneID;
			strUrl = strUrl + "&strCountryID=" + strCountryID;
			if (strProvinceID !== undefined)
			{
				strUrl = strUrl + "&strProvinceID=" + strProvinceID;
			}
			strUrl = strUrl + "&nbConfirmed=0&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_delCountryProvince").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		function stpi_delConfirmedCountryProvince(nbZoneID, strCountryID, strProvinceID)
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_delCountryProvince").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "shipcountryprovincedel.php?nbZoneID=" + nbZoneID;
			strUrl = strUrl + "&strCountryID=" + strCountryID;
			if (strProvinceID !== undefined)
			{
				strUrl = strUrl + "&strProvinceID=" + strProvinceID;
			}
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./shipzone.php?nbZoneID=" + document.getElementById("nbZoneID").value + "&l=" + "<?php print(LG); ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			document.getElementById("stpi_delCountryProvince").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		function stpi_ClearMessageCountryProvince()
		{
		  	document.getElementById("stpi_delCountryProvince").innerHTML = "";
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirm") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_delConfirmedShipZone()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	
	public function stpi_affDeleteCountryProvince($nnbZoneID, $nstrCountryID, $nstrProvinceID = "")
	{		
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirmcountryprovince") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_delConfirmedCountryProvince(" . $nnbZoneID . ", '" . $nstrCountryID . "'");
		if (!empty($nstrProvinceID))
		{
			print(", '" . $nstrProvinceID . "'");
		}
		print(")\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\"/>&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessageCountryProvince()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	
	public function stpi_selAll()
	{
		$SQL = "SELECT nbZoneID";
		$SQL .= " FROM stpi_ship_Zone";
		$SQL .= " ORDER BY nbZoneID";
		
		$arrID = array();
	
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbZoneID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selStrCountryID()
	{
		$SQL = "SELECT strCountryID";
		$SQL .= " FROM stpi_ship_Zone_Country_Province";
		$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
			
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
		$SQL = "SELECT strProvinceID";
		$SQL .= " FROM stpi_ship_Zone_Country_Province";
		$SQL .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
			
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