<?php
require_once(dirname(__FILE__) . "/clsmotdlg.php");
class clsmotd
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objMotdLg;
	
	private $nbID;
	private $boolRouge;
	private $dtEntryDate;
	
	private $arrObjMotdLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtmotd");
		$this->objLang = new clslang();
		$this->objMotdLg = new clsmotdlg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolRouge = 0;
			$this->arrObjMotdLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjMotdLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbMotdID", "stpi_motd_Motd"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkBoolRouge($nboolRouge)
	{
		if ($nboolRouge != "1" && $nboolRouge != "0")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidrouge") . "</span><br/>\n");
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
		
		$SQL = "SELECT boolRouge, dtEntryDate FROM stpi_motd_Motd WHERE nbMotdID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->dtEntryDate = $row["dtEntryDate"];
				$this->boolRouge = $row["boolRouge"];
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
	
	public function stpi_setBoolRouge($nboolRouge)
	{
		if (!$this->stpi_chkBoolRouge($nboolRouge))
		{
			return false;				
		}
		if ($nboolRouge != "1")
		{
			$this->boolRouge = 0;
		}
		else
		{
			$this->boolRouge = 1;
		}
		return true;
	}
	
	public function stpi_setArrObjMotdLgFromBdd()
	{
		if (!$this->objMotdLg->stpi_setNbMotdID($this->nbID))
		{
			return false;
		}
		if (!$arrNbMotdId = $this->objMotdLg->stpi_selNbMotdID())
		{
			return false;
		}
		foreach ($arrNbMotdId as $strLg => $nbMotdLgID)
		{
			if (!$this->arrObjMotdLg[$strLg] = new clsmotdlg($nbMotdLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjMotdLgFromBdd()
	{
		$SQL = "SELECT nbMotdLgID FROM stpi_motd_Motd_Lg WHERE nbMotdID=" . $this->nbID . " AND strLg='" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$row = mysql_fetch_assoc($result);
			if (!$this->objMotdLg->stpi_setNbID($row["nbMotdLgID"]))
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
	
	public function stpi_getDtEntryDate()
	{
		return $this->dtEntryDate;
	}
	
	public function stpi_getboolRouge()
	{
		return $this->boolRouge;
	}
	
	public function stpi_getObjMotdLg()
	{
		return $this->objMotdLg;
	}
	
	public function stpi_getArrObjMotdLg()
	{
		return $this->arrObjMotdLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_motd_Motd (boolRouge, dtEntryDate)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->boolRouge) . "', NOW())";
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
		$SQL = "UPDATE stpi_motd_Motd";
		$SQL .= " SET boolRouge='" . $this->objBdd->stpi_trsInputToBdd($this->boolRouge) . "'";
		$SQL .= " WHERE nbMotdID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbMotdID)
	{
		if (!$this->stpi_chkNbID($nnbMotdID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_motd_Motd WHERE nbMotdID=" . $this->objBdd->stpi_trsInputToBdd($nnbMotdID);
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
	
	
	public function stpi_affPublic()
	{
		if (!$arrNbMotdID = $this->stpi_selAll())
		{
			return false;
		}
	
		print("<div class=\"motd\" >");
		foreach ($arrNbMotdID as $nbMotdID)
		{
			if ($this->stpi_setNbID($nbMotdID))
			{
				$this->stpi_setObjMotdLgFromBdd();
				print("<p>\n");
				if ($this->boolRouge)
				{
					print("<span style=\"color:#FF0000;\">" . $this->objBdd->stpi_trsBddToHTML($this->objMotdLg->stpi_getStrMotd()) . "</span>\n");
				}
				else
				{
					print($this->objBdd->stpi_trsBddToHTML($this->objMotdLg->stpi_getStrMotd()) . "\n");
				}
				print("</p>\n");
			}			
		}
		print("</div>\n");
		
		return true;
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addMotd()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_MotdAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddMotd").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
				strParam = strParam + "&strMotd" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMotd" + strLg[i]).value);
			}
			if (document.getElementById("chkRouge").checked)
			{
				strParam = strParam + "&rouge=1";
			}
			else
			{
				strParam = strParam + "&rouge=0";
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./motd.php?l=" + "<?php print(LG); ?>" + "&nbMotdID=";
		  				var nbMotdIDIndex = xmlHttp.responseText.indexOf("nbMotdID") + 9;
		  				var nbMotdIDLen = xmlHttp.responseText.length - nbMotdIDIndex;
		  				var nbMotdID = xmlHttp.responseText.substr(nbMotdIDIndex, nbMotdIDIndex);
		  				strUrlRedirect = strUrlRedirect + nbMotdID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_AddMotd").style.visibility = "visible";
		  				document.getElementById("stpi_MotdAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "motdadd.php", true);
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
			print($this->objTexte->stpi_getArrTxt("motd") . " " . $strLg . "<br/>\n");
			print("<textarea rows=\"8\" cols=\"75\" id=\"strMotd" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<input id=\"chkRouge\" type=\"checkbox\"/>&nbsp;" . $this->objTexte->stpi_getArrTxt("rouge"));
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_MotdAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddMotd\" type=\"button\" onclick=\"stpi_addMotd()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strMotd" + strLg[i]).disabled = false;
			}
			document.getElementById("chkRouge").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editMotd()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbMotdID=" + encodeURIComponent(document.getElementById("nbMotdID").value);
			for (i in strLg)
			{
				strParam = strParam + "&strMotd" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strMotd" + strLg[i]).value);
			}
			if (document.getElementById("chkRouge").checked)
			{
				strParam = strParam + "&rouge=1";
			}
			else
			{
				strParam = strParam + "&rouge=0";
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./motd.php?l=" + "<?php print(LG); ?>" + "&nbMotdID=";
		  				var nbMotdIDIndex = xmlHttp.responseText.indexOf("nbMotdID") + 9;
		  				var nbMotdIDLen = xmlHttp.responseText.length - nbMotdIDIndex;
		  				var nbMotdID = xmlHttp.responseText.substr(nbMotdIDIndex, nbMotdIDLen);
		  				strUrlRedirect = strUrlRedirect + nbMotdID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "motdedit.php", true);
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
			print($this->objTexte->stpi_getArrTxt("motd") . " " . $strLg . "<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"8\" cols=\"75\" id=\"strMotd" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjMotdLg[$strLg]->stpi_getStrMotd()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<input disabled=\"disabled\" id=\"chkRouge\" type=\"checkbox\"");
		if ($this->boolRouge == 1)
		{
			print(" checked=\"checked\"");
		}
		print("/>&nbsp;" . $this->objTexte->stpi_getArrTxt("rouge"));
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editMotd()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delMotd()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delMotd()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			var strUrl = "motddel.php?nbMotdID=" + document.getElementById("nbMotdID").value;
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
		function stpi_delMotdConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "motddel.php?nbMotdID=" + document.getElementById("nbMotdID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./motds.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delMotdConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT nbMotdID FROM stpi_motd_Motd";
		$SQL .= " ORDER BY boolRouge DESC, dtEntryDate DESC";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbMotdID"];
			}
			mysql_free_result($result);
		}
		else
		{
			// print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return $arrID;
	}
}
?>