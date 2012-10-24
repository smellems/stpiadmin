<?php
require_once(dirname(__FILE__) . "/../date/clsdate.php");

class clseventdateheure
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objDate;
	
	private $nbID;
	private $nbEventID;
	private $dtDebut;
	private $dtFin;
	
	public function __construct($nnbDateHeureID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txteventdateheure");
		$this->objLang = new clslang();
		$this->objDate = new clsdate();
		if ($nnbDateHeureID == 0)
		{
			$this->nbID = 0;
			$this->nbEventID = 0;
			$this->dtDebut = "";
			$this->dtFin = "";
		}
		else
		{
			if(!$this->stpi_setNbID($nnbDateHeureID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkNbID($nnbDateHeureID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbDateHeureID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbDateHeureID, "nbDateHeureID", "stpi_event_DateHeure"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbEventID($nnbEventID)
	{
		if (!is_numeric($nnbEventID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalideventid") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}

		if (!$this->objBdd->stpi_chkExists($nnbEventID, "nbEventID", "stpi_event_Event"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalideventid") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkDtDebut($ndtDebut)
	{
		list($dtDate, $dtHeure) = explode(" ", $ndtDebut);
		if (!$this->objDate->stpi_chkDateISO($dtDate))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddebut") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objDate->stpi_chkHeureISO($dtHeure))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddebut") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkDtFin($ndtFin)
	{
		list($dtDate, $dtHeure) = explode(" ", $ndtFin);
		if (!$this->objDate->stpi_chkDateISO($dtDate))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidfin") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objDate->stpi_chkHeureISO($dtHeure))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidfin") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_setNbID($nnbDateHeureID)
	{

		if (!$this->stpi_chkNbID($nnbDateHeureID))
		{
			return false;
		}
		$this->nbID = $nnbDateHeureID;

		$SQL = "SELECT nbEventID, dtDebut, dtFin";
		$SQL .= " FROM stpi_event_DateHeure WHERE nbDateHeureID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbEventID = $row["nbEventID"];
				$this->dtDebut = $row["dtDebut"];
				$this->dtFin = $row["dtFin"];
				$this->strSousItemDesc = $row["strSousItemDesc"];
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
	
	public function stpi_setNbEventID($nnbEventID)
	{
		if (!$this->stpi_chkNbEventID($nnbEventID))
		{
			return false;
		}
		$this->nbEventID = $nnbEventID;
		return true;
	}
	
	public function stpi_setDtDebut($ndtDebut)
	{
		if (!$this->stpi_chkDtDebut($ndtDebut))
		{
			return false;
		}
		$this->dtDebut = $ndtDebut;
		return true;
	}
	
	public function stpi_setDtFin($ndtFin)
	{
		if (!$this->stpi_chkDtFin($ndtFin))
		{
			return false;
		}
		$this->dtFin = $ndtFin;
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	public function stpi_getNbEventID()
	{
		return $this->nbEventID;
	}
	public function stpi_getDtDebut()
	{
		return $this->dtDebut;
	}
	public function stpi_getDtFin()
	{
		return $this->dtFin;
	}
	
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	public function stpi_getObjDate()
	{
		return $this->objDate;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}		
		$SQL = "INSERT INTO stpi_event_DateHeure (nbEventID, dtDebut, dtFin)";
		$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbEventID);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->dtDebut) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->dtFin) . "')";
		if ($this->objBdd->stpi_insert($SQL))
		{
			return true;
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
		$SQL = "UPDATE stpi_event_DateHeure";
		$SQL .= " SET nbEventID=" . $this->objBdd->stpi_trsInputToBdd($this->nbEventID);
		$SQL .= ", dtDebut='" . $this->objBdd->stpi_trsInputToBdd($this->dtDebut) . "'";
		$SQL .= ", dtFin='" . $this->objBdd->stpi_trsInputToBdd($this->dtFin) . "'";
		$SQL .= " WHERE nbDateHeureID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
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
	
	public function stpi_deleteEventID($nnbEventID)
	{
		if (!$this->stpi_chkNbEventID($nnbEventID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_event_DateHeure WHERE nbEventID=" . $this->objBdd->stpi_trsInputToBdd($nnbEventID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(nbEventID)</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_delete($nnbDateHeureID)
	{
		if (!$this->stpi_chkNbID($nnbDateHeureID))
		{
			return false;
		}

		$SQL = "DELETE FROM stpi_event_DateHeure WHERE nbDateHeureID=" . $this->objBdd->stpi_trsInputToBdd($nnbDateHeureID);
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
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			document.getElementById("dtDebut").disabled = false;
			document.getElementById("dtFin").disabled = false;
			
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editDateHeure()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject[stpi_chkLang()];
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbDateHeureID=" + encodeURIComponent(document.getElementById("nbDateHeureID").value);
			strParam = strParam + "&dtDebut=" + encodeURIComponent(document.getElementById("dtDebut").value);
			strParam = strParam + "&dtFin=" + encodeURIComponent(document.getElementById("dtFin").value);
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./eventdateheure.php?l=" + "<?php print(LG) ?>" + "&nbDateHeureID=" + encodeURIComponent(document.getElementById("nbDateHeureID").value);
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "eventdateheureedit.php", true);
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
		if ($this->nbID == 0)
		{
			return false;
		}
		print("<input type=\"hidden\" id=\"nbDateHeureID\" value=\"" . $this->nbID . "\" />\n");
		print("<input type=\"hidden\" id=\"nbEventID\" value=\"" . $this->nbEventID . "\" />\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("dateheure") . "<br/>\n");

		print($this->objTexte->stpi_getArrTxt("debut") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->dtDebut) . "\" id=\"dtDebut\"/><br/>\n");

		print($this->objTexte->stpi_getArrTxt("fin") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->dtFin) . "\" id=\"dtFin\"/><br/>\n");
		
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editDateHeure()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delDateHeure()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" />\n");
		print("</p>\n");
	}
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addDateHeure()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_DateHeureAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddDateHeure").style.visibility = "hidden";
			var strParam = "nbEventID=" + encodeURIComponent(document.getElementById("nbEventID").value);
			strParam = strParam + "&dtDebut=" + encodeURIComponent(document.getElementById("dtDebut").value);
			strParam = strParam + "&dtFin=" + encodeURIComponent(document.getElementById("dtFin").value);
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
			  			document.getElementById("stpi_AddDateHeure").style.visibility = "visible";
			  			document.getElementById("stpi_DateHeureAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "eventdateheureadd.php", true);
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
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("dateheure") . "<br/>\n");

		print($this->objTexte->stpi_getArrTxt("debut") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"\" id=\"dtDebut\"/><br/>\n");

		print($this->objTexte->stpi_getArrTxt("fin") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"\" id=\"dtFin\"/><br/>\n");
		
		print("<span id=\"stpi_DateHeureAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddDateHeure\" type=\"button\" onclick=\"stpi_addDateHeure()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delDateHeure()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventdateheuredel.php?nbDateHeureID=" + encodeURIComponent(document.getElementById("nbDateHeureID").value);
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
		function stpi_delDateHeureConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventdateheuredel.php?nbDateHeureID=" + encodeURIComponent(document.getElementById("nbDateHeureID").value);
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./event.php?l=" + "<?php print(LG); ?>" + "&nbEventID=" + document.getElementById("nbEventID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delDateHeureConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
}
?>