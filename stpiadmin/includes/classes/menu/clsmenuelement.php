<?php
require_once(dirname(__FILE__) . "/clsmenuelementlg.php");
require_once(dirname(__FILE__) . "/clsmenupublic.php");
class clsmenuelement
{
	private $nbProfondeurMax = 5;

	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objMenuElementLg;
	private $objImg;
	private $objMenuPublic;
	
	private $nbID;
	private $nbMenuID;
	private $nbParentID;
	private $nbOrdre;
	
	private $arrObjMenuElementLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtmenuelement");
		$this->objLang = new clslang();
		$this->objMenuElementLg = new clsmenuelementlg();
		$this->objMenuPublic = new clsmenupublic();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbMenuID = 0;
			$this->nbParentID = 0;
			$this->nbOrdre = 0;
			$this->arrObjMenuElementLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjMenuElementLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbMenuElementID", "stpi_menu_MenuElement"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbMenuID($nnbMenuID)
	{
		if (!$this->objMenuPublic->stpi_chkNbID($nnbMenuID))
		{
			return false;
		}
		return true;
	}

	public function stpi_chkNbParentID($nnbParentID)
	{
		if ($nnbParentID != 0)
		{
			if (!$this->objBdd->stpi_chkInputToBdd($nnbParentID))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidparent") . "</span><br/>\n");
				return false;				
			}
			if (!$this->objBdd->stpi_chkExists($nnbParentID, "nbMenuElementID", "stpi_menu_MenuElement"))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . " (parent)</span><br/>\n");
				return false;
			}
		}
		return true;
	}

	public function stpi_chkNbOrdre($nnbOrdre)
	{
		if (!is_numeric($nnbOrdre))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidordre") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbQte < 0 OR $nnbQte > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidordre") . "&nbsp;([0,1000000000])</span><br/>\n");
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
		
		$SQL = "SELECT nbMenuID, nbParentID, nbOrdre FROM stpi_menu_MenuElement WHERE nbMenuElementID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbMenuID = $row["nbMenuID"];
				$this->nbParentID = $row["nbParentID"];
				$this->nbOrdre = $row["nbOrdre"];
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
	
	public function stpi_setNbMenuID($nnbMenuID)
	{
		if (!$this->stpi_chkNbMenuID($nnbMenuID))
		{
			return false;				
		}
		$this->nbMenuID = $nnbMenuID;
		return true;
	}

	public function stpi_setNbParentID($nnbParentID)
	{
		if (!$this->stpi_chkNbParentID($nnbParentID))
		{
			return false;				
		}
		$this->nbParentID = $nnbParentID;
		return true;
	}

	public function stpi_setNbOrdre($nnbOrdre)
	{
		if (!$this->stpi_chkNbOrdre($nnbOrdre))
		{
			return false;				
		}
		$this->nbOrdre = $nnbOrdre;
		return true;
	}
	

	
	public function stpi_setArrObjMenuElementLgFromBdd()
	{
		if (!$this->objMenuElementLg->stpi_setNbMenuElementID($this->nbID))
		{
			return false;
		}
		if (!$arrNbMenuElementId = $this->objMenuElementLg->stpi_selNbMenuElementID())
		{
			return false;
		}
		foreach ($arrNbMenuElementId as $strLg => $nbMenuElementLgID)
		{
			if (!$this->arrObjMenuElementLg[$strLg] = new clsmenuelementlg($nbMenuElementLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjMenuElementLgFromBdd()
	{
		if (!$this->objMenuElementLg->stpi_setNbMenuElementID($this->nbID))
		{
			return false;
		}
		if (!$nbMenuElementLgId = $this->objMenuElementLg->stpi_selNbMenuElementIDLG())
		{
			return false;
		}
		if (!$this->objMenuElementLg->stpi_setNbID($nbMenuElementLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}

	public function stpi_getNbProfondeurMax()
	{
		return $this->nbProfondeurMax;
	}

	public function stpi_getNbMenuID()
	{
		return $this->nbMenuID;
	}
	
	public function stpi_getNbParentID()
	{
		return $this->nbParentID;
	}

	public function stpi_getNbOrdre()
	{
		return $this->nbOrdre;
	}
	
	public function stpi_getObjMenuElementLg()
	{
		return $this->objMenuElementLg;
	}
	
	public function stpi_objMenuPublic()
	{
		return $this->objMenuPublic;
	}
	
	public function stpi_getArrObjMenuElementLg()
	{
		return $this->arrObjMenuElementLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_menu_MenuElement (nbMenuElementID, nbMenuID, nbParentID, nbOrdre) VALUES (NULL," . $this->objBdd->stpi_trsInputToBdd($this->nbMenuID) . "," . $this->objBdd->stpi_trsInputToBdd($this->nbParentID) . "," . $this->objBdd->stpi_trsInputToBdd($this->nbOrdre) . ")";
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
		
		$SQL = "UPDATE stpi_menu_MenuElement";
		$SQL .= " SET nbMenuID='" . $this->objBdd->stpi_trsInputToBdd($this->nbMenuID) . "'";
		$SQL .= ", nbParentID='" . $this->objBdd->stpi_trsInputToBdd($this->nbParentID) . "'";
		$SQL .= ", nbOrdre='" . $this->objBdd->stpi_trsInputToBdd($this->nbOrdre) . "'";
		$SQL .= " WHERE nbMenuElementID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbMenuElementID)
	{
		if (!$this->stpi_chkNbID($nnbMenuElementID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_menu_MenuElement WHERE nbMenuElementID=" . $this->objBdd->stpi_trsInputToBdd($nnbMenuElementID);
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
		function stpi_SearchMenuElement(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affMenuElement").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affMenuElement").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "menuelementsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affMenuElement").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("menuelement") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchMenuElement(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affMenuElement\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addMenuElement()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_MenuElementAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddMenuElement").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strText" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strText" + strLg[i]).value);
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLien" + strLg[i]).value);
			}
			strParam = strParam + "&nbMenuID=" + encodeURIComponent(document.getElementById("nbMenuID").value);
			strParam = strParam + "&nbParentID=" + encodeURIComponent(document.getElementById("nbParentID").value);
			strParam = strParam + "&nbOrdre=" + encodeURIComponent(document.getElementById("nbOrdre").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./menuelement.php?l=" + "<?php print(LG); ?>" + "&nbMenuElementID=";
		  				var nbMenuElementIDIndex = xmlHttp.responseText.indexOf("nbMenuElementID") + 16;
		  				var nbMenuElementIDLen = xmlHttp.responseText.length - nbMenuElementIDIndex;
		  				var nbMenuElementID = xmlHttp.responseText.substr(nbMenuElementIDIndex, nbMenuElementIDLen);
		  				strUrlRedirect = strUrlRedirect + nbMenuElementID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddMenuElement").style.visibility = "visible";
			  			document.getElementById("stpi_MenuElementAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "menuelementadd.php", true);
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
			print($this->objTexte->stpi_getArrTxt("text") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strText" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"255\" size=\"40\" id=\"strLien" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
		}

		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("parent") . "<br/>\n");				
		print("<select id=\"nbParentID\">\n");
		print("<option selected=\"selected\" value=\"0\">Menu</option>\n");

		$objMenuElement = new clsmenuelement();

		if ($arrNbMenuElementID = $this->stpi_selNbMenuID())
		{
			foreach($arrNbMenuElementID as $nbMenuElementID)
			{
				if ($objMenuElement->stpi_setNbID($nbMenuElementID))
				{
					if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbMenuElementID($nbMenuElementID))
					{
						if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbID($objMenuElement->stpi_getObjMenuElementLg()->stpi_selNbMenuElementIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbMenuElementID) . "\">-" . $this->objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</option>\n");
		/* pour ajouter un niveau au menu
							if ($arrNbMenuElement2ID = $objMenuElement->stpi_selNbParentID())
							{
								foreach($arrNbMenuElement2ID as $nbMenuElementID)
								{
									if ($objMenuElement->stpi_setNbID($nbMenuElementID))
									{
										if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbMenuElementID($nbMenuElementID))
										{
											if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbID($objMenuElement->stpi_getObjMenuElementLg()->stpi_selNbMenuElementIDLG()))
											{
												print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbMenuElementID) . "\">--" . $this->objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</option>\n");
											}
										}
									}
								}
							}
		*/
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");

		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("ordre") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"0\" id=\"nbOrdre\"/><br/>\n");
		print("</p>\n");

		print("<p>\n");
		print("<span id=\"stpi_MenuElementAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddMenuElement\" type=\"button\" onclick=\"stpi_addMenuElement()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strText" + strLg[i]).disabled = false;
				document.getElementById("strLien" + strLg[i]).disabled = false;
			}
			document.getElementById("nbParentID").disabled = false;
			document.getElementById("nbOrdre").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editMenuElement()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbMenuElementID=" + encodeURIComponent(document.getElementById("nbMenuElementID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strText" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strText" + strLg[i]).value);
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLien" + strLg[i]).value);
			}
			strParam = strParam + "&nbParentID=" + encodeURIComponent(document.getElementById("nbParentID").value);
			strParam = strParam + "&nbOrdre=" + encodeURIComponent(document.getElementById("nbOrdre").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./menuelement.php?l=" + "<?php print(LG); ?>" + "&nbMenuElementID=";
		  				var nbMenuElementIDIndex = xmlHttp.responseText.indexOf("nbMenuElementID") + 16;
		  				var nbMenuElementIDLen = xmlHttp.responseText.length - nbMenuElementIDIndex;
		  				var nbMenuElementID = xmlHttp.responseText.substr(nbMenuElementIDIndex, nbMenuElementIDLen);
		  				strUrlRedirect = strUrlRedirect + nbMenuElementID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "menuelementedit.php", true);
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
			print($this->objTexte->stpi_getArrTxt("text") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strText" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjMenuElementLg[$strLg]->stpi_getStrText()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"40\" id=\"strLien" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjMenuElementLg[$strLg]->stpi_getStrLien()) . "\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("parent") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbParentID\">\n");
		print("<option");
		if ($this->nbParentID == 0)
		{
			print(" selected=\"selected\"");
		}
		print(" value=\"0\">Menu</option>\n");

		$objMenuElement = new clsmenuelement();
					
		if ($arrNbMenuElementID = $this->stpi_selNbMenuID())
		{
			foreach($arrNbMenuElementID as $nbMenuElementID)
			{
				if ($objMenuElement->stpi_setNbID($nbMenuElementID))
				{
					if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbMenuElementID($nbMenuElementID))
					{
						if ($objMenuElement->stpi_getObjMenuElementLg()->stpi_setNbID($objMenuElement->stpi_getObjMenuElementLg()->stpi_selNbMenuElementIDLG()))
						{
							print("<option");
							if ($this->nbParentID == $objMenuElement->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbMenuElementID) . "\">-" . $this->objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");

		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("ordre") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $this->stpi_getNbOrdre() . "\" id=\"nbOrdre\"/><br/>\n");
		print("</p>\n");

		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editMenuElement()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delMenuElement()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delMenuElement()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "menuelementdel.php?nbMenuElementID=" + document.getElementById("nbMenuElementID").value;
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
		function stpi_delMenuElementConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "menuelementdel.php?nbMenuElementID=" + document.getElementById("nbMenuElementID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./menus.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delMenuElementConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}

	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}

	public function stpi_selNbMenuID()
	{
		$arrID = array();
		$SQL = "SELECT nbMenuElementID FROM stpi_menu_MenuElement";
		$SQL .= " WHERE nbMenuID='" . $this->nbMenuID . "' AND nbParentID=0";
		$SQL .= " ORDER BY nbOrdre";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbMenuElementID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}

	public function stpi_selNbParentID()
	{
		$arrID = array();
		$SQL = "SELECT nbMenuElementID FROM stpi_menu_MenuElement";
		$SQL .= " WHERE nbParentID='" . $this->nbID . "'";
		$SQL .= " ORDER BY nbOrdre";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbMenuElementID"];
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
