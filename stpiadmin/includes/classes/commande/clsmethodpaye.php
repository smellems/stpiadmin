<?php
require_once(dirname(__FILE__) . "/clsmethodpayelg.php");
class clsmethodpaye
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objMethodPayeLg;
	
	private $nbID;
	private $boolCarte;
	private $boolDelete;
	
	private $arrObjMethodPayeLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtmethodpaye");
		$this->objLang = new clslang();
		$this->objMethodPayeLg = new clsmethodpayelg();
		$this->arrObjMethodPayeLg = array();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolCarte = 0;
			$this->boolDelete = 1;
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbMethodPayeID", "stpi_commande_MethodPaye"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkBoolCarte($nboolCarte)
	{
		if ($nboolCarte != "1" AND $nboolCarte != "0")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcarte") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkBoolDelete()
	{
		if ($this->boolDelete != 1)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrTxt("booldelete") . "</span><br/>\n");
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
		
		$SQL = "SELECT boolCarte, boolDelete FROM stpi_commande_MethodPaye WHERE nbMethodPayeID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->boolCarte = $row["boolCarte"];
				$this->boolDelete = $row["boolDelete"];
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
	
	public function stpi_setBoolCarte($nboolCarte)
	{
		if (!$this->stpi_chkBoolCarte($nboolCarte))
		{
			return false;				
		}
		$this->boolCarte = $nboolCarte;
		return true;
	}
	
	public function stpi_setArrObjMethodPayeLgFromBdd()
	{
		if (!$this->objMethodPayeLg->stpi_setNbMethodPayeID($this->nbID))
		{
			return false;
		}
		if (!$arrNbMethodPayeId = $this->objMethodPayeLg->stpi_selNbMethodPayeID())
		{
			return false;
		}
		foreach ($arrNbMethodPayeId as $strLg => $nbMethodPayeLgID)
		{
			if (!$this->arrObjMethodPayeLg[$strLg] = new clsMethodPayelg($nbMethodPayeLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjMethodPayeLgFromBdd()
	{
		if (!$this->objMethodPayeLg->stpi_setNbMethodPayeID($this->nbID))
		{
			return false;
		}
		if (!$nbMethodPayeLgId = $this->objMethodPayeLg->stpi_selNbMethodPayeIDLG())
		{
			return false;
		}
		if (!$this->objMethodPayeLg->stpi_setNbID($nbMethodPayeLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getBoolCarte()
	{
		return $this->boolCarte;
	}
	
	public function stpi_getObjMethodPayeLg()
	{
		return $this->objMethodPayeLg;
	}
	
	public function stpi_getObjTypeMethodPaye()
	{
		return $this->objTypeMethodPaye;
	}

	public function stpi_getArrObjMethodPayeLg()
	{
		return $this->arrObjMethodPayeLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_commande_MethodPaye (nbMethodPayeID, boolCarte, boolDelete) VALUES (NULL, " . $this->objBdd->stpi_trsInputToBdd($this->boolCarte) . ", 1)";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			return $this->nbID;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_update()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$SQL = "UPDATE stpi_commande_MethodPaye";
		$SQL .= " SET boolCarte='" . $this->objBdd->stpi_trsInputToBdd($this->boolCarte) . "'";
		$SQL .= " WHERE nbMethodPayeID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbMethodPayeID)
	{
		if (!$this->stpi_chkNbID($nnbMethodPayeID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_MethodPaye WHERE nbMethodPayeID=" . $this->objBdd->stpi_trsInputToBdd($nnbMethodPayeID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
	}
	
	
	public function stpi_affJsSelectMethodPaye()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affMethodPaye(nnbMethodPayeID)
		{
			if (nnbMethodPayeID.length == 0)
			{ 
				document.getElementById("stpi_affMethodPaye").innerHTML = "";
		  		return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affMethodPaye").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "commandemethodpayeaff.php?l=<? print(LG); ?>&nbMethodPayeID=" + nnbMethodPayeID + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affMethodPaye").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affSelectMethodPaye($nnbMethodPayeID = 0)
	{					
		if (!$arrNbMethodPayeID = $this->stpi_selAll())
		{
			$arrNbMethodPayeID = array();
		}
		
		print("<select onchange=\"stpi_affMethodPaye(this.value)\" id=\"nbMethodPayeID\" >\n");
		print("<option value=\"\" ></option>\n");
		foreach ($arrNbMethodPayeID as $nbMethodPayeID)
		{
			if ($this->stpi_setNbID($nbMethodPayeID))
			{
				if ($this->stpi_setObjMethodPayeLgFromBdd())
				{
					if ($nnbMethodPayeID == $this->nbID)
					{
						print("<option selected=\"selected\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objMethodPayeLg->stpi_getStrName()) . "</option>\n");
					}
					else
					{
						print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objMethodPayeLg->stpi_getStrName()) . "</option>\n");
					}
				}
			}
		}
		print("</select><br/>\n");
		print("<div id=\"stpi_affMethodPaye\" ></div><br/>\n");
	}
	
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchMethodPaye(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affMethodPaye").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affMethodPaye").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandemethodpayesaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affMethodPaye").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affSearch()
	{
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("methodpaye") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchMethodPaye(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affMethodPaye\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addMethodPaye()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_MethodPayeAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddMethodPaye").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMethodPayeName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMethodPayeDesc" + strLg[i]).value);
			}
			if (document.getElementById("chkCarte").checked)
			{
				strParam = strParam + "&boolCarte=1";
			}
			else
			{
				strParam = strParam + "&boolCarte=0";
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandemethodpaye.php?l=" + "<?php print(LG); ?>" + "&nbMethodPayeID=";
		  				var nbMethodPayeIDIndex = xmlHttp.responseText.indexOf("nbMethodPayeID") + 15;
		  				var nbMethodPayeIDLen = xmlHttp.responseText.length - nbMethodPayeIDIndex;
		  				var nbMethodPayeID = xmlHttp.responseText.substr(nbMethodPayeIDIndex, nbMethodPayeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbMethodPayeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddMethodPaye").style.visibility = "visible";
			  			document.getElementById("stpi_MethodPayeAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commandemethodpayeadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strMethodPayeName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strMethodPayeDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
			print("<p>\n");
			print("<input id=\"chkCarte\" type=\"checkbox\"/>" . $this->objTexte->stpi_getArrTxt("carte"));
			print("</p>\n");
			print("<p>\n");
			print("<span id=\"stpi_MethodPayeAdd\"></span><br/>\n");
			print("<input id=\"stpi_AddMethodPaye\" type=\"button\" onclick=\"stpi_addMethodPaye()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
			print("</p>\n");
	}
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			for (i in strLg)
			{
				document.getElementById("strMethodPayeName" + strLg[i]).disabled = false;
				document.getElementById("strMethodPayeDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("chkCarte").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editMethodPaye()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbMethodPayeID=" + encodeURIComponent(document.getElementById("nbMethodPayeID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMethodPayeName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMethodPayeDesc" + strLg[i]).value);
			}
			if (document.getElementById("chkCarte").checked)
			{
				strParam = strParam + "&boolCarte=1";
			}
			else
			{
				strParam = strParam + "&boolCarte=0";
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandemethodpaye.php?l=" + "<?php print(LG); ?>" + "&nbMethodPayeID=";
		  				var nbMethodPayeIDIndex = xmlHttp.responseText.indexOf("nbMethodPayeID") + 15;
		  				var nbMethodPayeIDLen = xmlHttp.responseText.length - nbMethodPayeIDIndex;
		  				var nbMethodPayeID = xmlHttp.responseText.substr(nbMethodPayeIDIndex, nbMethodPayeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbMethodPayeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commandemethodpayeedit.php", true);
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
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		foreach ($arrLang as $strLg)	
		{
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strMethodPayeName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjMethodPayeLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strMethodPayeDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjMethodPayeLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<input disabled=\"disabled\" id=\"chkCarte\" type=\"checkbox\"");
		if ($this->boolCarte == 1)
		{
			print(" checked=\"checked\"");
		}
		print("/>&nbsp;" . $this->objTexte->stpi_getArrTxt("carte"));
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editMethodPaye()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delMethodPaye()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delMethodPaye()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandemethodpayedel.php?nbMethodPayeID=" + document.getElementById("nbMethodPayeID").value;
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
		function stpi_delMethodPayeConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandemethodpayedel.php?nbMethodPayeID=" + document.getElementById("nbMethodPayeID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandes.php?l=" + "<?php print(LG); ?>";
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
	
	public function stpi_affDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirm") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_delMethodPayeConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
		
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_commande_MethodPaye.nbMethodPayeID FROM stpi_commande_MethodPaye, stpi_commande_MethodPaye_Lg";
		$SQL .= " WHERE stpi_commande_MethodPaye.nbMethodPayeID=stpi_commande_MethodPaye_Lg.nbMethodPayeID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbMethodPayeID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbCommandeID()
	{
		$arrID = array();
		$SQL = "SELECT nbCommandeID FROM stpi_commande_Commande";
		$SQL .= " WHERE nbMethodPayeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " ORDER BY dtEntryDate";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbCommandeID"];
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