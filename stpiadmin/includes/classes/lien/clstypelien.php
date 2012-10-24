<?php
require_once(dirname(__FILE__) . "/clstypelienlg.php");
class clstypelien
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeLienLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjTypeLienLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypelien");
		$this->objLang = new clslang();
		$this->objTypeLienLg = new clstypelienlg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolDelete = 1;
			$this->arrObjTypeLienLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjTypeLienLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeLienID", "stpi_lien_TypeLien"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}

	public function stpi_chkBoolDelete($nboolDelete)
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

		$SQL = "SELECT boolDelete FROM stpi_lien_TypeLien WHERE nbTypeLienID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
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
	
	public function stpi_setArrObjTypeLienLgFromBdd()
	{
		if (!$this->objTypeLienLg->stpi_setNbTypeLienID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypeLienId = $this->objTypeLienLg->stpi_selNbTypeLienID())
		{
			return false;
		}
		foreach ($arrNbTypeLienId as $strLg => $nbTypeLienLgID)
		{
			if (!$this->arrObjTypeLienLg[$strLg] = new clsTypeLienlg($nbTypeLienLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjTypeLienLgFromBdd()
	{
		if (!$this->objTypeLienLg->stpi_setNbTypeLienID($this->nbID))
		{
			return false;
		}
		if (!$nbTypeLienLgId = $this->objTypeLienLg->stpi_selNbTypeLienIDLG())
		{
			return false;
		}
		if (!$this->objTypeLienLg->stpi_setNbID($nbTypeLienLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getObjTypeLienLg()
	{
		return $this->objTypeLienLg;
	}
	
	public function stpi_getArrObjTypeLienLg()
	{
		return $this->arrObjTypeLienLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_lien_TypeLien (nbTypeLienID, boolDelete) VALUES (NULL, 1)";
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
	
	public function stpi_delete($nnbTypeLienID)
	{
		if (!$this->stpi_chkNbID($nnbTypeLienID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_lien_TypeLien WHERE nbTypeLienID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeLienID);
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
		function stpi_SearchTypeLien(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeLien").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeLien").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "lientypeliensaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeLien").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typelien") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypeLien(this.value)\" maxlength=\"50\" size=\"20\" id=\"strName\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypeLien\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypeLien()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypeLienAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypeLien").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeLienName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeLienDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./lientypelien.php?l=" + "<?php print(LG); ?>" + "&nbTypeLienID=";
		  				var nbTypeLienIDIndex = xmlHttp.responseText.indexOf("nbTypeLienID") + 13;
		  				var nbTypeLienIDLen = xmlHttp.responseText.length - nbTypeLienIDIndex;
		  				var nbTypeLienID = xmlHttp.responseText.substr(nbTypeLienIDIndex, nbTypeLienIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeLienID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypeLien").style.visibility = "visible";
			  			document.getElementById("stpi_TypeLienAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "lientypelienadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeLienName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"5\" cols=\"50\" id=\"strTypeLienDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypeLienAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypeLien\" type=\"button\" onclick=\"stpi_addTypeLien()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypeLienName" + strLg[i]).disabled = false;
				document.getElementById("strTypeLienDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypeLien()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypeLienID=" + encodeURIComponent(document.getElementById("nbTypeLienID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeLienName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeLienDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./lientypelien.php?l=" + "<?php print(LG); ?>" + "&nbTypeLienID=";
		  				var nbTypeLienIDIndex = xmlHttp.responseText.indexOf("nbTypeLienID") + 13;
		  				var nbTypeLienIDLen = xmlHttp.responseText.length - nbTypeLienIDIndex;
		  				var nbTypeLienID = xmlHttp.responseText.substr(nbTypeLienIDIndex, nbTypeLienIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeLienID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "lientypelienedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeLienName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeLienLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"5\" cols=\"50\" id=\"strTypeLienDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeLienLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypeLien()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypeLien()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypeLien()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "lientypeliendel.php?nbTypeLienID=" + document.getElementById("nbTypeLienID").value;
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
		function stpi_delTypeLienConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "lientypeliendel.php?nbTypeLienID=" + document.getElementById("nbTypeLienID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./liens.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delTypeLienConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_lien_TypeLien.nbTypeLienID FROM stpi_lien_TypeLien, stpi_lien_TypeLien_Lg";
		$SQL .= " WHERE stpi_lien_TypeLien.nbTypeLienID=stpi_lien_TypeLien_Lg.nbTypeLienID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeLienID"];
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
		$SQL = "SELECT DISTINCT stpi_lien_TypeLien.nbTypeLienID FROM stpi_lien_Lien, stpi_lien_TypeLien, stpi_lien_TypeLien_Lg";
		$SQL .= " WHERE stpi_lien_TypeLien.nbTypeLienID=stpi_lien_TypeLien_Lg.nbTypeLienID";
		$SQL .= " AND stpi_lien_TypeLien.nbTypeLienID=stpi_lien_Lien.nbTypeLienID";
		// $SQL .= " AND stpi_lien_TypeLien.nbTypeLienID!=1";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY FIELD(stpi_lien_TypeLien.nbTypeLienID, 1) DESC, strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			// $arrID[] = 1;
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeLienID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbLienID()
	{
		$arrID = array();
		$SQL = "SELECT stpi_lien_Lien.nbLienID FROM stpi_lien_Lien, stpi_lien_Lien_Lg";
		$SQL .= " WHERE stpi_lien_Lien.nbLienID=stpi_lien_Lien_Lg.nbLienID";
		$SQL .= " AND nbTypeLienID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
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
