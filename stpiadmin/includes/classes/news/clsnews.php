<?php
require_once(dirname(__FILE__) . "/clstypenews.php");
require_once(dirname(__FILE__) . "/clsnewslg.php");
class clsnews
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objNewsLg;
	private $objTypeNews;
	
	private $nbID;
	private $dtEntryDate;
	private $nbTypeNewsID;
	
	private $arrObjNewsLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtnews");
		$this->objLang = new clslang();
		$this->objNewsLg = new clsnewslg();
		$this->objTypeNews = new clstypenews();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->dtEntryDate = "";
			$this->nbTypeNewsID = 0;
			$this->arrObjNewsLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjNewsLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbNewsID", "stpi_news_News"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}

	public function stpi_chkNbTypeNewsID($nnbTypeNewsID)
	{
		if (!$this->objTypeNews->stpi_chkNbID($nnbTypeNewsID))
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
		
		$SQL = "SELECT dtEntryDate, nbTypeNewsID FROM stpi_news_News WHERE nbNewsID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->dtEntryDate = $row["dtEntryDate"];
				$this->nbTypeNewsID = $row["nbTypeNewsID"];
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

	public function stpi_setNbTypeNewsID($nnbTypeNewsID)
	{
		if (!$this->stpi_chkNbTypeNewsID($nnbTypeNewsID))
		{
			return false;				
		}
		$this->nbTypeNewsID = $nnbTypeNewsID;
		return true;
	}
	
	public function stpi_setArrObjNewsLgFromBdd()
	{
		if (!$this->objNewsLg->stpi_setNbNewsID($this->nbID))
		{
			return false;
		}
		if (!$arrNbNewsId = $this->objNewsLg->stpi_selNbNewsID())
		{
			return false;
		}
		foreach ($arrNbNewsId as $strLg => $nbNewsLgID)
		{
			if (!$this->arrObjNewsLg[$strLg] = new clsnewslg($nbNewsLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjNewsLgFromBdd()
	{
		$SQL = "SELECT nbNewsLgID FROM stpi_news_News_Lg WHERE nbNewsID=" . $this->nbID . " AND strLg='" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$row = mysql_fetch_assoc($result);
			if (!$this->objNewsLg->stpi_setNbID($row["nbNewsLgID"]))
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

	public function stpi_getNbTypeNewsID()
	{
		return $this->nbTypeNewsID;
	}
	
	public function stpi_getDtEntryDate()
	{
		return $this->dtEntryDate;
	}

	public function stpi_getObjTypeNews()
	{
		return $this->objTypeNews;
	}
	
	public function stpi_getObjNewsLg()
	{
		return $this->objNewsLg;
	}
	
	public function stpi_getArrObjNewsLg()
	{
		return $this->arrObjNewsLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_news_News (nbTypeNewsID, dtEntryDate) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeNewsID) . ", NOW())";
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
		
		$SQL = "UPDATE stpi_news_News";
		$SQL .= " SET nbTypeNewsID='" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeNewsID) . "'";
		$SQL .= " WHERE nbNewsID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbNewsID)
	{
		if (!$this->stpi_chkNbID($nnbNewsID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_news_News WHERE nbNewsID=" . $this->objBdd->stpi_trsInputToBdd($nnbNewsID);
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
		function stpi_SearchNews(nstrTitre)
		{
			if (nstrTitre.length == 0)
			{ 
				document.getElementById("stpi_affNews").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affNews").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "newssaff.php?strTitre=" + nstrTitre + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affNews").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("news") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchNews(this.value)\" maxlength=\"50\" size=\"20\" id=\"strTitre\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affNews\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addNews()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_NewsAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddNews").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strTitre" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTitre" + strLg[i]).value);
				strParam = strParam + "&strNews" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strNews" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeNewsID=" + encodeURIComponent(document.getElementById("nbTypeNewsID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./news.php?l=" + "<?php print(LG); ?>" + "&nbNewsID=";
		  				var nbNewsIDIndex = xmlHttp.responseText.indexOf("nbNewsID") + 9;
		  				var nbNewsIDLen = xmlHttp.responseText.length - nbNewsIDIndex;
		  				var nbNewsID = xmlHttp.responseText.substr(nbNewsIDIndex, nbNewsIDIndex);
		  				strUrlRedirect = strUrlRedirect + nbNewsID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddNews").style.visibility = "visible";
			  			document.getElementById("stpi_NewsAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "newsadd.php", true);
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
			print($this->objTexte->stpi_getArrTxt("title") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"75\" size=\"50\" id=\"strTitre" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("news") . " " . $strLg . "<br/>\n");
			print("<textarea rows=\"8\" cols=\"75\" id=\"strNews" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typenews") . "<br/>\n");				
		print("<select id=\"nbTypeNewsID\">\n");
		print("<option selected=\"selected\" value=\"\"></option>\n");
		if ($arrNbTypeNewsID = $this->objTypeNews->stpi_selAll())
		{
			foreach($arrNbTypeNewsID as $nbTypeNewsID)
			{
				if ($this->objTypeNews->stpi_setNbID($nbTypeNewsID))
				{
					if ($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_setNbTypeNewsID($nbTypeNewsID))
					{
						if ($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_setNbID($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_selNbTypeNewsIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeNewsID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_NewsAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddNews\" type=\"button\" onclick=\"stpi_addNews()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTitre" + strLg[i]).disabled = false;
				document.getElementById("strNews" + strLg[i]).disabled = false;
			}
			document.getElementById("nbTypeNewsID").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editNews()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbNewsID=" + encodeURIComponent(document.getElementById("nbNewsID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strTitre" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTitre" + strLg[i]).value);
				strParam = strParam + "&strNews" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strNews" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeNewsID=" + encodeURIComponent(document.getElementById("nbTypeNewsID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./news.php?l=" + "<?php print(LG); ?>" + "&nbNewsID=";
		  				var nbNewsIDIndex = xmlHttp.responseText.indexOf("nbNewsID") + 9;
		  				var nbNewsIDLen = xmlHttp.responseText.length - nbNewsIDIndex;
		  				var nbNewsID = xmlHttp.responseText.substr(nbNewsIDIndex, nbNewsIDLen);
		  				strUrlRedirect = strUrlRedirect + nbNewsID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "newsedit.php", true);
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
			print($this->objTexte->stpi_getArrTxt("title") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"75\" size=\"50\" id=\"strTitre" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjNewsLg[$strLg]->stpi_getStrTitre()) . "\" /><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("news") . " " . $strLg . "<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"8\" cols=\"75\" id=\"strNews" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjNewsLg[$strLg]->stpi_getStrNews()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typenews") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbTypeNewsID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeNewsID = $this->objTypeNews->stpi_selAll())
		{
			foreach($arrNbTypeNewsID as $nbTypeNewsID)
			{
				if ($this->objTypeNews->stpi_setNbID($nbTypeNewsID))
				{
					if ($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_setNbTypeNewsID($nbTypeNewsID))
					{
						if ($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_setNbID($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_selNbTypeNewsIDLG()))
						{
							print("<option");
							if ($this->nbTypeNewsID == $this->objTypeNews->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeNewsID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeNews->stpi_getObjTypeNewsLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editNews()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delNews()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delNews()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "newsdel.php?nbNewsID=" + document.getElementById("nbNewsID").value;
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
		function stpi_delNewsConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "newsdel.php?nbNewsID=" + document.getElementById("nbNewsID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./newss.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delNewsConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	
	public function stpi_selAll($nnbLimit = 0)
	{
		$SQL = "SELECT nbNewsID";
		$SQL .= " FROM stpi_news_News";
		$SQL .= " ORDER BY dtEntryDate DESC";
		if ($nnbLimit != 0)
		{
			 $SQL .= " LIMIT 0, " . $nnbLimit;
		}
		
		$arrID = array();
	
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbNewsID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}

	// fonctions pour la section publiques

	public function stpi_affPublic($nnbQuantity = 0)
	{
		
		if ($nnbQuantity != 0)
		{
			if (!$arrID = $this->stpi_selAll($nnbQuantity))
			{
				return false;
			}
		}
		else
		{
			if (!$arrID = $this->stpi_selAll())
			{
				return false;
			}
		}
		
		foreach ($arrID as $nbNewsID)
		{
			$this->stpi_setNbID($nbNewsID);
			$this->stpi_setObjNewsLgFromBdd();
			print("<h1 class=\"news\">" . $this->objBdd->stpi_trsBddToHTML($this->objNewsLg->stpi_getStrTitre()) . "</h1>\n");
			print("<span class=\"news\">" . $this->objBdd->stpi_trsBddToHTML($this->dtEntryDate) . "</span>\n");
			print("<p>\n");
			print($this->objBdd->stpi_trsBddToBBCodeHTML($this->objNewsLg->stpi_getStrNews()) . "\n");
			print("</p><br/>\n");
		}
	}

}
?>
