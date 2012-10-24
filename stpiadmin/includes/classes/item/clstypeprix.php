<?php
require_once(dirname(__FILE__) . "/clstypeprixlg.php");
class clstypeprix
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypePrixLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjTypePrixLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypeprix");
		$this->objLang = new clslang();
		$this->objTypePrixLg = new clstypeprixlg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolDelete = 1;
			$this->arrObjTypePrixLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjTypePrixLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypePrixID", "stpi_item_TypePrix"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
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
		
		$SQL = "SELECT boolDelete FROM stpi_item_TypePrix WHERE nbTypePrixID=" . $this->nbID;
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
	
	public function stpi_setArrObjTypePrixLgFromBdd()
	{
		if (!$this->objTypePrixLg->stpi_setNbTypePrixID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypePrixId = $this->objTypePrixLg->stpi_selNbTypePrixID())
		{
			return false;
		}
		foreach ($arrNbTypePrixId as $strLg => $nbTypePrixLgID)
		{
			if (!$this->arrObjTypePrixLg[$strLg] = new clsTypePrixlg($nbTypePrixLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjTypePrixLgFromBdd()
	{
		if (!$this->objTypePrixLg->stpi_setNbTypePrixID($this->nbID))
		{
			return false;
		}
		if (!$nbTypePrixLgId = $this->objTypePrixLg->stpi_selNbTypePrixIDLG())
		{
			return false;
		}
		if (!$this->objTypePrixLg->stpi_setNbID($nbTypePrixLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbImgWidthMax()
	{
		return $this->nbImgWidthMax;
	}
	
	public function stpi_getNbImgHeightMax()
	{
		return $this->nbImgHeightMax;
	}
	
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	public function stpi_getObjTypePrixLg()
	{
		return $this->objTypePrixLg;
	}
	
	public function stpi_getArrObjTypePrixLg()
	{
		return $this->arrObjTypePrixLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_item_TypePrix (nbTypePrixID, boolDelete) VALUES (NULL, 1)";
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
	
	public function stpi_delete($nnbTypePrixID)
	{
		if (!$this->stpi_chkNbID($nnbTypePrixID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_TypePrix WHERE nbTypePrixID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypePrixID);
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
		function stpi_SearchTypePrix(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypePrix").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypePrix").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeprixsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypePrix").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typeprix") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypePrix(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypePrix\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypePrix()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypePrixAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypePrix").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypePrixName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypePrixDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemtypeprix.php?l=" + "<?php print(LG); ?>" + "&nbTypePrixID=";
		  				var nbTypePrixIDIndex = xmlHttp.responseText.indexOf("nbTypePrixID") + 13;
		  				var nbTypePrixIDLen = xmlHttp.responseText.length - nbTypePrixIDIndex;
		  				var nbTypePrixID = xmlHttp.responseText.substr(nbTypePrixIDIndex, nbTypePrixIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypePrixID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypePrix").style.visibility = "visible";
			  			document.getElementById("stpi_TypePrixAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemtypeprixadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypePrixName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strTypePrixDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypePrixAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypePrix\" type=\"button\" onclick=\"stpi_addTypePrix()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypePrixName" + strLg[i]).disabled = false;
				document.getElementById("strTypePrixDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypePrix()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypePrixID=" + encodeURIComponent(document.getElementById("nbTypePrixID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypePrixName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypePrixDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemtypeprix.php?l=" + "<?php print(LG); ?>" + "&nbTypePrixID=";
		  				var nbTypePrixIDIndex = xmlHttp.responseText.indexOf("nbTypePrixID") + 13;
		  				var nbTypePrixIDLen = xmlHttp.responseText.length - nbTypePrixIDIndex;
		  				var nbTypePrixID = xmlHttp.responseText.substr(nbTypePrixIDIndex, nbTypePrixIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypePrixID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemtypeprixedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypePrixName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypePrixLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strTypePrixDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypePrixLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypePrix()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypePrix()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypePrix()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeprixdel.php?nbTypePrixID=" + document.getElementById("nbTypePrixID").value;
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
		function stpi_delTypePrixConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeprixdel.php?nbTypePrixID=" + document.getElementById("nbTypePrixID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./items.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delTypePrixConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_TypePrix.nbTypePrixID FROM stpi_item_TypePrix, stpi_item_TypePrix_Lg";
		$SQL .= " WHERE stpi_item_TypePrix.nbTypePrixID=stpi_item_TypePrix_Lg.nbTypePrixID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypePrixID"];
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