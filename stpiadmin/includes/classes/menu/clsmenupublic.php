<?php
require_once(dirname(__FILE__) . "/clsmenupubliclg.php");
class clsmenupublic
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objMenuLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjMenuLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtmenupublic");
		$this->objLang = new clslang();
		$this->objMenuLg = new clsmenupubliclg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolDelete = 1;
			$this->arrObjMenuLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjMenuLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbMenuID", "stpi_menu_Menu"))
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

		$SQL = "SELECT boolDelete FROM stpi_menu_Menu WHERE nbMenuID=" . $this->nbID;
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
	
	public function stpi_setArrObjMenuLgFromBdd()
	{
		if (!$this->objMenuLg->stpi_setNbMenuID($this->nbID))
		{
			return false;
		}
		if (!$arrNbMenuId = $this->objMenuLg->stpi_selNbMenuID())
		{
			return false;
		}
		foreach ($arrNbMenuId as $strLg => $nbMenuLgID)
		{
			if (!$this->arrObjMenuLg[$strLg] = new clsmenupubliclg($nbMenuLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjMenuLgFromBdd()
	{
		if (!$this->objMenuLg->stpi_setNbMenuID($this->nbID))
		{
			return false;
		}
		if (!$nbMenuLgId = $this->objMenuLg->stpi_selNbMenuIDLG())
		{
			return false;
		}
		if (!$this->objMenuLg->stpi_setNbID($nbMenuLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getObjMenuLg()
	{
		return $this->objMenuLg;
	}
	
	public function stpi_getArrObjMenuLg()
	{
		return $this->arrObjMenuLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_menu_Menu (nbMenuID, boolDelete) VALUES (NULL, 1)";
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
	
	public function stpi_delete($nnbMenuID)
	{
		if (!$this->stpi_chkNbID($nnbMenuID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_menu_Menu WHERE nbMenuID=" . $this->objBdd->stpi_trsInputToBdd($nnbMenuID);
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
		function stpi_SearchMenu(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affMenu").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affMenu").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "menusaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affMenu").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("menu") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchMenu(this.value)\" maxlength=\"50\" size=\"20\" id=\"strName\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affMenu\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addMenu()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_MenuAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddMenu").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMenuName" + strLg[i]).value);

			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./menu.php?l=" + "<?php print(LG); ?>" + "&nbMenuID=";
		  				var nbMenuIDIndex = xmlHttp.responseText.indexOf("nbMenuID") + 9;
		  				var nbMenuIDLen = xmlHttp.responseText.length - nbMenuIDIndex;
		  				var nbMenuID = xmlHttp.responseText.substr(nbMenuIDIndex, nbMenuIDLen);
		  				strUrlRedirect = strUrlRedirect + nbMenuID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddMenu").style.visibility = "visible";
			  			document.getElementById("stpi_MenuAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "menuadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strMenuName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_MenuAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddMenu\" type=\"button\" onclick=\"stpi_addMenu()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strMenuName" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editMenu()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbMenuID=" + encodeURIComponent(document.getElementById("nbMenuID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMenuName" + strLg[i]).value);

			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./menu.php?l=" + "<?php print(LG); ?>" + "&nbMenuID=";
		  				var nbMenuIDIndex = xmlHttp.responseText.indexOf("nbMenuID") + 9;
		  				var nbMenuIDLen = xmlHttp.responseText.length - nbMenuIDIndex;
		  				var nbMenuID = xmlHttp.responseText.substr(nbMenuIDIndex, nbMenuIDLen);
		  				strUrlRedirect = strUrlRedirect + nbMenuID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "menuedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strMenuName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjMenuLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editMenu()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delMenu()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delMenu()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "menudel.php?nbMenuID=" + document.getElementById("nbMenuID").value;
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
		function stpi_delMenuConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "menudel.php?nbMenuID=" + document.getElementById("nbMenuID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./menus.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delMenuConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_menu_Menu.nbMenuID FROM stpi_menu_Menu, stpi_menu_Menu_Lg";
		$SQL .= " WHERE stpi_menu_Menu.nbMenuID=stpi_menu_Menu_Lg.nbMenuID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbMenuID"];
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
		$SQL = "SELECT DISTINCT stpi_menu_Menu.nbMenuID FROM stpi_menu_Lien, stpi_menu_Menu, stpi_menu_Menu_Lg";
		$SQL .= " WHERE stpi_menu_Menu.nbMenuID=stpi_menu_Menu_Lg.nbMenuID";
		$SQL .= " AND stpi_menu_Menu.nbMenuID=stpi_menu_Lien.nbMenuID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbMenuID"];
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
