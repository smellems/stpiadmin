<?php
require_once(dirname(__FILE__) . "/clsstatutcommandelg.php");
class clsstatutcommande
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objStatutCommandeLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjStatutCommandeLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtstatutcommande");
		$this->objLang = new clslang();
		$this->objStatutCommandeLg = new clsstatutcommandelg();
		$this->arrObjStatutCommandeLg = array();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->boolDelete = 1;
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbStatutCommandeID", "stpi_commande_StatutCommande"))
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
		
		$SQL = "SELECT boolDelete FROM stpi_commande_StatutCommande WHERE nbStatutCommandeID=" . $this->nbID;
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
	
	public function stpi_setArrObjStatutCommandeLgFromBdd()
	{
		if (!$this->objStatutCommandeLg->stpi_setNbStatutCommandeID($this->nbID))
		{
			return false;
		}
		if (!$arrNbStatutCommandeId = $this->objStatutCommandeLg->stpi_selNbStatutCommandeID())
		{
			return false;
		}
		foreach ($arrNbStatutCommandeId as $strLg => $nbStatutCommandeLgID)
		{
			if (!$this->arrObjStatutCommandeLg[$strLg] = new clsStatutCommandelg($nbStatutCommandeLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjStatutCommandeLgFromBdd()
	{
		if (!$this->objStatutCommandeLg->stpi_setNbStatutCommandeID($this->nbID))
		{
			return false;
		}
		if (!$nbStatutCommandeLgId = $this->objStatutCommandeLg->stpi_selNbStatutCommandeIDLG())
		{
			return false;
		}
		if (!$this->objStatutCommandeLg->stpi_setNbID($nbStatutCommandeLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getObjStatutCommandeLg()
	{
		return $this->objStatutCommandeLg;
	}
	
	public function stpi_getArrObjStatutCommandeLg()
	{
		return $this->arrObjStatutCommandeLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_commande_StatutCommande (nbStatutCommandeID, boolDelete) VALUES (NULL, 1)";
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
	
	public function stpi_delete($nnbStatutCommandeID)
	{
		if (!$this->stpi_chkNbID($nnbStatutCommandeID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_StatutCommande WHERE nbStatutCommandeID=" . $this->objBdd->stpi_trsInputToBdd($nnbStatutCommandeID);
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
		function stpi_SearchStatutCommande(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affStatutCommande").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affStatutCommande").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandestatutcommandesaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affStatutCommande").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("statutcommande") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchStatutCommande(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affStatutCommande\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addStatutCommande()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_StatutCommandeAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddStatutCommande").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strStatutCommandeName" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandestatutcommande.php?l=" + "<?php print(LG); ?>" + "&nbStatutCommandeID=";
		  				var nbStatutCommandeIDIndex = xmlHttp.responseText.indexOf("nbStatutCommandeID") + 19;
		  				var nbStatutCommandeIDLen = xmlHttp.responseText.length - nbStatutCommandeIDIndex;
		  				var nbStatutCommandeID = xmlHttp.responseText.substr(nbStatutCommandeIDIndex, nbStatutCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbStatutCommandeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddStatutCommande").style.visibility = "visible";
			  			document.getElementById("stpi_StatutCommandeAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commandestatutcommandeadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strStatutCommandeName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_StatutCommandeAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddStatutCommande\" type=\"button\" onclick=\"stpi_addStatutCommande()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strStatutCommandeName" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editStatutCommande()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbStatutCommandeID=" + encodeURIComponent(document.getElementById("nbStatutCommandeID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strStatutCommandeName" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandestatutcommande.php?l=" + "<?php print(LG); ?>" + "&nbStatutCommandeID=";
		  				var nbStatutCommandeIDIndex = xmlHttp.responseText.indexOf("nbStatutCommandeID") + 19;
		  				var nbStatutCommandeIDLen = xmlHttp.responseText.length - nbStatutCommandeIDIndex;
		  				var nbStatutCommandeID = xmlHttp.responseText.substr(nbStatutCommandeIDIndex, nbStatutCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbStatutCommandeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commandestatutcommandeedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strStatutCommandeName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjStatutCommandeLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editStatutCommande()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delStatutCommande()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delStatutCommande()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandestatutcommandedel.php?nbStatutCommandeID=" + document.getElementById("nbStatutCommandeID").value;
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
		function stpi_delStatutCommandeConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandestatutcommandedel.php?nbStatutCommandeID=" + document.getElementById("nbStatutCommandeID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandes.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delStatutCommandeConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_commande_StatutCommande.nbStatutCommandeID FROM stpi_commande_StatutCommande, stpi_commande_StatutCommande_Lg";
		$SQL .= " WHERE stpi_commande_StatutCommande.nbStatutCommandeID=stpi_commande_StatutCommande_Lg.nbStatutCommandeID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbStatutCommandeID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbCommandeID()
	{
		$arrID = array();
		$SQL = "SELECT nbCommandeID FROM stpi_commande_Commande";
		$SQL .= " WHERE nbStatutCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " ORDER BY dtEntryDate";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbCommandeID"];
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