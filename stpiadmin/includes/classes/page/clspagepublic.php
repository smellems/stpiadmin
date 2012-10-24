<?php
require_once(dirname(__FILE__) . "/clspagepubliclg.php");
class clspagepublic
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objPageLg;
	
	private $nbID;
	private $dtEntryDate;
	private $boolDelete;
	
	private $arrObjPageLg;
	
	public function __construct($nnbID = 0)
	{

		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtpage");
		$this->objLang = new clslang();
		$this->objPageLg = new clspagepubliclg();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->dtEntryDate = "";
			$this->boolDelete = 1;
			$this->arrObjPageLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjPageLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbPageID", "stpi_page_Page"))
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
		
		$SQL = "SELECT dtEntryDate, boolDelete FROM stpi_page_Page WHERE nbPageID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->dtEntryDate = $row["dtEntryDate"];
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
	
	public function stpi_setArrObjPageLgFromBdd()
	{
		if (!$this->objPageLg->stpi_setNbPageID($this->nbID))
		{
			return false;
		}
		if (!$arrNbPageId = $this->objPageLg->stpi_selNbPageID())
		{
			return false;
		}
		foreach ($arrNbPageId as $strLg => $nbPageLgID)
		{
			if (!$this->arrObjPageLg[$strLg] = new clspagepubliclg($nbPageLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjPageLgFromBdd()
	{
		$SQL = "SELECT nbPageLgID FROM stpi_page_Page_Lg WHERE nbPageID=" . $this->nbID . " AND strLg='" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$row = mysql_fetch_assoc($result);
			if (!$this->objPageLg->stpi_setNbID($row["nbPageLgID"]))
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
	
	public function stpi_getObjPageLg()
	{
		return $this->objPageLg;
	}
	
	public function stpi_getArrObjPageLg()
	{
		return $this->arrObjPageLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_page_Page (dtEntryDate, boolDelete) VALUES (NOW(), 1)";
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
	
	public function stpi_delete($nnbPageID)
	{
		if (!$this->stpi_chkNbID($nnbPageID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_page_Page WHERE nbPageID=" . $this->objBdd->stpi_trsInputToBdd($nnbPageID);
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
		function stpi_SearchPage(nstrTitre)
		{
			if (nstrTitre.length == 0)
			{ 
				document.getElementById("stpi_affPage").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affPage").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "pagesaff.php?strTitre=" + nstrTitre + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affPage").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("title") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchPage(this.value)\" maxlength=\"50\" size=\"20\" id=\"strTitre\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affPage\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addPage()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_PageAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddPage").style.visibility = "hidden";

			var strContentTxtArea = [];
			for(var i in CKEDITOR.instances) {
			   strContentTxtArea[CKEDITOR.instances[i].name] = CKEDITOR.instances[i].getData();
			}

			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strTitre" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTitre" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strDesc" + strLg[i]).value);
				strParam = strParam + "&strKeywords" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strKeywords" + strLg[i]).value);
				strParam = strParam + "&strContent" + strLg[i] + "=" + encodeURIComponent(strContentTxtArea["strContent" + strLg[i]]);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./page.php?l=" + "<?php print(LG); ?>" + "&nbPageID=";
		  				var nbPageIDIndex = xmlHttp.responseText.indexOf("nbPageID") + 9;
		  				var nbPageIDLen = xmlHttp.responseText.length - nbPageIDIndex;
		  				var nbPageID = xmlHttp.responseText.substr(nbPageIDIndex, nbPageIDIndex);
		  				strUrlRedirect = strUrlRedirect + nbPageID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddPage").style.visibility = "visible";
			  			document.getElementById("stpi_PageAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "pageadd.php", true);
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
			print($this->objTexte->stpi_getArrTxt("title") . " " . $strLg . "<br/>\n");
			print("<input type=\"text\" maxlength=\"75\" size=\"50\" id=\"strTitre" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . "<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("keywords") . " " . $strLg . "<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strKeywords" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("text") . " " . $strLg . "<br/>\n");
			print("<textarea rows=\"20\" cols=\"100\" id=\"strContent" . $strLg . "\"></textarea><br/><br/>\n");
			print("</p>\n");

			print("<script type=\"text/javascript\">\n");
			?>
			<!--
			CKEDITOR.replace("strContent" + "<?php print($strLg); ?>");
			-->
			<?php
			print("</script>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_PageAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddPage\" type=\"button\" onclick=\"stpi_addPage()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTitre" + strLg[i]).disabled = false;
				document.getElementById("strDesc" + strLg[i]).disabled = false;
				document.getElementById("strKeywords" + strLg[i]).disabled = false;
				document.getElementById("strContent" + strLg[i]).disabled = false;
				CKEDITOR.replace("strContent" + strLg[i]);
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editPage()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			document.getElementById("stpi_Save").style.visibility = "hidden";

			var strContentTxtArea = [];
			for(var i in CKEDITOR.instances) {
			   strContentTxtArea[CKEDITOR.instances[i].name] = CKEDITOR.instances[i].getData();
			}

			var strParam = "nbPageID=" + encodeURIComponent(document.getElementById("nbPageID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strTitre" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTitre" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strDesc" + strLg[i]).value);
				strParam = strParam + "&strKeywords" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strKeywords" + strLg[i]).value);
				strParam = strParam + "&strContent" + strLg[i] + "=" + encodeURIComponent(strContentTxtArea["strContent" + strLg[i]]);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./page.php?l=" + "<?php print(LG); ?>" + "&nbPageID=";
		  				var nbPageIDIndex = xmlHttp.responseText.indexOf("nbPageID") + 9;
		  				var nbPageIDLen = xmlHttp.responseText.length - nbPageIDIndex;
		  				var nbPageID = xmlHttp.responseText.substr(nbPageIDIndex, nbPageIDLen);
		  				strUrlRedirect = strUrlRedirect + nbPageID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "pageedit.php", true);
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
			print($this->objTexte->stpi_getArrTxt("title") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"75\" size=\"50\" id=\"strTitre" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjPageLg[$strLg]->stpi_getStrTitre()) . "\" /><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . "<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjPageLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("keywords") . " " . $strLg . "<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strKeywords" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjPageLg[$strLg]->stpi_getStrKeywords()) . "</textarea><br/>\n");
			print("</p>\n");
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("text") . " " . $strLg . "<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"20\" cols=\"100\" id=\"strContent" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjPageLg[$strLg]->stpi_getStrContent()) . "</textarea><br/><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editPage()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delPage()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delPage()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "pagedel.php?nbPageID=" + document.getElementById("nbPageID").value;
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
		function stpi_delPageConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "pagedel.php?nbPageID=" + document.getElementById("nbPageID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./pages.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delPageConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	
	public function stpi_selAll($nnbLimit = 0)
	{
		$SQL = "SELECT nbPageID";
		$SQL .= " FROM stpi_page_Page";
		$SQL .= " ORDER BY dtEntryDate DESC";
		if ($nnbLimit != 0)
		{
			 $SQL .= " LIMIT 0, " . $nnbLimit;
		}
		
		$arrID = array();
	
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbPageID"];
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
