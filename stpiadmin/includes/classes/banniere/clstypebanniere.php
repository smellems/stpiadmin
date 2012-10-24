<?php
require_once(dirname(__FILE__) . "/clstypebannierelg.php");

class clstypebanniere
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeBanniereLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjTypeBanniereLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypebanniere");
		$this->objLang = new clslang();
		$this->objTypeBanniereLg = new clstypebannierelg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolDelete = 1;
			$this->arrObjTypeBanniereLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjTypeBanniereLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeBanniereID", "stpi_banniere_TypeBanniere"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkBoolDelete($nboolDelete)
	{
		if ($nboolDelete != 0 && $ $nboolDelete != 1)
		{
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
		
		$SQL = "SELECT nbTypeBanniereID, boolDelete FROM stpi_banniere_TypeBanniere WHERE nbTypeBanniereID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbID = $row["nbTypeBanniereID"];
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
	
	
	public function stpi_setBoolDelete($nboolDelete)
	{
		if (!$this->stpi_chkBoolDelete($nboolDelete))
		{
			return false;				
		}
		$this->boolDelete = $nboolDelete;
		return true;
	}
	
	
	public function stpi_setArrObjTypeBanniereLgFromBdd()
	{
		if (!$this->objTypeBanniereLg->stpi_setNbTypeBanniereID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypeBanniereID = $this->objTypeBanniereLg->stpi_selNbTypeBanniereID())
		{
			return false;
		}
		foreach ($arrNbTypeBanniereID as $strLg => $nbTypeBanniereLgID)
		{
			if (!$this->arrObjTypeBanniereLg[$strLg] = new clstypebannierelg($nbTypeBanniereLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	
	public function stpi_setObjTypeBanniereLgFromBdd()
	{
		if (!$this->objTypeBanniereLg->stpi_setNbTypeBanniereID($this->nbID))
		{
			return false;
		}
		if (!$nbTypeBanniereLgID = $this->objTypeBanniereLg->stpi_selNbTypeBanniereIDLG())
		{
			return false;
		}
		if (!$this->objTypeBanniereLg->stpi_setNbID($nbTypeBanniereLgID))
		{
			return false;
		}
		return true;
	}
	
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	
	public function stpi_getBoolDelete()
	{
		return $this->boolDelete;	
	}
	
	
	public function stpi_getObjTypeBanniereLg()
	{
		return $this->objTypeBanniereLg;
	}
	
	
	public function stpi_getArrObjTypeBanniereLg()
	{
		return $this->arrObjTypeBanniereLg;
	}
	
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_banniere_TypeBanniere (nbTypeBanniereID, boolDelete) VALUES (NULL, " . $this->objBdd->stpi_trsInputToBdd($this->boolDelete) . ")";
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
		
		$SQL = "UPDATE stpi_banniere_TypeBanniere";
		$SQL .= " SET nbTypeBanniereID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= ", boolDelete='" . $this->objBdd->stpi_trsInputToBdd($this->boolDelete) . "'";
		$SQL .= " WHERE nbTypeBanniereID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	
	public function stpi_delete($nnbTypeBanniereID)
	{
		if (!$this->stpi_chkNbID($nnbTypeBanniereID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_banniere_TypeBanniere WHERE nbTypeBanniereID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeBanniereID);
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
	
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchTypeBanniere(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeBanniere").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeBanniere").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "bannieretypebannieresaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeBanniere").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typebanniere") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypeBanniere(this.value)\" maxlength=\"50\" size=\"20\" id=\"strName\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypeBanniere\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypeBanniere()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypeBanniereAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypeBanniere").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeBanniereName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeBanniereDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./bannieretypebanniere.php?l=" + "<?php print(LG); ?>" + "&nbTypeBanniereID=";
		  				var nbTypeBanniereIDIndex = xmlHttp.responseText.indexOf("nbTypeBanniereID") + 17;
		  				var nbTypeBanniereIDLen = xmlHttp.responseText.length - nbTypeBanniereIDIndex;
		  				var nbTypeBanniereID = xmlHttp.responseText.substr(nbTypeBanniereIDIndex, nbTypeBanniereIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeBanniereID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypeBanniere").style.visibility = "visible";
			  			document.getElementById("stpi_TypeBanniereAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "bannieretypebanniereadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeBanniereName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strTypeBanniereDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypeBanniereAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypeBanniere\" type=\"button\" onclick=\"stpi_addTypeBanniere()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypeBanniereName" + strLg[i]).disabled = false;
				document.getElementById("strTypeBanniereDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypeBanniere()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypeBanniereID=" + encodeURIComponent(document.getElementById("nbTypeBanniereID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeBanniereName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeBanniereDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./bannieretypebanniere.php?l=" + "<?php print(LG); ?>" + "&nbTypeBanniereID=";
		  				var nbTypeBanniereIDIndex = xmlHttp.responseText.indexOf("nbTypeBanniereID") + 17;
		  				var nbTypeBanniereIDLen = xmlHttp.responseText.length - nbTypeBanniereIDIndex;
		  				var nbTypeBanniereID = xmlHttp.responseText.substr(nbTypeBanniereIDIndex, nbTypeBanniereIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeBanniereID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "bannieretypebanniereedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeBanniereName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeBanniereLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strTypeBanniereDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeBanniereLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypeBanniere()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypeBanniere()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypeBanniere()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "bannieretypebannieredel.php?nbTypeBanniereID=" + document.getElementById("nbTypeBanniereID").value;
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
		function stpi_delTypeBanniereConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "bannieretypebannieredel.php?nbTypeBanniereID=" + document.getElementById("nbTypeBanniereID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./bannieres.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delTypeBanniereConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_banniere_TypeBanniere.nbTypeBanniereID FROM stpi_banniere_TypeBanniere, stpi_banniere_TypeBanniere_Lg";
		$SQL .= " WHERE stpi_banniere_TypeBanniere.nbTypeBanniereID = stpi_banniere_TypeBanniere_Lg.nbTypeBanniereID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
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
	
	
	public function stpi_selAllPublic()
	{
		$arrID = array();
		$SQL = "SELECT stpi_banniere_TypeBanniere.nbTypeBanniereID FROM stpi_banniere_TypeBanniere, stpi_banniere_TypeBanniere_Lg";
		$SQL .= " WHERE stpi_banniere_TypeBanniere.nbTypeBanniereID = stpi_banniere_TypeBanniere_Lg.nbTypeBanniereID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " AND stpi_banniere_TypeBanniere.nbTypeBanniereID != '1'";
		$SQL .= " AND stpi_banniere_TypeBanniere.nbTypeBanniereID != '2'";
		$SQL .= " ORDER BY strName";
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
	

	public function stpi_selNbBanniereID()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_banniere_Banniere.nbBanniereID FROM stpi_banniere_Banniere, stpi_banniere_Banniere_Lg";
		$SQL .= " WHERE stpi_banniere_Banniere.nbBanniereID=stpi_banniere_Banniere_Lg.nbBanniereID";
		$SQL .= " AND stpi_banniere_Banniere.nbTypeBanniereID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " ORDER BY stpi_banniere_Banniere_Lg.strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbBanniereID"];
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