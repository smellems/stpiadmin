<?php
require_once(dirname(__FILE__) . "/clstypecommandelg.php");
class clstypecommande
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeCommandeLg;
	
	private $nbID;
	private $boolDelete;
	
	private $arrObjTypeCommandeLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypecommande");
		$this->objLang = new clslang();
		$this->objTypeCommandeLg = new clstypecommandelg();
		$this->arrObjTypeCommandeLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeCommandeID", "stpi_commande_TypeCommande"))
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
		
		$SQL = "SELECT boolDelete FROM stpi_commande_TypeCommande WHERE nbTypeCommandeID=" . $this->nbID;
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
	
	public function stpi_setArrObjTypeCommandeLgFromBdd()
	{
		if (!$this->objTypeCommandeLg->stpi_setNbTypeCommandeID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypeCommandeId = $this->objTypeCommandeLg->stpi_selNbTypeCommandeID())
		{
			return false;
		}
		foreach ($arrNbTypeCommandeId as $strLg => $nbTypeCommandeLgID)
		{
			if (!$this->arrObjTypeCommandeLg[$strLg] = new clsTypeCommandelg($nbTypeCommandeLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjTypeCommandeLgFromBdd()
	{
		if (!$this->objTypeCommandeLg->stpi_setNbTypeCommandeID($this->nbID))
		{
			return false;
		}
		if (!$nbTypeCommandeLgId = $this->objTypeCommandeLg->stpi_selNbTypeCommandeIDLG())
		{
			return false;
		}
		if (!$this->objTypeCommandeLg->stpi_setNbID($nbTypeCommandeLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getObjTypeCommandeLg()
	{
		return $this->objTypeCommandeLg;
	}
	
	public function stpi_getArrObjTypeCommandeLg()
	{
		return $this->arrObjTypeCommandeLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_commande_TypeCommande (nbTypeCommandeID, boolDelete) VALUES (NULL, 1)";
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
	
	public function stpi_delete($nnbTypeCommandeID)
	{
		if (!$this->stpi_chkNbID($nnbTypeCommandeID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_TypeCommande WHERE nbTypeCommandeID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeCommandeID);
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
		function stpi_SearchTypeCommande(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeCommande").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeCommande").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandetypecommandesaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeCommande").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typecommande") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypeCommande(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypeCommande\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypeCommande()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypeCommandeAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypeCommande").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeCommandeName" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandetypecommande.php?l=" + "<?php print(LG); ?>" + "&nbTypeCommandeID=";
		  				var nbTypeCommandeIDIndex = xmlHttp.responseText.indexOf("nbTypeCommandeID") + 17;
		  				var nbTypeCommandeIDLen = xmlHttp.responseText.length - nbTypeCommandeIDIndex;
		  				var nbTypeCommandeID = xmlHttp.responseText.substr(nbTypeCommandeIDIndex, nbTypeCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeCommandeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypeCommande").style.visibility = "visible";
			  			document.getElementById("stpi_TypeCommandeAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commandetypecommandeadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeCommandeName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypeCommandeAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypeCommande\" type=\"button\" onclick=\"stpi_addTypeCommande()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypeCommandeName" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypeCommande()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypeCommandeID=" + encodeURIComponent(document.getElementById("nbTypeCommandeID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeCommandeName" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandetypecommande.php?l=" + "<?php print(LG); ?>" + "&nbTypeCommandeID=";
		  				var nbTypeCommandeIDIndex = xmlHttp.responseText.indexOf("nbTypeCommandeID") + 17;
		  				var nbTypeCommandeIDLen = xmlHttp.responseText.length - nbTypeCommandeIDIndex;
		  				var nbTypeCommandeID = xmlHttp.responseText.substr(nbTypeCommandeIDIndex, nbTypeCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeCommandeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commandetypecommandeedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeCommandeName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeCommandeLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypeCommande()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypeCommande()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypeCommande()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandetypecommandedel.php?nbTypeCommandeID=" + document.getElementById("nbTypeCommandeID").value;
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
		function stpi_delTypeCommandeConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandetypecommandedel.php?nbTypeCommandeID=" + document.getElementById("nbTypeCommandeID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delTypeCommandeConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_commande_TypeCommande.nbTypeCommandeID FROM stpi_commande_TypeCommande, stpi_commande_TypeCommande_Lg";
		$SQL .= " WHERE stpi_commande_TypeCommande.nbTypeCommandeID=stpi_commande_TypeCommande_Lg.nbTypeCommandeID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeCommandeID"];
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
		$SQL .= " WHERE nbTypeCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
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