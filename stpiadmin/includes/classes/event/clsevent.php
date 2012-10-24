<?php
require_once(dirname(__FILE__) . "/clseventlg.php");
require_once(dirname(__FILE__) . "/clstypeevent.php");
require_once(dirname(__FILE__) . "/clseventadresse.php");
require_once(dirname(__FILE__) . "/clseventdateheure.php");
require_once(dirname(__FILE__) . "/../img/clsimg.php");
class clsevent
{
	private $nbImgWidthMax = 200;
	private $nbImgHeightMax = 100;
	
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objEventLg;
	private $objImg;
	private $objTypeEvent;
	private $objAdresse;
	private $objDateHeure;
	
	private $nbID;
	private $nbTypeEventID;
	private $nbAdresseID;
	private $nbImageID;
	
	private $arrObjEventLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtevent");
		$this->objLang = new clslang();
		$this->objEventLg = new clseventlg();
		$this->objImg = new clsimg("stpi_event_ImgEvent");
		$this->objTypeEvent = new clstypeevent();
		$this->objAdresse = new clseventadresse();
		$this->objDateHeure = new clseventdateheure();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbTypeEventID = 0;
			$this->nbAdresseID = 0;
			$this->nbImageID = 0;
			$this->arrObjEventLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjEventLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbEventID", "stpi_event_Event"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbImageID($nnbImageID)
	{
		if (!$this->objImg->stpi_chkNbID($nnbImageID))
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
		
		$SQL = "SELECT nbTypeEventID, nbAdresseID, nbImageID FROM stpi_event_Event WHERE nbEventID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeEventID = $row["nbTypeEventID"];
				$this->nbAdresseID = $row["nbAdresseID"];
				$this->nbImageID = $row["nbImageID"];
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
	
	public function stpi_setNbTypeEventID($nnbTypeEventID)
	{
		if (!$this->objTypeEvent->stpi_chkNbID($nnbTypeEventID))
		{
			return false;				
		}
		$this->nbTypeEventID = $nnbTypeEventID;
		return true;
	}
	
	public function stpi_setNbAdresseID($nnbAdresseID)
	{
		if (!$this->objAdresse->stpi_chkNbID($nnbAdresseID))
		{
			return false;				
		}
		$this->nbAdresseID = $nnbAdresseID;
		return true;
	}
	
	public function stpi_setNbImageID($nnbImageID)
	{
		if (!$this->stpi_chkNbImageID($nnbImageID))
		{
			return false;				
		}
		$this->nbImageID = $nnbImageID;
		return true;
	}
	
	public function stpi_setArrObjEventLgFromBdd()
	{
		if (!$this->objEventLg->stpi_setNbEventID($this->nbID))
		{
			return false;
		}
		if (!$arrNbEventId = $this->objEventLg->stpi_selNbEventID())
		{
			return false;
		}
		foreach ($arrNbEventId as $strLg => $nbEventLgID)
		{
			if (!$this->arrObjEventLg[$strLg] = new clsEventlg($nbEventLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjEventLgFromBdd()
	{
		if (!$this->objEventLg->stpi_setNbEventID($this->nbID))
		{
			return false;
		}
		if (!$nbEventLgId = $this->objEventLg->stpi_selNbEventIDLG())
		{
			return false;
		}
		if (!$this->objEventLg->stpi_setNbID($nbEventLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbTypeEventID()
	{
		return $this->nbTypeEventID;
	}
	
	public function stpi_getNbAdresseID()
	{
		return $this->nbAdresseID;
	}
	
	public function stpi_getNbImageID()
	{
		return $this->nbImageID;
	}
	
	public function stpi_getNbImgWidthMax()
	{
		return $this->nbImgWidthMax;
	}
	
	public function stpi_getNbImgHeightMax()
	{
		return $this->nbImgHeightMax;
	}
	
	public function stpi_getObjEventLg()
	{
		return $this->objEventLg;
	}
	
	public function stpi_getObjTypeEvent()
	{
		return $this->objTypeEvent;
	}
	
	public function stpi_getObjAdresse()
	{
		return $this->objAdresse;
	}
	
	public function stpi_getObjDateHeure()
	{
		return $this->objDateHeure;
	}
	
	public function stpi_getObjImg()
	{
		return $this->objImg;
	}
	
	public function stpi_getArrObjEventLg()
	{
		return $this->arrObjEventLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_event_Event (nbTypeEventID, nbAdresseID, nbImageID) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeEventID) . ", " . $this->objBdd->stpi_trsInputToBdd($this->nbAdresseID) . ", 0)";
		print($SQL);
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
		
		$SQL = "UPDATE stpi_event_Event";
		$SQL .= " SET nbTypeEventID='" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeEventID) . "'";
		$SQL .= ", nbAdresseID='" . $this->objBdd->stpi_trsInputToBdd($this->nbAdresseID) . "'";
		$SQL .= ", nbImageID='" . $this->objBdd->stpi_trsInputToBdd($this->nbImageID) . "'";
		$SQL .= " WHERE nbEventID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbEventID)
	{
		if (!$this->stpi_chkNbID($nnbEventID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_event_Event WHERE nbEventID=" . $this->objBdd->stpi_trsInputToBdd($nnbEventID);
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
		function stpi_SearchEvent(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affEvent").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affEvent").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affEvent").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("event") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchEvent(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affEvent\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addEvent()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_EventAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddEvent").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strEventName" + strLg[i]).value);
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLien" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strEventDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeEventID=" + encodeURIComponent(document.getElementById("nbTypeEventID").value);
			strParam = strParam + "&nbAdresseID=" + encodeURIComponent(document.getElementById("nbAdresseID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./event.php?l=" + "<?php print(LG); ?>" + "&nbEventID=";
		  				var nbEventIDIndex = xmlHttp.responseText.indexOf("nbEventID") + 10;
		  				var nbEventIDLen = xmlHttp.responseText.length - nbEventIDIndex;
		  				var nbEventID = xmlHttp.responseText.substr(nbEventIDIndex, nbEventIDLen);
		  				strUrlRedirect = strUrlRedirect + nbEventID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddEvent").style.visibility = "visible";
			  			document.getElementById("stpi_EventAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "eventadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strEventName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strLien" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strEventDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typeevent") . "<br/>\n");				
		print("<select id=\"nbTypeEventID\">\n");
		print("<option selected=\"selected\" value=\"\"></option>\n");
		if ($arrNbTypeEventID = $this->objTypeEvent->stpi_selAll())
		{
			foreach($arrNbTypeEventID as $nbTypeEventID)
			{
				if ($this->objTypeEvent->stpi_setNbID($nbTypeEventID))
				{
					if ($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_setNbTypeEventID($nbTypeEventID))
					{
						if ($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_setNbID($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_selNbTypeEventIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeEventID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("adresse") . "<br/>\n");				
		print("<select id=\"nbAdresseID\">\n");
		print("<option selected=\"selected\" value=\"\"></option>\n");
		if ($arrNbAdresseID = $this->objAdresse->stpi_selAll())
		{
			foreach($arrNbAdresseID as $nbAdresseID)
			{
				if ($this->objAdresse->stpi_setNbID($nbAdresseID))
				{
					print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbAdresseID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrEndroit()) . " - " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrAdresse()) . ", " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrVille()) . ", " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrProvinceID()) . ", " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrCountryID()) . "</option>\n");
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_EventAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddEvent\" type=\"button\" onclick=\"stpi_addEvent()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strEventName" + strLg[i]).disabled = false;
				document.getElementById("strLien" + strLg[i]).disabled = false;
				document.getElementById("strEventDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("nbTypeEventID").disabled = false;
			document.getElementById("nbAdresseID").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editEvent()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbEventID=" + encodeURIComponent(document.getElementById("nbEventID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strEventName" + strLg[i]).value);
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLien" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strEventDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeEventID=" + encodeURIComponent(document.getElementById("nbTypeEventID").value);
			strParam = strParam + "&nbAdresseID=" + encodeURIComponent(document.getElementById("nbAdresseID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./event.php?l=" + "<?php print(LG); ?>" + "&nbEventID=";
		  				var nbEventIDIndex = xmlHttp.responseText.indexOf("nbEventID") + 10;
		  				var nbEventIDLen = xmlHttp.responseText.length - nbEventIDIndex;
		  				var nbEventID = xmlHttp.responseText.substr(nbEventIDIndex, nbEventIDLen);
		  				strUrlRedirect = strUrlRedirect + nbEventID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "eventedit.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affImgAdd()
	{
		print("<form method=\"post\" action=\"./eventimgadd.php?l=" . LG);
		print("&amp;nbEventID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
		print("&amp;op=save\" enctype=\"multipart/form-data\">");	
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("path") . "<br/>\n");	
		print("<input type=\"file\" name=\"blobImage\" id=\"blobImage\"/><br/>\n");
		print("</p>\n");
		print("<p>\n");
		print("<input type=\"submit\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\"/><br/>\n");
		print("</p>\n");
		
		print("</form>\n");
	}
	
	public function stpi_affImgEdit()
	{
		print("<form method=\"post\" action=\"./eventimgedit.php?l=" . LG);
		print("&amp;nbEventID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
		print("&amp;op=save\" enctype=\"multipart/form-data\">");	
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("path") . "<br/>\n");	
		print("<input type=\"file\" name=\"blobImage\" id=\"blobImage\"/><br/>\n");
		print("</p>\n");
		print("<p>\n");
		print("<input type=\"submit\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\"/><br/>\n");
		print("</p>\n");
		
		print("</form>\n");
	}
	
	public function stpi_affEdit()
	{
		if ($this->nbImageID != 0 && $this->objImg->stpi_setnbID($this->nbImageID))
		{
			print("<img alt=\"" . $this->nbImageID . "\" src=\"./eventimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->nbImageID . "\"/><br/>\n");
			print("<a href=\"./eventimgedit.php?l=" . LG . "&amp;nbEventID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("editimg") . "</a><br/>\n");
		}
		else
		{
			print("<a href=\"./eventimgadd.php?l=" . LG . "&amp;nbEventID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("addimg") . "</a><br/>\n");
		}
		
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		foreach ($arrLang as $strLg)	
		{
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strEventName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjEventLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strLien" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjEventLg[$strLg]->stpi_getStrLien()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strEventDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjEventLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typeevent") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbTypeEventID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeEventID = $this->objTypeEvent->stpi_selAll())
		{
			foreach($arrNbTypeEventID as $nbTypeEventID)
			{
				if ($this->objTypeEvent->stpi_setNbID($nbTypeEventID))
				{
					if ($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_setNbTypeEventID($nbTypeEventID))
					{
						if ($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_setNbID($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_selNbTypeEventIDLG()))
						{
							print("<option");
							if ($this->nbTypeEventID == $nbTypeEventID)
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeEventID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeEvent->stpi_getObjTypeEventLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("adresse") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbAdresseID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbAdresseID = $this->objAdresse->stpi_selAll())
		{
			foreach($arrNbAdresseID as $nbAdresseID)
			{
				if ($this->objAdresse->stpi_setNbID($nbAdresseID))
				{
					print("<option");
					if ($this->nbAdresseID == $nbAdresseID)
					{
						print(" selected=\"selected\"");
					}
					print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbAdresseID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrEndroit()) . " - " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrAdresse()) . ", " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrVille()) . ", " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrProvinceID()) . ", " . $this->objBdd->stpi_trsBddToHTML($this->objAdresse->stpi_getStrCountryID()) . "</option>\n");
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editEvent()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delEvent()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delEvent()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventdel.php?nbEventID=" + document.getElementById("nbEventID").value;
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
		function stpi_delEventConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventdel.php?nbEventID=" + document.getElementById("nbEventID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delEventConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_selNbDateHeureID()
	{
		$arrID = array();
		$SQL = "SELECT nbDateHeureID FROM stpi_event_DateHeure";
		$SQL .= " WHERE nbEventID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " ORDER BY dtDebut";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbDateHeureID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbDateHeureIDPublic()
	{
		$arrID = array();
		$SQL = "SELECT nbDateHeureID FROM stpi_event_DateHeure";
		$SQL .= " WHERE nbEventID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " AND dtDebut >= NOW() - INTERVAL 7 DAY";
		$SQL .= " ORDER BY dtDebut";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbDateHeureID"];
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