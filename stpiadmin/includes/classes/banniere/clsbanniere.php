<?php
require_once(dirname(__FILE__) . "/clsbannierelg.php");
require_once(dirname(__FILE__) . "/clstypebanniere.php");

class clsbanniere
{	
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objBanniereLg;
	private $objTypeBanniere;
	
	private $nbID;
	private $nbTypeBanniereID;
	
	private $arrObjBanniereLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtbanniere");
		$this->objLang = new clslang();
		$this->objBanniereLg = new clsbannierelg();
		$this->objTypeBanniere = new clstypebanniere();
		$this->arrObjBanniereLg = array();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbTypeBanniereID = 0;
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbBanniereID", "stpi_banniere_Banniere"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbTypeBanniereID($nnbTypeBanniereID)
	{
		if (!$this->objTypeBanniere->stpi_chkNbID($nnbTypeBanniereID))
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
		
		$SQL = "SELECT nbTypeBanniereID FROM stpi_banniere_Banniere WHERE nbBanniereID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeBanniereID = $row["nbTypeBanniereID"];
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
	
	public function stpi_setNbTypeBanniereID($nnbTypeBanniereID)
	{
		if (!$this->stpi_chkNbTypeBanniereID($nnbTypeBanniereID))
		{
			return false;				
		}
		$this->nbTypeBanniereID = $nnbTypeBanniereID;
		return true;
	}
	
	
	public function stpi_setArrObjBanniereLgFromBdd()
	{
		if (!$this->objBanniereLg->stpi_setNbBanniereID($this->nbID))
		{
			return false;
		}
		if (!$arrNbBanniereID = $this->objBanniereLg->stpi_selNbBanniereID())
		{
			return false;
		}
		foreach ($arrNbBanniereID as $strLg => $nbBanniereLgID)
		{
			if (!$this->arrObjBanniereLg[$strLg] = new clsbannierelg($nbBanniereLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjBanniereLgFromBdd()
	{
		if (!$this->objBanniereLg->stpi_setNbBanniereID($this->nbID))
		{
			return false;
		}
		if (!$nbBanniereLgId = $this->objBanniereLg->stpi_selNbBanniereIDLG())
		{
			return false;
		}
		if (!$this->objBanniereLg->stpi_setNbID($nbBanniereLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	
	public function stpi_getObjBanniereLg()
	{
		return $this->objBanniereLg;
	}
	
	
	public function stpi_getObjTypeBanniere()
	{
		return $this->objTypeBanniere;
	}

	
	public function stpi_getArrObjBanniereLg()
	{
		return $this->arrObjBanniereLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_banniere_Banniere (nbBanniereID, nbTypeBanniereID) VALUES (NULL, " . $this->objBdd->stpi_trsInputToBdd($this->nbTypeBanniereID) . ")";
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
		
		$SQL = "UPDATE stpi_banniere_Banniere";
		$SQL .= " SET nbTypeBanniereID='" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeBanniereID) . "'";
		$SQL .= " WHERE nbBanniereID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbBanniereID)
	{
		if (!$this->stpi_chkNbID($nnbBanniereID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_banniere_Banniere WHERE nbBanniereID=" . $this->objBdd->stpi_trsInputToBdd($nnbBanniereID);
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
	
	
	public function stpi_affHomePublic()
	{
		if (!$this->objTypeBanniere->stpi_setNbID(1))
		{
			return false;
		}
		
		print("<div class=\"banniere\" >\n");
		
		$this->objTypeBanniere->stpi_setObjTypeBanniereLgFromBdd();
		
		$objTypeBanniereLg =& $this->objTypeBanniere->stpi_getObjTypeBanniereLg();
		$objImg =& $this->objBanniereLg->stpi_getObjImg();
		
		$strDesc = $objTypeBanniereLg->stpi_getStrDesc();
		
		if (!empty($strDesc))
		{
			print("<p>" . $this->objBdd->stpi_trsBddToHTML($strDesc) . "</p>\n");
		}
		
		if (!$arrNbBanniereID = $this->objTypeBanniere->stpi_selNbBanniereID())
		{
			$arrNbBanniereID = array(); 
		}
		
		foreach ($arrNbBanniereID as $nbBanniereID)
		{
			if (!$this->stpi_setNbID($nbBanniereID))
			{
				return false;
			}
			
			$this->stpi_setObjBanniereLgFromBdd();
			
			if ($this->objBanniereLg->stpi_getNbImageID() != 0)
			{
				if ($objImg->stpi_setNbID($this->objBanniereLg->stpi_getNbImageID()))
				{
					print("<a href=\"" . $this->objBdd->stpi_trsBddToHTML($this->objBanniereLg->stpi_getStrLien()) . "\" >");
					print("<img width=\"" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbWidth()) . "px\" height=\"" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbHeight()) . "px\" alt=\"" . $this->objBdd->stpi_trsBddToHTML($this->objBanniereLg->stpi_getStrName()) . "\" src=\"./banniereimgaff.php?l=" . $this->objBdd->stpi_trsBddToHTML(LG) . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbID()) . "\" />");
					print("</a><br/>");
				}
			}
		}
		print("</div>\n");
	}
	
	
	public function stpi_affPartnersPublic()
	{
		if (!$this->objTypeBanniere->stpi_setNbID(2))
		{
			return false;
		}
		
		print("<div class=\"banniere\" >\n");
		
		$this->objTypeBanniere->stpi_setObjTypeBanniereLgFromBdd();
		
		$objTypeBanniereLg =& $this->objTypeBanniere->stpi_getObjTypeBanniereLg();
		$objImg =& $this->objBanniereLg->stpi_getObjImg();
		
		$strDesc = $objTypeBanniereLg->stpi_getStrDesc();
		
		print("<h2>" . $this->objBdd->stpi_trsBddToHTML($objTypeBanniereLg->stpi_getStrName()) . "</h2>\n");
		
		if (!empty($strDesc))
		{
			print("<p>" . $this->objBdd->stpi_trsBddToHTML($strDesc) . "</p>\n");
		}
		
		if (!$arrNbBanniereID = $this->objTypeBanniere->stpi_selNbBanniereID())
		{
			$arrNbBanniereID = array(); 
		}
		
		foreach ($arrNbBanniereID as $nbBanniereID)
		{
			if (!$this->stpi_setNbID($nbBanniereID))
			{
				return false;
			}
			
			$this->stpi_setObjBanniereLgFromBdd();
			
			if ($this->objBanniereLg->stpi_getNbImageID() != 0)
			{
				if ($objImg->stpi_setNbID($this->objBanniereLg->stpi_getNbImageID()))
				{
					print("<a href=\"" . $this->objBdd->stpi_trsBddToHTML($this->objBanniereLg->stpi_getStrLien()) . "\" >");
					print("<img width=\"" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbWidth()) . "px\" height=\"" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbHeight()) . "px\" alt=\"" . $this->objBdd->stpi_trsBddToHTML($this->objBanniereLg->stpi_getStrName()) . "\" src=\"./banniereimgaff.php?l=" . $this->objBdd->stpi_trsBddToHTML(LG) . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbID()) . "\" />");
					print("</a><br/>");
				}
			}
		}
		print("</div>\n");
	}
	
	
	public function stpi_affPublic()
	{
		if (!$arrNbTypeBanniereID = $this->objTypeBanniere->stpi_selAllPublic())
		{
			return false;
		}
		
		print("<div class=\"banniere\" >\n");
		
		foreach ($arrNbTypeBanniereID as $nbTypeBanniereID)
		{
					
			if (!$this->objTypeBanniere->stpi_setNbID($nbTypeBanniereID))
			{
				return false;
			}			
			
			$this->objTypeBanniere->stpi_setObjTypeBanniereLgFromBdd();
			
			$objTypeBanniereLg =& $this->objTypeBanniere->stpi_getObjTypeBanniereLg();
			$objImg =& $this->objBanniereLg->stpi_getObjImg();
			
			$strDesc = $objTypeBanniereLg->stpi_getStrDesc();
			
			print("<h2>" . $this->objBdd->stpi_trsBddToHTML($objTypeBanniereLg->stpi_getStrName()) . "</h2>\n");
			
			if (!empty($strDesc))
			{
				print("<p>" . $this->objBdd->stpi_trsBddToHTML($strDesc) . "</p>\n");
			}
			
			if (!$arrNbBanniereID = $this->objTypeBanniere->stpi_selNbBanniereID())
			{
				$arrNbBanniereID = array(); 
			}
			
			foreach ($arrNbBanniereID as $nbBanniereID)
			{
				if (!$this->stpi_setNbID($nbBanniereID))
				{
					return false;
				}
				
				$this->stpi_setObjBanniereLgFromBdd();
				
				if ($this->objBanniereLg->stpi_getNbImageID() != 0)
				{
					if ($objImg->stpi_setNbID($this->objBanniereLg->stpi_getNbImageID()))
					{
						print("<a href=\"" . $this->objBdd->stpi_trsBddToHTML($this->objBanniereLg->stpi_getStrLien()) . "\" >");
						print("<img width=\"" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbWidth()) . "px\" height=\"" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbHeight()) . "px\" alt=\"" . $this->objBdd->stpi_trsBddToHTML($this->objBanniereLg->stpi_getStrName()) . "\" src=\"./banniereimgaff.php?l=" . $this->objBdd->stpi_trsBddToHTML(LG) . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($objImg->stpi_getNbID()) . "\" />");
						print("</a><br/>");
					}
				}
			}
		}
		
		print("</div>\n");
	}
	
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchBanniere(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affBanniere").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affBanniere").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "bannieresaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affBanniere").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("banniere") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchBanniere(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affBanniere\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addBanniere()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_BanniereAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddBanniere").style.visibility = "hidden";
			var strParam = "";
			strParam = strParam + "nbTypeBanniereID=" + encodeURIComponent(document.getElementById("nbTypeBanniereID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strBanniereLien" + strLg[i]).value);
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strBanniereName" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./banniere.php?l=" + "<?php print(LG); ?>" + "&nbBanniereID=";
		  				var nbBanniereIDIndex = xmlHttp.responseText.indexOf("nbBanniereID") + 13;
		  				var nbBanniereIDLen = xmlHttp.responseText.length - nbBanniereIDIndex;
		  				var nbBanniereID = xmlHttp.responseText.substr(nbBanniereIDIndex, nbBanniereIDLen);
		  				strUrlRedirect = strUrlRedirect + nbBanniereID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddBanniere").style.visibility = "visible";
			  			document.getElementById("stpi_BanniereAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "banniereadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strBanniereName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strBanniereLien" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typebanniere") . "<br/>\n");				
		print("<select id=\"nbTypeBanniereID\">\n");
		print("<option selected=\"selected\" value=\"\"></option>\n");
		if ($arrNbTypeBanniereID = $this->objTypeBanniere->stpi_selAll())
		{
			foreach($arrNbTypeBanniereID as $nbTypeBanniereID)
			{
				if ($this->objTypeBanniere->stpi_setNbID($nbTypeBanniereID))
				{
					if ($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_setNbTypeBanniereID($nbTypeBanniereID))
					{
						if ($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_setNbID($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_selNbTypeBanniereIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeBanniereID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		print("<p>\n");
		print("<span id=\"stpi_BanniereAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddBanniere\" type=\"button\" onclick=\"stpi_addBanniere()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strBanniereName" + strLg[i]).disabled = false;
				document.getElementById("strBanniereLien" + strLg[i]).disabled = false;
			}
			document.getElementById("nbTypeBanniereID").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editBanniere()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbBanniereID=" + encodeURIComponent(document.getElementById("nbBanniereID").value);
			for (i in strLg)
			{
				strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strBanniereName" + strLg[i]).value);
		 		strParam = strParam + "&strLien" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strBanniereLien" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeBanniereID=" + encodeURIComponent(document.getElementById("nbTypeBanniereID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./banniere.php?l=" + "<?php print(LG); ?>" + "&nbBanniereID=";
		  				var nbBanniereIDIndex = xmlHttp.responseText.indexOf("nbBanniereID") + 13;
		  				var nbBanniereIDLen = xmlHttp.responseText.length - nbBanniereIDIndex;
		  				var nbBanniereID = xmlHttp.responseText.substr(nbBanniereIDIndex, nbBanniereIDLen);
		  				strUrlRedirect = strUrlRedirect + nbBanniereID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "banniereedit.php", true);
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
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strBanniereName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjBanniereLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("lien") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strBanniereLien" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjBanniereLg[$strLg]->stpi_getStrLien()) . "\" /><br/>\n");
			print("</p>\n");
			
			if ($this->arrObjBanniereLg[$strLg]->stpi_getNbImageID() != 0 && $this->arrObjBanniereLg[$strLg]->stpi_getObjImg()->stpi_setNbID($this->arrObjBanniereLg[$strLg]->stpi_getNbImageID()))
			{
				print("<img alt=\"" . $this->arrObjBanniereLg[$strLg]->stpi_getNbImageID() . "\" src=\"./banniereimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->arrObjBanniereLg[$strLg]->stpi_getNbImageID() . "\"/><br/>\n");
				print("<a href=\"./banniereimgedit.php?l=" . LG . "&amp;nbBanniereID=" . $this->nbID . "&amp;strLg=" . $strLg . "\">" . $this->objTexte->stpi_getArrTxt("editimg") . " - " . $this->objBdd->stpi_trsBddToHTML($strLg) . "</a><br/>\n");
			}
			else
			{
				print("<a href=\"./banniereimgadd.php?l=" . LG . "&amp;nbBanniereID=" . $this->nbID . "&amp;strLg=" . $strLg . "\">" . $this->objTexte->stpi_getArrTxt("addimg") . " - " . $this->objBdd->stpi_trsBddToHTML($strLg) . "</a><br/>\n");
			}
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typebanniere") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbTypeBanniereID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeBanniereID = $this->objTypeBanniere->stpi_selAll())
		{
			foreach($arrNbTypeBanniereID as $nbTypeBanniereID)
			{
				if ($this->objTypeBanniere->stpi_setNbID($nbTypeBanniereID))
				{
					if ($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_setNbTypeBanniereID($nbTypeBanniereID))
					{
						if ($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_setNbID($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_selNbTypeBanniereIDLG()))
						{
							print("<option");
							if ($this->nbTypeBanniereID == $this->objTypeBanniere->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeBanniereID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeBanniere->stpi_getObjTypeBanniereLg()->stpi_getStrName()) . "</option>\n");
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
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editBanniere()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delBanniere()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delBanniere()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "bannieredel.php?nbBanniereID=" + document.getElementById("nbBanniereID").value;
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
		function stpi_delBanniereConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "bannieredel.php?nbBanniereID=" + document.getElementById("nbBanniereID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./bannieres.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delBanniereConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
}
?>