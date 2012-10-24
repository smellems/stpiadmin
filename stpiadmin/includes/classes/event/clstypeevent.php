<?php
require_once(dirname(__FILE__) . "/clstypeeventlg.php");
class clstypeevent
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeEventLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjTypeEventLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypeevent");
		$this->objLang = new clslang();
		$this->objTypeEventLg = new clstypeeventlg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->arrObjTypeEventLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjTypeEventLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeEventID", "stpi_event_TypeEvent"))
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
		
		$SQL = "SELECT boolDelete FROM stpi_event_TypeEvent WHERE nbTypeEventID=" . $this->nbID;
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
	
	public function stpi_setArrObjTypeEventLgFromBdd()
	{
		if (!$this->objTypeEventLg->stpi_setNbTypeEventID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypeEventId = $this->objTypeEventLg->stpi_selNbTypeEventID())
		{
			return false;
		}
		foreach ($arrNbTypeEventId as $strLg => $nbTypeEventLgID)
		{
			if (!$this->arrObjTypeEventLg[$strLg] = new clsTypeEventlg($nbTypeEventLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjTypeEventLgFromBdd()
	{
		if (!$this->objTypeEventLg->stpi_setNbTypeEventID($this->nbID))
		{
			return false;
		}
		if (!$nbTypeEventLgId = $this->objTypeEventLg->stpi_selNbTypeEventIDLG())
		{
			return false;
		}
		if (!$this->objTypeEventLg->stpi_setNbID($nbTypeEventLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getObjTypeEventLg()
	{
		return $this->objTypeEventLg;
	}
	
	public function stpi_getArrObjTypeEventLg()
	{
		return $this->arrObjTypeEventLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_event_TypeEvent (nbTypeEventID, boolDelete) VALUES (NULL, 1)";
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
	
	public function stpi_delete($nnbTypeEventID)
	{
		if (!$this->stpi_chkNbID($nnbTypeEventID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_event_TypeEvent WHERE nbTypeEventID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeEventID);
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
		function stpi_SearchTypeEvent(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeEvent").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeEvent").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventtypeeventsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeEvent").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typeevent") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypeEvent(this.value)\" maxlength=\"50\" size=\"20\" id=\"strName\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypeEvent\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypeEvent()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypeEventAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypeEvent").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeEventName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeEventDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./eventtypeevent.php?l=" + "<?php print(LG); ?>" + "&nbTypeEventID=";
		  				var nbTypeEventIDIndex = xmlHttp.responseText.indexOf("nbTypeEventID") + 14;
		  				var nbTypeEventIDLen = xmlHttp.responseText.length - nbTypeEventIDIndex;
		  				var nbTypeEventID = xmlHttp.responseText.substr(nbTypeEventIDIndex, nbTypeEventIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeEventID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypeEvent").style.visibility = "visible";
			  			document.getElementById("stpi_TypeEventAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "eventtypeeventadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeEventName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strTypeEventDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypeEventAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypeEvent\" type=\"button\" onclick=\"stpi_addTypeEvent()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypeEventName" + strLg[i]).disabled = false;
				document.getElementById("strTypeEventDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypeEvent()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypeEventID=" + encodeURIComponent(document.getElementById("nbTypeEventID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeEventName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeEventDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./eventtypeevent.php?l=" + "<?php print(LG); ?>" + "&nbTypeEventID=";
		  				var nbTypeEventIDIndex = xmlHttp.responseText.indexOf("nbTypeEventID") + 14;
		  				var nbTypeEventIDLen = xmlHttp.responseText.length - nbTypeEventIDIndex;
		  				var nbTypeEventID = xmlHttp.responseText.substr(nbTypeEventIDIndex, nbTypeEventIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeEventID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "eventtypeeventedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeEventName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeEventLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strTypeEventDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeEventLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypeEvent()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypeEvent()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypeEvent()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventtypeeventdel.php?nbTypeEventID=" + document.getElementById("nbTypeEventID").value;
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
		function stpi_delTypeEventConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventtypeeventdel.php?nbTypeEventID=" + document.getElementById("nbTypeEventID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./events.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delTypeEventConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_event_TypeEvent.nbTypeEventID FROM stpi_event_TypeEvent, stpi_event_TypeEvent_Lg";
		$SQL .= " WHERE stpi_event_TypeEvent.nbTypeEventID=stpi_event_TypeEvent_Lg.nbTypeEventID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeEventID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbEventID($nnbAdresseID = 0)
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_event_Event.nbEventID FROM stpi_event_Event, stpi_event_Event_Lg, stpi_event_DateHeure";
		$SQL .= " WHERE stpi_event_Event.nbEventID=stpi_event_Event_Lg.nbEventID";
		$SQL .= " AND stpi_event_Event.nbEventID=stpi_event_DateHeure.nbEventID";
		$SQL .= " AND nbTypeEventID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		if ($nnbAdresseID != 0)
		{
			$SQL .= " AND nbAdresseID=" . $this->objBdd->stpi_trsInputToBdd($nnbAdresseID);
		}
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " AND dtDebut >= NOW() - INTERVAL 7 DAY";
		$SQL .= " ORDER BY dtDebut";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbEventID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbAdresseID()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_event_Adresse.nbAdresseID FROM stpi_event_Adresse, stpi_event_Event";
		$SQL .= " WHERE nbTypeEventID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " AND stpi_event_Adresse.nbAdresseID=stpi_event_Event.nbAdresseID";
		$SQL .= " ORDER BY strCountryID, strProvinceID, strVille";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbAdresseID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbAdresseIDPublic()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_event_Adresse.nbAdresseID FROM stpi_event_Adresse, stpi_event_Event, stpi_event_DateHeure";
		$SQL .= " WHERE nbTypeEventID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " AND stpi_event_Adresse.nbAdresseID=stpi_event_Event.nbAdresseID";
		$SQL .= " AND stpi_event_Event.nbEventID=stpi_event_DateHeure.nbEventID";
		$SQL .= " AND dtDebut >= NOW() - INTERVAL 7 DAY";
		$SQL .= " ORDER BY strCountryID, strProvinceID, strVille";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbAdresseID"];
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