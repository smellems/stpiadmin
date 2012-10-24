<?php
require_once(dirname(__FILE__) . "/clsattributlg.php");
require_once(dirname(__FILE__) . "/clstypeattribut.php");
class clsattribut
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objAttributLg;
	private $objTypeAttribut;
	
	private $nbID;
	private $nbTypeAttributID;
	private $nbOrdre;
	
	private $arrObjAttributLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtattribut");
		$this->objLang = new clslang();
		$this->objAttributLg = new clsattributlg();
		$this->objTypeAttribut = new clstypeattribut();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbTypeAttributID = 0;
			$this->nbOrdre = 0;
			$this->arrObjAttributLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjAttributLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbAttributID", "stpi_item_Attribut"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbTypeAttributID($nnbTypeAttributID)
	{
		if (!$this->objTypeAttribut->stpi_chkNbID($nnbTypeAttributID))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbOrdre($nnbOrdre)
	{
		if (!is_numeric($nnbOrdre))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidordre") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbOrdre < 0 OR $nnbOrdre > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidordre") . "&nbsp;([0,1000000000])</span><br/>\n");
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
		
		$SQL = "SELECT nbTypeAttributID, nbOrdre FROM stpi_item_Attribut WHERE nbAttributID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeAttributID = $row["nbTypeAttributID"];
				$this->nbOrdre = $row["nbOrdre"];
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
	
	public function stpi_setNbTypeAttributID($nnbTypeAttributID)
	{
		if (!$this->stpi_chkNbTypeAttributID($nnbTypeAttributID))
		{
			return false;				
		}
		$this->nbTypeAttributID = $nnbTypeAttributID;
		return true;
	}
	
	public function stpi_setNbOrdre($nnbOrdre)
	{
		if (!$this->stpi_chkNbOrdre($nnbOrdre))
		{
			return false;				
		}
		$this->nbOrdre = $nnbOrdre;
		return true;
	}
	
	public function stpi_setArrObjAttributLgFromBdd()
	{
		if (!$this->objAttributLg->stpi_setNbAttributID($this->nbID))
		{
			return false;
		}
		if (!$arrNbAttributId = $this->objAttributLg->stpi_selNbAttributID())
		{
			return false;
		}
		foreach ($arrNbAttributId as $strLg => $nbAttributLgID)
		{
			if (!$this->arrObjAttributLg[$strLg] = new clsAttributlg($nbAttributLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjAttributLgFromBdd()
	{
		if (!$this->objAttributLg->stpi_setNbAttributID($this->nbID))
		{
			return false;
		}
		if (!$nbAttributLgId = $this->objAttributLg->stpi_selNbAttributIDLG())
		{
			return false;
		}
		if (!$this->objAttributLg->stpi_setNbID($nbAttributLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbTypeAttributID()
	{
		return $this->nbTypeAttributID;
	}
	
	public function stpi_getNbOrdre()
	{
		return $this->nbOrdre;
	}
	
	public function stpi_getObjAttributLg()
	{
		return $this->objAttributLg;
	}
	
	public function stpi_getObjTypeAttribut()
	{
		return $this->objTypeAttribut;
	}

	public function stpi_getArrObjAttributLg()
	{
		return $this->arrObjAttributLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_item_Attribut (nbAttributID, nbTypeAttributID, nbOrdre) VALUES (NULL, " . $this->objBdd->stpi_trsInputToBdd($this->nbTypeAttributID) . ", " . $this->objBdd->stpi_trsInputToBdd($this->nbOrdre) . ")";
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
		
		$SQL = "UPDATE stpi_item_Attribut";
		$SQL .= " SET nbTypeAttributID=" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeAttributID);
		$SQL .= " ,nbOrdre=" . $this->objBdd->stpi_trsInputToBdd($this->nbOrdre);
		$SQL .= " WHERE nbAttributID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbAttributID)
	{
		if (!$this->stpi_chkNbID($nnbAttributID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_Attribut WHERE nbAttributID=" . $this->objBdd->stpi_trsInputToBdd($nnbAttributID);
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
		function stpi_SearchAttribut(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affAttribut").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affAttribut").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemattributsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affAttribut").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("attribut") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchAttribut(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affAttribut\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addAttribut()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_AttributAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddAttribut").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strAttributName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strAttributDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeAttributID=" + encodeURIComponent(document.getElementById("nbTypeAttributID").value);
			strParam = strParam + "&nbOrdre=" + encodeURIComponent(document.getElementById("nbOrdre").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemattribut.php?l=" + "<?php print(LG); ?>" + "&nbAttributID=";
		  				var nbAttributIDIndex = xmlHttp.responseText.indexOf("nbAttributID") + 13;
		  				var nbAttributIDLen = xmlHttp.responseText.length - nbAttributIDIndex;
		  				var nbAttributID = xmlHttp.responseText.substr(nbAttributIDIndex, nbAttributIDLen);
		  				strUrlRedirect = strUrlRedirect + nbAttributID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddAttribut").style.visibility = "visible";
			  			document.getElementById("stpi_AttributAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemattributadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strAttributName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strAttributDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typeattribut") . "<br/>\n");				
		print("<select id=\"nbTypeAttributID\">\n");
		print("<option selected=\"selected\" value=\"\"></option>\n");
		if ($arrNbTypeAttributID = $this->objTypeAttribut->stpi_selAll())
		{
			foreach($arrNbTypeAttributID as $nbTypeAttributID)
			{
				if ($this->objTypeAttribut->stpi_setNbID($nbTypeAttributID))
				{
					if ($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_setNbTypeAttributID($nbTypeAttributID))
					{
						if ($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_setNbID($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_selNbTypeAttributIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeAttributID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("ordre") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"0\" id=\"nbOrdre\"/><br/>\n");
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_AttributAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddAttribut\" type=\"button\" onclick=\"stpi_addAttribut()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strAttributName" + strLg[i]).disabled = false;
				document.getElementById("strAttributDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("nbTypeAttributID").disabled = false;
			document.getElementById("nbOrdre").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editAttribut()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbAttributID=" + encodeURIComponent(document.getElementById("nbAttributID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strAttributName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strAttributDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeAttributID=" + encodeURIComponent(document.getElementById("nbTypeAttributID").value);
			strParam = strParam + "&nbOrdre=" + encodeURIComponent(document.getElementById("nbOrdre").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemattribut.php?l=" + "<?php print(LG); ?>" + "&nbAttributID=";
		  				var nbAttributIDIndex = xmlHttp.responseText.indexOf("nbAttributID") + 13;
		  				var nbAttributIDLen = xmlHttp.responseText.length - nbAttributIDIndex;
		  				var nbAttributID = xmlHttp.responseText.substr(nbAttributIDIndex, nbAttributIDLen);
		  				strUrlRedirect = strUrlRedirect + nbAttributID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemattributedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strAttributName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjAttributLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strAttributDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjAttributLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typeattribut") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbTypeAttributID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeAttributID = $this->objTypeAttribut->stpi_selAll())
		{
			foreach($arrNbTypeAttributID as $nbTypeAttributID)
			{
				if ($this->objTypeAttribut->stpi_setNbID($nbTypeAttributID))
				{
					if ($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_setNbTypeAttributID($nbTypeAttributID))
					{
						if ($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_setNbID($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_selNbTypeAttributIDLG()))
						{
							print("<option");
							if ($this->nbTypeAttributID == $this->objTypeAttribut->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeAttributID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeAttribut->stpi_getObjTypeAttributLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("ordre") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbOrdre) . "\" id=\"nbOrdre\"/><br/>\n");
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editAttribut()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delAttribut()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delAttribut()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemattributdel.php?nbAttributID=" + document.getElementById("nbAttributID").value;
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
		function stpi_delAttributConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemattributdel.php?nbAttributID=" + document.getElementById("nbAttributID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delAttributConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_Attribut.nbAttributID FROM stpi_item_Attribut, stpi_item_Attribut_Lg";
		$SQL .= " WHERE stpi_item_Attribut.nbAttributID=stpi_item_Attribut_Lg.nbAttributID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
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
	
	public function stpi_selNbSousItemID()
	{
		$arrID = array();
		$SQL = "SELECT nbSousItemID FROM stpi_item_SousItem_Attribut";
		$SQL .= " WHERE nbAttributID='" . $this->nbID . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSousItemID"];
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