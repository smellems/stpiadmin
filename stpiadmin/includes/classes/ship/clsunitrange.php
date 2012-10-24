<?php

class clsunitrange
{
	private $objBdd;
	private $objTexte;
	
	private $nbUnitRangeID;
	private $nbZoneID;
	private $nbPrix;
	
	public function __construct($nnbUnitRangeID = 0, $nnbZoneID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtunitrange");
		
		if ($nnbUnitRangeID == 0  && $nnbZoneID == 0)
		{
			$nbPrix = 0;
		}
		else
		{
			if (!$this->stpi_setNbID($nnbUnitRangeID, $nnbZoneID))
			{
				return false;
			}
		}
				
		return true;
	}
	
	
	public function stpi_chkNbID($nnbUnitRangeID, $nnbZoneID)
	{
		if (!$this->stpi_chkNbUnitRangeID($nnbUnitRangeID))
		{
			return false;				
		}
		
		if (!$this->stpi_chkNbZoneID($nnbZoneID))
		{
			return false;				
		}
		
		return true;
	}
	
	
	public function stpi_chkNbUnitRangeID($nnbUnitRangeID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbUnitRangeID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		
		if (!is_numeric($nnbUnitRangeID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;
		}
		
		if ($nnbUnitRangeID < 1 || $nnbUnitRangeID > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_chkNbZoneID($nnbZoneID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbZoneID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		
		return true;
	}
	
	
	public function stpi_chkNbPrix($nnbPrix)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbPrix))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprix") . "</span><br/>\n");
			return false;				
		}
		
		if (!is_numeric($nnbPrix))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprix") . "</span><br/>\n");
			return false;
		}
		
		if ($nnbPrix < 0 || $nnbPrix > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprix") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_setNbID($nnbUnitRangeID, $nnbZoneID)
	{
		if (!$this->stpi_chkNbID($nnbUnitRangeID, $nnbZoneID))
		{
			return false;				
		}
		
		$this->nbUnitRangeID = $nnbUnitRangeID;
		$this->nbZoneID = $nnbZoneID;
		
		$SQL = "SELECT nbPrix";
		$SQL .= " FROM stpi_ship_UnitRange";
		$SQL .= " WHERE nbUnitRangeID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbUnitRangeID) . "'";
		$SQL .= " AND nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbZoneID) . "'";

		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbPrix = $row["nbPrix"];
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
	
	
	public function stpi_setNbUnitRangeID($nnbUnitRangeID)
	{
		if (!$this->stpi_chkNbUnitRangeID($nnbUnitRangeID))
		{
			return false;				
		}
		
		$this->nbUnitRangeID = $nnbUnitRangeID;
		
		return true;
	}
	
	
	public function stpi_setNbZoneID($nnbZoneID)
	{
		if (!$this->stpi_chkNbZoneID($nnbZoneID))
		{
			return false;				
		}
		
		$this->nbZoneID = $nnbZoneID;
		
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
	
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	
	public function stpi_getNbUnitRangeID()
	{
		return $this->nbUnitRangeID;
	}
	
	
	public function stpi_getNbZoneID()
	{
		return $this->nbZoneID;
	}
	
	
	public function stpi_getNbPrix()
	{
		return $this->nbPrix;
	}
	
	
	public function stpi_insert()
	{
		if ($this->nbUnitRangeID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span>\n");
			return false;
		}
		
		if ($this->nbZoneID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span>\n");
			return false;
		}
		
		$arrValeur = array();
		$arrChamp = array();
		
		$arrValeur[] = $this->nbUnitRangeID;
		$arrValeur[] = $this->nbZoneID;
		
		$arrChamp[] = "nbUnitRangeID";
		$arrChamp[] = "nbZoneID";
		
		if ($this->objBdd->stpi_chkArrExists($arrValeur, $arrChamp, "stpi_ship_UnitRange"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("exists") . "</span><br/>\n");
			return false;
		}
		
		$SQL1 = "INSERT INTO stpi_ship_UnitRange";
		$SQL1 .= " (nbUnitRangeID, nbZoneID, nbPrix)";
		$SQL1 .= " VALUE ('" . $this->objBdd->stpi_trsInputToBdd($this->nbUnitRangeID)  . "',"; 
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->nbZoneID)  . "',";
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->nbPrix)  . "')";
		
		
		if (!$this->objBdd->stpi_insert($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		
		}
		return true;
	}
	
	
	public function stpi_update()
	{
		if ($this->nbUnitRangeID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		
		if ($this->nbZoneID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		
		$arrValeur = array();
		$arrChamp = array();
		
		$arrValeur[] = $this->nbUnitRangeID;
		$arrValeur[] = $this->nbZoneID;
		
		$arrChamp[] = "nbUnitRangeID";
		$arrChamp[] = "nbZoneID";
		
		if (!$this->objBdd->stpi_chkArrExists($arrValeur, $arrChamp, "stpi_ship_UnitRange"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		
		$SQL1 = "UPDATE stpi_ship_UnitRange";
		$SQL1 .= " SET nbPrix = '" . $this->objBdd->stpi_trsInputToBdd($this->nbPrix) . "'";
		$SQL1 .= " WHERE nbUnitRangeID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbUnitRangeID) . "'";
		$SQL1 .= " AND nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbZoneID) . "'";
		
		if (!$this->objBdd->stpi_update($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
				
		return true; 
	}
	
	
	public function stpi_deleteUnitRangeID($nnbUnitRangeID)
	{
		if (!$this->stpi_chkNbUnitRangeID($nnbUnitRangeID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;				
		}			
			
		$SQL1 = "DELETE FROM stpi_ship_UnitRange";
		$SQL1 .= " WHERE nbUnitRangeID = '" . $this->objBdd->stpi_trsInputToBdd($nnbUnitRangeID) . "'";

		if (!$this->objBdd->stpi_delete($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_deleteZoneID($nnbZoneID)
	{
		if (!$this->stpi_chkNbZoneID($nnbZoneID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;				
		}			
			
		$SQL1 = "DELETE FROM stpi_ship_UnitRange";
		$SQL1 .= " WHERE nbZoneID = '" . $this->objBdd->stpi_trsInputToBdd($nnbZoneID) . "'";

		if (!$this->objBdd->stpi_delete($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addUnitRange()
		{
			xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_unitrangeAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_Addunitrange").style.visibility = "hidden";
			
			var strParam = "nbUnits=" + encodeURIComponent(document.getElementById("nbUnits").value);
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./ships.php?l=" + "<?php print(LG); ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Addunitrange").style.visibility = "visible";
			  			document.getElementById("stpi_unitrangeAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "shipunitrangeadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affAllUnitRange()
	{
		if (!$arrUnitRangeID = $this->stpi_selAllUnitRange())
		{
			$arrUnitRangeID = array();			
		}
		$nbUnits = 1;
		print("<p>\n");
		foreach ($arrUnitRangeID as $nbUnitRangeID)
		{
			print($nbUnits . " - " . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID) . " " . $this->objTexte->stpi_getArrTxt("unit"));
			print("&nbsp;&nbsp;<input type=\"button\" onclick=\"stpi_delUnitRange(" . $this->objBdd->stpi_trsBddToHTML($nbUnitRangeID) . ")\" value=\"" . $this->objTexte->stpi_getArrTxt("buttondelete") . "\"/><br/>\n");
			$nbUnits = $nbUnitRangeID + 1;
		}
		print("<span id=\"stpi_delUnitRange\"></span><br/>\n");	
		print("</p>\n");
		
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("addunit") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" id=\"nbUnits\" value=\"\" /><br/>\n");				
		print("<span id=\"stpi_unitrangeAdd\"></span><br/>\n");				
		print("<input id=\"stpi_Addunitrange\" type=\"button\" onclick=\"stpi_addUnitRange()\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonadd") . "\"/><br/>\n");				
		print("</p>\n");
	}
	
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delConfirmedUnitRangeClearMessage()
		{
		  	document.getElementById("stpi_delUnitRange").innerHTML = "";
		}
		
		function stpi_delUnitRange(nnbUnitRangeID)
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_delUnitRange").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "shipunitrangedel.php?nbUnitRangeID=" + nnbUnitRangeID;
			strUrl = strUrl + "&nbConfirmed=0&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_delUnitRange").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		
		function stpi_delConfirmedUnitRange(nnbUnitRangeID)
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_delUnitRange").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "shipunitrangedel.php?nbUnitRangeID=" + nnbUnitRangeID;
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
		  			document.getElementById("stpi_delUnitRange").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affDelete()
	{
		if ($this->nbUnitRangeID == 0)
		{
			return false;
		}
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirm") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_delConfirmedUnitRange(" . $this->nbUnitRangeID . ")\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delConfirmedUnitRangeClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
		
		return true;
	}
	
		
	public function stpi_selAllUnitRange()
	{
		$SQL = "SELECT DISTINCT nbUnitRangeID";
		$SQL .= " FROM stpi_ship_UnitRange";
		$SQL .= " ORDER BY nbUnitRangeID";
		
		$arrID = array();
	
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbUnitRangeID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selAllZone()
	{
		$SQL = "SELECT DISTINCT nbZoneID";
		$SQL .= " FROM stpi_ship_UnitRange";
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
}

?>