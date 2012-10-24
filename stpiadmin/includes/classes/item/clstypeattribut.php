<?php
require_once(dirname(__FILE__) . "/clstypeattributlg.php");
class clstypeattribut
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeAttributLg;
	
	private $nbID;
	
	private $arrObjTypeAttributLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypeattribut");
		$this->objLang = new clslang();
		$this->objTypeAttributLg = new clstypeattributlg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->arrObjTypeAttributLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjTypeAttributLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeAttributID", "stpi_item_TypeAttribut"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
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
		return true;
	}
	
	public function stpi_setArrObjTypeAttributLgFromBdd()
	{
		if (!$this->objTypeAttributLg->stpi_setNbTypeAttributID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypeAttributId = $this->objTypeAttributLg->stpi_selNbTypeAttributID())
		{
			return false;
		}
		foreach ($arrNbTypeAttributId as $strLg => $nbTypeAttributLgID)
		{
			if (!$this->arrObjTypeAttributLg[$strLg] = new clsTypeAttributlg($nbTypeAttributLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjTypeAttributLgFromBdd()
	{
		if (!$this->objTypeAttributLg->stpi_setNbTypeAttributID($this->nbID))
		{
			return false;
		}
		if (!$nbTypeAttributLgId = $this->objTypeAttributLg->stpi_selNbTypeAttributIDLG())
		{
			return false;
		}
		if (!$this->objTypeAttributLg->stpi_setNbID($nbTypeAttributLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getObjTypeAttributLg()
	{
		return $this->objTypeAttributLg;
	}
	
	public function stpi_getArrObjTypeAttributLg()
	{
		return $this->arrObjTypeAttributLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_item_TypeAttribut (nbTypeAttributID) VALUES (NULL)";
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
	
	public function stpi_delete($nnbTypeAttributID)
	{
		if (!$this->stpi_chkNbID($nnbTypeAttributID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_TypeAttribut WHERE nbTypeAttributID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeAttributID);
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
		function stpi_SearchTypeAttribut(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeAttribut").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeAttribut").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeattributsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeAttribut").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typeattribut") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypeAttribut(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypeAttribut\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypeAttribut()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypeAttributAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypeAttribut").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeAttributName" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemtypeattribut.php?l=" + "<?php print(LG); ?>" + "&nbTypeAttributID=";
		  				var nbTypeAttributIDIndex = xmlHttp.responseText.indexOf("nbTypeAttributID") + 17;
		  				var nbTypeAttributIDLen = xmlHttp.responseText.length - nbTypeAttributIDIndex;
		  				var nbTypeAttributID = xmlHttp.responseText.substr(nbTypeAttributIDIndex, nbTypeAttributIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeAttributID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypeAttribut").style.visibility = "visible";
			  			document.getElementById("stpi_TypeAttributAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemtypeattributadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeAttributName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypeAttributAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypeAttribut\" type=\"button\" onclick=\"stpi_addTypeAttribut()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypeAttributName" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypeAttribut()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypeAttributID=" + encodeURIComponent(document.getElementById("nbTypeAttributID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeAttributName" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemtypeattribut.php?l=" + "<?php print(LG); ?>" + "&nbTypeAttributID=";
		  				var nbTypeAttributIDIndex = xmlHttp.responseText.indexOf("nbTypeAttributID") + 17;
		  				var nbTypeAttributIDLen = xmlHttp.responseText.length - nbTypeAttributIDIndex;
		  				var nbTypeAttributID = xmlHttp.responseText.substr(nbTypeAttributIDIndex, nbTypeAttributIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeAttributID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemtypeattributedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeAttributName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeAttributLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypeAttribut()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypeAttribut()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypeAttribut()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeattributdel.php?nbTypeAttributID=" + document.getElementById("nbTypeAttributID").value;
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
		function stpi_delTypeAttributConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeattributdel.php?nbTypeAttributID=" + document.getElementById("nbTypeAttributID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delTypeAttributConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_TypeAttribut.nbTypeAttributID FROM stpi_item_TypeAttribut, stpi_item_TypeAttribut_Lg";
		$SQL .= " WHERE stpi_item_TypeAttribut.nbTypeAttributID=stpi_item_TypeAttribut_Lg.nbTypeAttributID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeAttributID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbAttributID()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_Attribut.nbAttributID FROM stpi_item_Attribut, stpi_item_Attribut_Lg";
		$SQL .= " WHERE stpi_item_Attribut.nbAttributID=stpi_item_Attribut_Lg.nbAttributID";
		$SQL .= " AND nbTypeAttributID='" . $this->nbID . "'";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY nbOrdre, strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbAttributID"];
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