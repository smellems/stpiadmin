<?php
require_once(dirname(__FILE__) . "/clslienlg.php");
require_once(dirname(__FILE__) . "/clstypelien.php");
require_once(dirname(__FILE__) . "/../img/clsimg.php");
class clslien
{
	private $nbImgWidthMax = 200;
	private $nbImgHeightMax = 100;
	
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objLienLg;
	private $objImg;
	private $objTypeLien;
	
	private $nbID;
	private $nbTypeLienID;
	private $nbImageID;
	
	private $arrObjLienLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtlien");
		$this->objLang = new clslang();
		$this->objLienLg = new clslienlg();
		$this->objImg = new clsimg("stpi_lien_ImgLien");
		$this->objTypeLien = new clstypelien();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbTypeLienID = 0;
			$this->nbImageID = 0;
			$this->arrObjLienLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjLienLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbLienID", "stpi_lien_Lien"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbTypeLienID($nnbTypeLienID)
	{
		if (!$this->objTypeLien->stpi_chkNbID($nnbTypeLienID))
		{
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
		
		$SQL = "SELECT nbTypeLienID, nbImageID FROM stpi_lien_Lien WHERE nbLienID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeLienID = $row["nbTypeLienID"];
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
	
	public function stpi_setNbTypeLienID($nnbTypeLienID)
	{
		if (!$this->stpi_chkNbTypeLienID($nnbTypeLienID))
		{
			return false;				
		}
		$this->nbTypeLienID = $nnbTypeLienID;
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
	
	public function stpi_setArrObjLienLgFromBdd()
	{
		if (!$this->objLienLg->stpi_setNbLienID($this->nbID))
		{
			return false;
		}
		if (!$arrNbLienId = $this->objLienLg->stpi_selNbLienID())
		{
			return false;
		}
		foreach ($arrNbLienId as $strLg => $nbLienLgID)
		{
			if (!$this->arrObjLienLg[$strLg] = new clsLienlg($nbLienLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjLienLgFromBdd()
	{
		if (!$this->objLienLg->stpi_setNbLienID($this->nbID))
		{
			return false;
		}
		if (!$nbLienLgId = $this->objLienLg->stpi_selNbLienIDLG())
		{
			return false;
		}
		if (!$this->objLienLg->stpi_setNbID($nbLienLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}

	public function stpi_getNbTypeLienID()
	{
		return $this->nbTypeLienID;
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
	
	public function stpi_getObjLienLg()
	{
		return $this->objLienLg;
	}
	
	public function stpi_getObjTypeLien()
	{
		return $this->objTypeLien;
	}
	
	public function stpi_getObjImg()
	{
		return $this->objImg;
	}
	
	public function stpi_getArrObjLienLg()
	{
		return $this->arrObjLienLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_lien_Lien (nbLienID, nbTypeLienID, nbImageID) VALUES (NULL, " . $this->objBdd->stpi_trsInputToBdd($this->nbTypeLienID) . ", 0)";
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
		
		$SQL = "UPDATE stpi_lien_Lien";
		$SQL .= " SET nbTypeLienID='" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeLienID) . "'";
		$SQL .= ", nbImageID='" . $this->objBdd->stpi_trsInputToBdd($this->nbImageID) . "'";
		$SQL .= " WHERE nbLienID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbLienID)
	{
		if (!$this->stpi_chkNbID($nnbLienID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_lien_Lien WHERE nbLienID=" . $this->objBdd->stpi_trsInputToBdd($nnbLienID);
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
		function stpi_SearchLien(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affLien").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affLien").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "liensaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affLien").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("lien") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchLien(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affLien\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addLien()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_LienAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddLien").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLienName" + strLg[i]).value);
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLien" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLienDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeLienID=" + encodeURIComponent(document.getElementById("nbTypeLienID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./lien.php?l=" + "<?php print(LG); ?>" + "&nbLienID=";
		  				var nbLienIDIndex = xmlHttp.responseText.indexOf("nbLienID") + 9;
		  				var nbLienIDLen = xmlHttp.responseText.length - nbLienIDIndex;
		  				var nbLienID = xmlHttp.responseText.substr(nbLienIDIndex, nbLienIDLen);
		  				strUrlRedirect = strUrlRedirect + nbLienID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddLien").style.visibility = "visible";
			  			document.getElementById("stpi_LienAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "lienadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strLienName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strLien" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"5\" cols=\"50\" id=\"strLienDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typelien") . "<br/>\n");				
		print("<select id=\"nbTypeLienID\">\n");
		print("<option selected=\"selected\" value=\"\"></option>\n");
		if ($arrNbTypeLienID = $this->objTypeLien->stpi_selAll())
		{
			foreach($arrNbTypeLienID as $nbTypeLienID)
			{
				if ($this->objTypeLien->stpi_setNbID($nbTypeLienID))
				{
					if ($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_setNbTypeLienID($nbTypeLienID))
					{
						if ($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_setNbID($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_selNbTypeLienIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeLienID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_LienAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddLien\" type=\"button\" onclick=\"stpi_addLien()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strLienName" + strLg[i]).disabled = false;
				document.getElementById("strLien" + strLg[i]).disabled = false;
				document.getElementById("strLienDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("nbTypeLienID").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editLien()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbLienID=" + encodeURIComponent(document.getElementById("nbLienID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLienName" + strLg[i]).value);
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLien" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strLienDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeLienID=" + encodeURIComponent(document.getElementById("nbTypeLienID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./lien.php?l=" + "<?php print(LG); ?>" + "&nbLienID=";
		  				var nbLienIDIndex = xmlHttp.responseText.indexOf("nbLienID") + 9;
		  				var nbLienIDLen = xmlHttp.responseText.length - nbLienIDIndex;
		  				var nbLienID = xmlHttp.responseText.substr(nbLienIDIndex, nbLienIDLen);
		  				strUrlRedirect = strUrlRedirect + nbLienID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "lienedit.php", true);
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
		print("<form method=\"post\" action=\"./lienimgadd.php?l=" . LG);
		print("&amp;nbLienID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
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
		print("<form method=\"post\" action=\"./lienimgedit.php?l=" . LG);
		print("&amp;nbLienID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
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
			print("<img alt=\"" . $this->nbImageID . "\" src=\"./lienimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->nbImageID . "\"/><br/>\n");
			print("<a href=\"./lienimgedit.php?l=" . LG . "&amp;nbLienID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("editimg") . "</a><br/>\n");
		}
		else
		{
			print("<a href=\"./lienimgadd.php?l=" . LG . "&amp;nbLienID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("addimg") . "</a><br/>\n");
		}
		
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		foreach ($arrLang as $strLg)	
		{
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strLienName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjLienLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strLien" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjLienLg[$strLg]->stpi_getStrLien()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"5\" cols=\"50\" id=\"strLienDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjLienLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typelien") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbTypeLienID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeLienID = $this->objTypeLien->stpi_selAll())
		{
			foreach($arrNbTypeLienID as $nbTypeLienID)
			{
				if ($this->objTypeLien->stpi_setNbID($nbTypeLienID))
				{
					if ($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_setNbTypeLienID($nbTypeLienID))
					{
						if ($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_setNbID($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_selNbTypeLienIDLG()))
						{
							print("<option");
							if ($this->nbTypeLienID == $this->objTypeLien->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeLienID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeLien->stpi_getObjTypeLienLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editLien()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delLien()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delLien()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "liendel.php?nbLienID=" + document.getElementById("nbLienID").value;
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
		function stpi_delLienConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "liendel.php?nbLienID=" + document.getElementById("nbLienID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./liens.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delLienConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
}
?>
