<?php
require_once(dirname(__FILE__) . "/../item/clsitem.php");

class clscommandesousitem
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objItem;
	
	private $nbCommandeID;
	private $nbSousItemID;
	private $strItemCode;
	private $nbQte;
	private $nbPrix;
	private $strSousItemDesc;
	
	public function __construct($nnbCommandeID = 0, $nnbSousItemID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcommandesousitem");
		$this->objLang = new clslang();
		$this->objItem = new clsitem();
		if ($nnbCommandeID == 0 OR $nnbSousItemID == 0)
		{
			$this->nbCommandeID = 0;
			$this->nbSousItemID = 0;
			$this->strItemCode = "";
			$this->nbQte = 0;
			$this->nbPrix = 0;
			$this->strSousItemDesc = "";
		}
		else
		{
			if(!$this->stpi_setNbID($nnbCommandeID, $nnbSousItemID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkNbCommandeID($nnbCommandeID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbCommandeID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcommandeid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbCommandeID, "nbCommandeID", "stpi_commande_Commande"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcommandeid") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbSousItemID($nnbSousItemID)
	{
		if (!is_numeric($nnbSousItemID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemid") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbSousItemID < 1 OR $nnbSousItemID > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemid") . "&nbsp;([1,1000000000])</span><br/>\n");
			return false;
		}
		/*
		if (!$this->objBdd->stpi_chkExists($nnbSousItemID, "nbSousItemID", "stpi_commande_Commande_SousItem"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemid") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		*/
		return true;
	}
	
	public function stpi_chkStrItemCode($nstrItemCode)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrItemCode) AND $nstrItemCode != "")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliditemcode") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkNbQte($nnbQte)
	{
		if (!is_numeric($nnbQte))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqte") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbQte < 1 OR $nnbQte > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqte") . "&nbsp;([1,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbPrix($nnbPrix)
	{
		if (!is_numeric($nnbPrix))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprix") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbPrix < 0 OR $nnbPrix > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprix") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}

	public function stpi_chkStrSousItemDesc($nstrSousItemDesc)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrSousItemDesc))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemdesc") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_setNbID($nnbCommandeID, $nnbSousItemID)
	{
		if (!$this->stpi_setNbCommandeID($nnbCommandeID))
		{
			return false;				
		}
		if (!$this->stpi_setNbSousItemID($nnbSousItemID))
		{
			return false;				
		}

		$SQL = "SELECT strItemCode, nbQte, nbPrix, strSousItemDesc";
		$SQL .= " FROM stpi_commande_Commande_SousItem WHERE nbCommandeID=" . $this->nbCommandeID . " AND nbSousItemID=" . $this->nbSousItemID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->strItemCode = $row["strItemCode"];
				$this->nbQte = $row["nbQte"];
				$this->nbPrix = $row["nbPrix"];
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
	
	public function stpi_setNbCommandeID($nnbCommandeID)
	{
		if (!$this->stpi_chkNbCommandeID($nnbCommandeID))
		{
			return false;
		}
		$this->nbCommandeID = $nnbCommandeID;
		return true;
	}
	
	public function stpi_setNbSousItemID($nnbSousItemID)
	{
		if (!$this->stpi_chkNbSousItemID($nnbSousItemID))
		{
			return false;
		}
		$this->nbSousItemID = $nnbSousItemID;
		return true;
	}
	
	public function stpi_setStrItemCode($nstrItemCode)
	{
		if (!$this->stpi_chkStrItemCode($nstrItemCode))
		{
			return false;				
		}
		$this->strItemCode = $nstrItemCode;
		return true;
	}
	
	public function stpi_setNbQte($nnbQte)
	{
		if (!$this->stpi_chkNbQte($nnbQte))
		{
			return false;				
		}
		$this->nbQte = $nnbQte;
		return true;
	}
	
	public function stpi_setNbPrix($nnbPrix)
	{
		if (!$this->stpi_chkNbPrix($nnbPrix))
		{
			return false;				
		}
		$this->nbPrix = $nnbPrix;
		return true;
	}
	
	public function stpi_setStrSousItemDesc($nstrSousItemDesc)
	{
		if (!$this->stpi_chkStrSousItemDesc($nstrSousItemDesc))
		{
			return false;				
		}
		$this->strSousItemDesc = $nstrSousItemDesc;
		return true;
	}
	
	public function stpi_getNbCommandeID()
	{
		return $this->nbCommandeID;
	}
	public function stpi_getNbSousItemID()
	{
		return $this->nbSousItemID;
	}
	public function stpi_getStrItemCode()
	{
		return $this->strItemCode;
	}
	public function stpi_getNbQte()
	{
		return $this->nbQte;
	}
	public function stpi_getNbPrix()
	{
		return $this->nbPrix;
	}
	public function stpi_getStrSousItemDesc()
	{
		return $this->strSousItemDesc;
	}
	public function stpi_getObjItem()
	{
		return $this->objItem;
	}
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	public function stpi_insert()
	{
		if ($this->nbCommandeID == 0 OR $this->nbSousItemID == 0)
		{
			return false;
		}		
		$SQL = "INSERT INTO stpi_commande_Commande_SousItem (nbCommandeID, nbSousItemID, strItemCode, nbQte, nbPrix, strSousItemDesc)";
		$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbCommandeID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbSousItemID);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strItemCode) . "'";
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbQte);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbPrix);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strSousItemDesc) . "')";
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
		if ($this->nbCommandeID == 0 OR $this->nbSousItemID == 0)
		{
			return false;
		}
		$SQL = "UPDATE stpi_commande_Commande_SousItem";
		$SQL .= " SET strItemCode='" . $this->objBdd->stpi_trsInputToBdd($this->strItemCode) . "'";
		$SQL .= ", nbQte=" . $this->objBdd->stpi_trsInputToBdd($this->nbQte);
		$SQL .= ", nbPrix=" . $this->objBdd->stpi_trsInputToBdd($this->nbPrix);
		$SQL .= ", strSousItemDesc='" . $this->objBdd->stpi_trsInputToBdd($this->strSousItemDesc) . "'";
		$SQL .= " WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbCommandeID);
		$SQL .= " AND nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbSousItemID);
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
	
	public function stpi_deleteCommandeID($nnbCommandeID)
	{
		if (!$this->stpi_chkNbCommandeID($nnbCommandeID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_Commande_SousItem WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($nnbCommandeID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(nbCommandeID)</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_delete($nnbCommandeID, $nnbSousItemID)
	{
		if (!$this->objBdd->stpi_chkArrExists(array($nnbCommandeID, $nnbSousItemID), array("nbCommandeID", "nbSousItemID"), "stpi_commande_Commande_SousItem"))
		{
			return false;				
		}

		$SQL = "DELETE FROM stpi_commande_Commande_SousItem WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($nnbCommandeID) . " AND nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbSousItemID);
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
			document.getElementById("strItemCode").disabled = false;
			document.getElementById("nbQte").disabled = false;
			document.getElementById("nbPrix").disabled = false;
			document.getElementById("strSousItemDesc").disabled = false;
			
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editCommandeSousItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject[stpi_chkLang()];
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbCommandeID=" + encodeURIComponent(document.getElementById("nbCommandeID").value);
			strParam = strParam + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
			strParam = strParam + "&strItemCode=" + encodeURIComponent(document.getElementById("strItemCode").value);
			strParam = strParam + "&nbQte=" + encodeURIComponent(document.getElementById("nbQte").value);
			strParam = strParam + "&nbPrix=" + encodeURIComponent(document.getElementById("nbPrix").value);
			strParam = strParam + "&strSousItemDesc=" + encodeURIComponent(document.getElementById("strSousItemDesc").value);
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commandesousitem.php?l=" + "<?php print(LG) ?>" + "&nbCommandeID=" + encodeURIComponent(document.getElementById("nbCommandeID").value) + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "commandesousitemedit.php", true);
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
		
		if ($this->nbCommandeID == 0 OR $this->nbSousItemID == 0)
		{
			return false;
		}
		print("<input type=\"hidden\" id=\"nbCommandeID\" value=\"" . $this->nbCommandeID . "\" />\n");
		print("<input type=\"hidden\" id=\"nbSousItemID\" value=\"" . $this->nbSousItemID . "\" />\n");
		
		print("<p><span id=\"stpi_CommandeSousItemEdit\">");
		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("sousitem") . " : " . $this->nbSousItemID . "<br/><br/>\n");
		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("codeitem") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strItemCode) . "\" id=\"strItemCode\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qte") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbQte) . "\" id=\"nbQte\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("prix") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbPrix) . "\" id=\"nbPrix\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("desc") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strSousItemDesc) . "\" id=\"strSousItemDesc\"/><br/>\n");
		print("</span><br/>\n");
		
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editCommandeSousItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delCommandeSousItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" />\n");
		print("</p>\n");
	}
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addCommandeSousItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_CommandeSousItemAddEdit").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddCommandeSousItem").style.visibility = "hidden";
			var strParam = "nbCommandeID=" + encodeURIComponent(document.getElementById("nbCommandeID").value);
			strParam = strParam + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
			strParam = strParam + "&strItemCode=" + encodeURIComponent(document.getElementById("strItemCode").value);
			strParam = strParam + "&nbQte=" + encodeURIComponent(document.getElementById("nbQte").value);
			strParam = strParam + "&nbPrix=" + encodeURIComponent(document.getElementById("nbPrix").value);
			strParam = strParam + "&strSousItemDesc=" + encodeURIComponent(document.getElementById("strSousItemDesc").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commande.php?l=" + "<?php print(LG); ?>" + "&nbCommandeID=";
		  				var nbCommandeIDIndex = xmlHttp.responseText.indexOf("nbCommandeID") + 13;
		  				var nbCommandeIDLen = xmlHttp.responseText.length - nbCommandeIDIndex;
		  				var nbCommandeID = xmlHttp.responseText.substr(nbCommandeIDIndex, nbCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbCommandeID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddCommandeSousItem").style.visibility = "visible";
			  			document.getElementById("stpi_CommandeSousItemAddEdit").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commandesousitemadd.php", true);
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
		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("sousitem") . "<br/>\n");
		$this->objItem->stpi_affSelectTypeItem();

		print("<span id=\"stpi_CommandeSousItemEdit\">");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("sousitem") . "ID<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"\" id=\"nbSousItemID\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("codeitem") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"\" id=\"strItemCode\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qte") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"1\" id=\"nbQte\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("prix") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"12\" size=\"13\" value=\"0.00\" id=\"nbPrix\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("desc") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"255\" size=\"50\" value=\"\" id=\"strSousItemDesc\"/><br/>\n");
		print("</span><br/>\n");
		
		print("<span id=\"stpi_CommandeSousItemAddEdit\"></span><br/>\n");
		print("<input id=\"stpi_AddCommandeSousItem\" type=\"button\" onclick=\"stpi_addCommandeSousItem()\" value=\"" . $this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delCommandeSousItem()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandesousitemdel.php?nbCommandeID=" + encodeURIComponent(document.getElementById("nbCommandeID").value);
			strUrl = strUrl + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
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
		function stpi_delCommandeSousItemConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "commandesousitemdel.php?nbCommandeID=" + encodeURIComponent(document.getElementById("nbCommandeID").value);
			strUrl = strUrl + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./commande.php?l=" + "<?php print(LG); ?>" + "&nbCommandeID=";
		  				var nbCommandeIDIndex = xmlHttp.responseText.indexOf("nbCommandeID") + 13;
		  				var nbCommandeIDLen = xmlHttp.responseText.length - nbCommandeIDIndex;
		  				var nbCommandeID = xmlHttp.responseText.substr(nbCommandeIDIndex, nbCommandeIDLen);
		  				strUrlRedirect = strUrlRedirect + nbCommandeID;
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

	public function stpi_affJsInfoSousItem()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affSousItemInfo(nnbSousItemID)
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_selSousItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			if (nnbSousItemID != "")
			{
				var strUrl = "commandesousiteminfoaff.php?l=" + "<?php print(LG); ?>" + "&nbSousItemID=" + nnbSousItemID + "&sid=" + Math.random();
				xmlHttp.onreadystatechange=function()
				{
					if (xmlHttp.readyState == 4)
			  		{
			  			document.getElementById("stpi_CommandeSousItemEdit").innerHTML = xmlHttp.responseText;
					}
				}
				xmlHttp.open("GET", strUrl, true);
				xmlHttp.send(null);
			}
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirm") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_delCommandeSousItemConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
}
?>
