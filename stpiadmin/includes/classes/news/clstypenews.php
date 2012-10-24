<?php
require_once(dirname(__FILE__) . "/clstypenewslg.php");
class clstypenews
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeNewsLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjTypeNewsLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypenews");
		$this->objLang = new clslang();
		$this->objTypeNewsLg = new clstypenewslg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolDelete = 1;
			$this->arrObjTypeNewsLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjTypeNewsLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeNewsID", "stpi_news_TypeNews"))
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

		$SQL = "SELECT boolDelete FROM stpi_news_TypeNews WHERE nbTypeNewsID=" . $this->nbID;
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
	
	public function stpi_setArrObjTypeNewsLgFromBdd()
	{
		if (!$this->objTypeNewsLg->stpi_setNbTypeNewsID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypeNewsId = $this->objTypeNewsLg->stpi_selNbTypeNewsID())
		{
			return false;
		}
		foreach ($arrNbTypeNewsId as $strLg => $nbTypeNewsLgID)
		{
			if (!$this->arrObjTypeNewsLg[$strLg] = new clsTypeNewslg($nbTypeNewsLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjTypeNewsLgFromBdd()
	{
		if (!$this->objTypeNewsLg->stpi_setNbTypeNewsID($this->nbID))
		{
			return false;
		}
		if (!$nbTypeNewsLgId = $this->objTypeNewsLg->stpi_selNbTypeNewsIDLG())
		{
			return false;
		}
		if (!$this->objTypeNewsLg->stpi_setNbID($nbTypeNewsLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getObjTypeNewsLg()
	{
		return $this->objTypeNewsLg;
	}
	
	public function stpi_getArrObjTypeNewsLg()
	{
		return $this->arrObjTypeNewsLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_news_TypeNews (nbTypeNewsID, boolDelete) VALUES (NULL, 1)";
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
	
	public function stpi_delete($nnbTypeNewsID)
	{
		if (!$this->stpi_chkNbID($nnbTypeNewsID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_news_TypeNews WHERE nbTypeNewsID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeNewsID);
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
		function stpi_SearchTypeNews(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeNews").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeNews").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "newstypenewssaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeNews").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typenews") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypeNews(this.value)\" maxlength=\"50\" size=\"20\" id=\"strName\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypeNews\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypeNews()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypeNewsAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypeNews").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeNewsName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeNewsDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./newstypenews.php?l=" + "<?php print(LG); ?>" + "&nbTypeNewsID=";
		  				var nbTypeNewsIDIndex = xmlHttp.responseText.indexOf("nbTypeNewsID") + 13;
		  				var nbTypeNewsIDLen = xmlHttp.responseText.length - nbTypeNewsIDIndex;
		  				var nbTypeNewsID = xmlHttp.responseText.substr(nbTypeNewsIDIndex, nbTypeNewsIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeNewsID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypeNews").style.visibility = "visible";
			  			document.getElementById("stpi_TypeNewsAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "newstypenewsadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeNewsName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"5\" cols=\"50\" id=\"strTypeNewsDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypeNewsAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypeNews\" type=\"button\" onclick=\"stpi_addTypeNews()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypeNewsName" + strLg[i]).disabled = false;
				document.getElementById("strTypeNewsDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypeNews()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypeNewsID=" + encodeURIComponent(document.getElementById("nbTypeNewsID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeNewsName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeNewsDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./newstypenews.php?l=" + "<?php print(LG); ?>" + "&nbTypeNewsID=";
		  				var nbTypeNewsIDIndex = xmlHttp.responseText.indexOf("nbTypeNewsID") + 13;
		  				var nbTypeNewsIDLen = xmlHttp.responseText.length - nbTypeNewsIDIndex;
		  				var nbTypeNewsID = xmlHttp.responseText.substr(nbTypeNewsIDIndex, nbTypeNewsIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeNewsID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "newstypenewsedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeNewsName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeNewsLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"5\" cols=\"50\" id=\"strTypeNewsDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeNewsLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypeNews()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypeNews()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypeNews()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "newstypenewsdel.php?nbTypeNewsID=" + document.getElementById("nbTypeNewsID").value;
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
		function stpi_delTypeNewsConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "newstypenewsdel.php?nbTypeNewsID=" + document.getElementById("nbTypeNewsID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delTypeNewsConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_news_TypeNews.nbTypeNewsID FROM stpi_news_TypeNews, stpi_news_TypeNews_Lg";
		$SQL .= " WHERE stpi_news_TypeNews.nbTypeNewsID=stpi_news_TypeNews_Lg.nbTypeNewsID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeNewsID"];
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
		$SQL = "SELECT DISTINCT stpi_news_TypeNews.nbTypeNewsID FROM stpi_news_News, stpi_news_TypeNews, stpi_news_TypeNews_Lg";
		$SQL .= " WHERE stpi_news_TypeNews.nbTypeNewsID=stpi_news_TypeNews_Lg.nbTypeNewsID";
		$SQL .= " AND stpi_news_TypeNews.nbTypeNewsID=stpi_news_News.nbTypeNewsID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeNewsID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbNewsID()
	{
		$arrID = array();
		$SQL = "SELECT stpi_news_News.nbNewsID FROM stpi_news_News, stpi_news_News_Lg";
		$SQL .= " WHERE stpi_news_News.nbNewsID=stpi_news_News_Lg.nbNewsID";
		$SQL .= " AND nbTypeNewsID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY dtEntryDate";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
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
}
?>
