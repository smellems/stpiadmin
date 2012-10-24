<?php
require_once(dirname(__FILE__) . "/../item/clsitem.php");

class clsregistresousitem
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objItem;
	
	private $nbRegistreID;
	private $nbSousItemID;
	private $nbQteVoulu;
	private $nbQteRecu;
	private $strItemCode;
	private $strSousItemDesc;
	
	public function __construct($nnbRegistreID = 0, $nnbSousItemID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtregistresousitem");
		$this->objLang = new clslang();
		$this->objItem = new clsitem();
		if ($nnbRegistreID == 0 OR $nnbSousItemID == 0)
		{
			$this->nbRegistreID = 0;
			$this->nbSousItemID = 0;
			$this->nbQteVoulu = 0;
			$this->nbQteRecu = 0;
			$this->strItemCode = "";
			$this->strSousItemDesc = "";
		}
		else
		{
			if(!$this->stpi_setNbID($nnbRegistreID, $nnbSousItemID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkNbRegistreID($nnbRegistreID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbRegistreID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidregistreid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbRegistreID, "nbRegistreID", "stpi_registre_Registre"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidregistreid") . "&nbsp;(!chkExists)</span><br/>\n");
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
		if (!$this->objBdd->stpi_chkExists($nnbSousItemID, "nbSousItemID", "stpi_registre_Registre_SousItem"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemid") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		*/
		return true;
	}
	
	public function stpi_chkNbQteVoulu($nnbQteVoulu)
	{
		if (!is_numeric($nnbQteVoulu))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqtevoulu") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbQteVoulu < 1 OR $nnbQteRecu > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqtevoulu") . "&nbsp;([1,1000000000])</span><br/>\n");
			return false;
		}
		if ($nnbQteVoulu < $this->nbQteRecu OR $nnbQteVoulu > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqtevoulu") . "&nbsp;([" . $this->nbQteRecu . ",1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbQteRecu($nnbQteRecu)
	{
		if (!is_numeric($nnbQteRecu))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqterecu") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbQteRecu < 0 OR $nnbQteRecu > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqterecu") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
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
	
	public function stpi_chkStrSousItemDesc($nstrSousItemDesc)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrSousItemDesc))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsousitemdesc") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_setNbID($nnbRegistreID, $nnbSousItemID)
	{
		if (!$this->stpi_setNbRegistreID($nnbRegistreID))
		{
			return false;				
		}
		if (!$this->stpi_setNbSousItemID($nnbSousItemID))
		{
			return false;				
		}

		$SQL = "SELECT nbQteVoulu, nbQteRecu, strItemCode, strSousItemDesc";
		$SQL .= " FROM stpi_registre_Registre_SousItem WHERE nbRegistreID=" . $this->nbRegistreID . " AND nbSousItemID=" . $this->nbSousItemID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbQteVoulu = $row["nbQteVoulu"];
				$this->nbQteRecu = $row["nbQteRecu"];
				$this->strItemCode = $row["strItemCode"];
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
	
	public function stpi_setNbRegistreID($nnbRegistreID)
	{
		if (!$this->stpi_chkNbRegistreID($nnbRegistreID))
		{
			return false;
		}
		$this->nbRegistreID = $nnbRegistreID;
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
	
	public function stpi_setNbQteVoulu($nnbQteVoulu)
	{
		if (!$this->stpi_chkNbQteVoulu($nnbQteVoulu))
		{
			return false;				
		}
		$this->nbQteVoulu = $nnbQteVoulu;
		return true;
	}
	
	public function stpi_setNbQteRecu($nnbQteRecu)
	{
		if (!$this->stpi_chkNbQteRecu($nnbQteRecu))
		{
			return false;				
		}
		$this->nbQteRecu = $nnbQteRecu;
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
	
	public function stpi_setStrSousItemDesc($nstrSousItemDesc)
	{
		if (!$this->stpi_chkStrSousItemDesc($nstrSousItemDesc))
		{
			return false;				
		}
		$this->strSousItemDesc = $nstrSousItemDesc;
		return true;
	}
	
	public function stpi_getNbRegistreID()
	{
		return $this->nbRegistreID;
	}
	public function stpi_getNbSousItemID()
	{
		return $this->nbSousItemID;
	}
	public function stpi_getNbQteVoulu()
	{
		return $this->nbQteVoulu;
	}
	public function stpi_getNbQteRecu()
	{
		return $this->nbQteRecu;
	}
	public function stpi_getStrItemCode()
	{
		return $this->strItemCode;
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
		if ($this->nbRegistreID == 0 OR $this->nbSousItemID == 0)
		{
			return false;
		}		
		$SQL = "INSERT INTO stpi_registre_Registre_SousItem (nbRegistreID, nbSousItemID, nbQteVoulu, nbQteRecu, strItemCode, strSousItemDesc)";
		$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbRegistreID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbSousItemID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbQteVoulu);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbQteRecu);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strItemCode) . "'";
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
		if ($this->nbRegistreID == 0 OR $this->nbSousItemID == 0)
		{
			return false;
		}
		$SQL = "UPDATE stpi_registre_Registre_SousItem";
		$SQL .= " SET nbQteVoulu=" . $this->objBdd->stpi_trsInputToBdd($this->nbQteVoulu);
		$SQL .= ", nbQteRecu=" . $this->objBdd->stpi_trsInputToBdd($this->nbQteRecu);
		$SQL .= ", strItemCode='" . $this->objBdd->stpi_trsInputToBdd($this->strItemCode) . "'";
		$SQL .= ", strSousItemDesc='" . $this->objBdd->stpi_trsInputToBdd($this->strSousItemDesc) . "'";
		$SQL .= " WHERE nbRegistreID=" . $this->objBdd->stpi_trsInputToBdd($this->nbRegistreID);
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

	public function stpi_delete($nnbRegistreID, $nnbSousItemID)
	{
		if (!$this->objBdd->stpi_chkArrExists(array($nnbRegistreID, $nnbSousItemID), array("nbRegistreID", "nbSousItemID"), "stpi_registre_Registre_SousItem"))
		{
			return false;				
		}

		$SQL = "DELETE FROM stpi_registre_Registre_SousItem WHERE nbRegistreID=" . $this->objBdd->stpi_trsInputToBdd($nnbRegistreID) . " AND nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbSousItemID);
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
	
	public function stpi_deleteRegistreID($nnbRegistreID)
	{
		if (!$this->stpi_chkNbRegistreID($nnbRegistreID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_registre_Registre_SousItem WHERE nbRegistreID=" . $this->objBdd->stpi_trsInputToBdd($nnbRegistreID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(nbRegistreID)</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_affJsDelSousItemFromRegistre()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delSousItemFromRegistre(nnbRegistreID, nnbSousItemID)
		{
			if (nnbSousItemID == "")
			{
				return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_message").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			var strUrl = "registresousitemdelpublic.php?nbSousItemID=" + nnbSousItemID + "&nbRegistreID=" + nnbRegistreID + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./registrepublic.php?l=<?php print(LG); ?>&nbRegistreID=" + nnbRegistreID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}	
		-->
		<?php
		print("</script>\n");
	}

	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addRegistreSousItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_RegistreSousItemAddEdit").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddRegistreSousItem").style.visibility = "hidden";
			var strParam = "nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value);
			strParam = strParam + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
			strParam = strParam + "&strItemCode=" + encodeURIComponent(document.getElementById("strItemCode").value);
			strParam = strParam + "&nbQteVoulu=" + encodeURIComponent(document.getElementById("nbQte").value);
			strParam = strParam + "&strSousItemDesc=" + encodeURIComponent(document.getElementById("strSousItemDesc").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./registre.php?l=" + "<?php print(LG); ?>" + "&nbRegistreID=";
		  				var nbRegistreIDIndex = xmlHttp.responseText.indexOf("nbRegistreID") + 13;
		  				var nbRegistreIDLen = xmlHttp.responseText.length - nbRegistreIDIndex;
		  				var nbRegistreID = xmlHttp.responseText.substr(nbRegistreIDIndex, nbRegistreIDLen);
		  				strUrlRedirect = strUrlRedirect + nbRegistreID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddRegistreSousItem").style.visibility = "visible";
			  			document.getElementById("stpi_RegistreSousItemAddEdit").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "registresousitemadd.php", true);
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

		print("<span id=\"stpi_RegistreSousItemEdit\">");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("sousitem") . "ID<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"\" id=\"nbSousItemID\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("codeitem") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"\" id=\"strItemCode\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qte") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"1\" id=\"nbQte\"/><br/>\n");

		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("desc") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"255\" size=\"50\" value=\"\" id=\"strSousItemDesc\"/><br/>\n");
		print("</span><br/>\n");
		
		print("<span id=\"stpi_RegistreSousItemAddEdit\"></span><br/>\n");
		print("<input id=\"stpi_AddRegistreSousItem\" type=\"button\" onclick=\"stpi_addRegistreSousItem()\" value=\"" . $this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}

	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			document.getElementById("strItemCode").disabled = false;
			document.getElementById("nbQteVoulu").disabled = false;
			document.getElementById("nbQteRecu").disabled = false;
			document.getElementById("strSousItemDesc").disabled = false;
			
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editRegistreSousItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject[stpi_chkLang()];
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value);
			strParam = strParam + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
			strParam = strParam + "&strItemCode=" + encodeURIComponent(document.getElementById("strItemCode").value);
			strParam = strParam + "&nbQteVoulu=" + encodeURIComponent(document.getElementById("nbQteVoulu").value);
			strParam = strParam + "&nbQteRecu=" + encodeURIComponent(document.getElementById("nbQteRecu").value);
			strParam = strParam + "&strSousItemDesc=" + encodeURIComponent(document.getElementById("strSousItemDesc").value);
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./registresousitem.php?l=" + "<?php print(LG) ?>" + "&nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value) + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "registresousitemedit.php", true);
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
		
		if ($this->nbRegistreID == 0 OR $this->nbSousItemID == 0)
		{
			return false;
		}
		print("<input type=\"hidden\" id=\"nbRegistreID\" value=\"" . $this->nbRegistreID . "\" />\n");
		print("<input type=\"hidden\" id=\"nbSousItemID\" value=\"" . $this->nbSousItemID . "\" />\n");
		
		print("<p><span id=\"stpi_RegistreSousItemEdit\">");
		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("sousitem") . " : " . $this->nbSousItemID . "<br/><br/>\n");
		print($this->objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("codeitem") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strItemCode) . "\" id=\"strItemCode\"/><br/>\n");

		print($this->objTexte->stpi_getArrTxt("qtevoulu") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbQteVoulu) . "\" id=\"nbQteVoulu\"/><br/>\n");

		print($this->objTexte->stpi_getArrTxt("qterecu") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbQteRecu) . "\" id=\"nbQteRecu\"/><br/>\n");

		print($this->objTexte->stpi_getArrTxt("desc") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strSousItemDesc) . "\" id=\"strSousItemDesc\"/><br/>\n");
		print("</span><br/>\n");
		
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editRegistreSousItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delRegistreSousItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" />\n");
		print("</p>\n");
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
				var strUrl = "registresousiteminfoaff.php?l=" + "<?php print(LG); ?>" + "&nbSousItemID=" + nnbSousItemID + "&sid=" + Math.random();
				xmlHttp.onreadystatechange=function()
				{
					if (xmlHttp.readyState == 4)
			  		{
			  			document.getElementById("stpi_RegistreSousItemEdit").innerHTML = xmlHttp.responseText;
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

	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delRegistreSousItem()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "registresousitemdel.php?nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value);
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
		function stpi_delRegistreSousItemConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "registresousitemdel.php?nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value);
			strUrl = strUrl + "&nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./registre.php?l=" + "<?php print(LG); ?>" + "&nbRegistreID=";
		  				var nbRegistreIDIndex = xmlHttp.responseText.indexOf("nbRegistreID") + 13;
		  				var nbRegistreIDLen = xmlHttp.responseText.length - nbRegistreIDIndex;
		  				var nbRegistreID = xmlHttp.responseText.substr(nbRegistreIDIndex, nbRegistreIDLen);
		  				strUrlRedirect = strUrlRedirect + nbRegistreID;
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
		print("<input type=\"button\" onclick=\"stpi_delRegistreSousItemConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
}
?>
