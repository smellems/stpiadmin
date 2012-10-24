<?php
require_once(dirname(__FILE__) . "/clscatitemlg.php");
require_once(dirname(__FILE__) . "/../img/clsimg.php");
class clscatitem
{
	private $nbImgWidthMax = 200;
	private $nbImgHeightMax = 150;
	
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objCatItemLg;
	private $objImg;
	
	private $nbID;
	private $nbImageID;
	private $boolDelete;
	
	private $arrObjCatItemLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcatitem");
		$this->objLang = new clslang();
		$this->objCatItemLg = new clscatitemlg();
		$this->objImg = new clsimg("stpi_item_ImgCatItem");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbImageID = 0;
			$this->boolDelete = 1;
			$this->arrObjCatItemLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjCatItemLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbCatItemID", "stpi_item_CatItem"))
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
		
		$SQL = "SELECT nbImageID, boolDelete FROM stpi_item_CatItem WHERE nbCatItemID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbImageID = $row["nbImageID"];
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
	
	public function stpi_setNbImageID($nnbImageID)
	{
		if (!$this->objImg->stpi_chkNbID($nnbImageID))
		{
			return false;				
		}
		$this->nbImageID = $nnbImageID;
		return true;
	}
	
	public function stpi_setArrObjCatItemLgFromBdd()
	{
		if (!$this->objCatItemLg->stpi_setNbCatItemID($this->nbID))
		{
			return false;
		}
		if (!$arrNbCatItemId = $this->objCatItemLg->stpi_selNbCatItemID())
		{
			return false;
		}
		foreach ($arrNbCatItemId as $strLg => $nbCatItemLgID)
		{
			if (!$this->arrObjCatItemLg[$strLg] = new clsCatItemlg($nbCatItemLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjCatItemLgFromBdd()
	{
		if (!$this->objCatItemLg->stpi_setNbCatItemID($this->nbID))
		{
			return false;
		}
		if (!$nbCatItemLgId = $this->objCatItemLg->stpi_selNbCatItemIDLG())
		{
			return false;
		}
		if (!$this->objCatItemLg->stpi_setNbID($nbCatItemLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
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
	
	public function stpi_getObjImg()
	{
		return $this->objImg;
	}
	
	public function stpi_getObjCatItemLg()
	{
		return $this->objCatItemLg;
	}
	
	public function stpi_getArrObjCatItemLg()
	{
		return $this->arrObjCatItemLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_item_CatItem (nbCatItemID, nbImageID, boolDelete) VALUES (NULL, 0, 1)";
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
		$SQL = "UPDATE stpi_item_CatItem";
		$SQL .= " SET nbImageId='" . $this->objBdd->stpi_trsInputToBdd($this->nbImageID) . "'";
		$SQL .= " WHERE nbCatItemID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbCatItemID)
	{
		if (!$this->stpi_chkNbID($nnbCatItemID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_CatItem WHERE nbCatItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbCatItemID);
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
	
	
	public function stpi_affPublic()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$strPage = basename($_SERVER["SCRIPT_NAME"]);
		
		$this->stpi_setObjCatItemLgFromBdd();
		$objCatItemLg =& $this->stpi_getObjCatItemLg();
		$nbImageID = $this->nbImageID;
		if (!empty($nbImageID))
		{
			print("<a href=\"./" . $this->objBdd->stpi_trsBddToHTML($strPage) . "?l=" . $this->objBdd->stpi_trsBddToHTML(LG) . "&amp;nbCatItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" >");
			print("<img alt=\"" .  $this->objBdd->stpi_trsBddToHTML($objCatItemLg->stpi_getStrName()) . "\" src=\"./catitemimgaff.php?nbCatItemID=" .  $this->objBdd->stpi_trsBddToHTML($this->nbImageID) . "\" />\n");
			print("</a>\n");
			print("<div style=\"color: #0069AA; text-align: center;\">" . $this->objBdd->stpi_trsBddToHTML($objCatItemLg->stpi_getStrName()) . "</div>");				
		}
	}
	
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchCatItem(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affCatItem").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affCatItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemcatitemsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affCatItem").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("catitem") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchCatItem(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affCatItem\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addCatItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_CatItemAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddCatItem").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strCatItemName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strCatItemDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemcatitem.php?l=" + "<?php print(LG); ?>" + "&nbCatItemID=";
		  				var nbCatItemIDIndex = xmlHttp.responseText.indexOf("nbCatItemID") + 12;
		  				var nbCatItemIDLen = xmlHttp.responseText.length - nbCatItemIDIndex;
		  				var nbCatItemID = xmlHttp.responseText.substr(nbCatItemIDIndex, nbCatItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbCatItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddCatItem").style.visibility = "visible";
			  			document.getElementById("stpi_CatItemAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemcatitemadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strCatItemName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strCatItemDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_CatItemAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddCatItem\" type=\"button\" onclick=\"stpi_addCatItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strCatItemName" + strLg[i]).disabled = false;
				document.getElementById("strCatItemDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editCatItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbCatItemID=" + encodeURIComponent(document.getElementById("nbCatItemID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strCatItemName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strCatItemDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemcatitem.php?l=" + "<?php print(LG); ?>" + "&nbCatItemID=";
		  				var nbCatItemIDIndex = xmlHttp.responseText.indexOf("nbCatItemID") + 12;
		  				var nbCatItemIDLen = xmlHttp.responseText.length - nbCatItemIDIndex;
		  				var nbCatItemID = xmlHttp.responseText.substr(nbCatItemIDIndex, nbCatItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbCatItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemcatitemedit.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affImgEdit()
	{
		print("<form method=\"post\" action=\"./itemcatitemimgedit.php?l=" . LG);
		print("&amp;nbCatItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
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
	
	public function stpi_affImgAdd()
	{
		print("<form method=\"post\" action=\"./itemcatitemimgadd.php?l=" . LG);
		print("&amp;nbCatItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
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
		if ($this->nbImageID != 0 AND $this->objImg->stpi_setnbID($this->nbImageID))
		{
			print("<img alt=\"" . $this->nbImageID . "\" src=\"./itemcatitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->nbImageID . "\"/><br/>\n");
			print("<a href=\"./itemcatitemimgedit.php?l=" . LG . "&amp;nbCatItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("editimg") . "</a><br/>\n");
		}
		else
		{
			print("<a href=\"./itemcatitemimgadd.php?l=" . LG . "&amp;nbCatItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("addimg") . "</a><br/>\n");
		}
		
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		foreach ($arrLang as $strLg)	
		{
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strCatItemName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjCatItemLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strCatItemDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjCatItemLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editCatItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delCatItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delCatItem()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemcatitemdel.php?nbCatItemID=" + document.getElementById("nbCatItemID").value;
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
		function stpi_delCatItemConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemcatitemdel.php?nbCatItemID=" + document.getElementById("nbCatItemID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./items.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delCatItemConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_CatItem.nbCatItemID FROM stpi_item_CatItem, stpi_item_CatItem_Lg";
		$SQL .= " WHERE stpi_item_CatItem.nbCatItemID=stpi_item_CatItem_Lg.nbCatItemID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbCatItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selAllPublic($nboolRegistre = 0)
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_CatItem.nbCatItemID FROM stpi_item_CatItem, stpi_item_CatItem_Lg,stpi_item_Item_CatItem, stpi_item_Item_DispItem, stpi_item_SousItem";
		$SQL .= " WHERE stpi_item_CatItem.nbCatItemID=stpi_item_CatItem_Lg.nbCatItemID";
		$SQL .= " AND stpi_item_CatItem.nbCatItemID=stpi_item_Item_CatItem.nbCatItemID";
		$SQL .= " AND stpi_item_Item_CatItem.nbItemID=stpi_item_Item_DispItem.nbItemID";
		$SQL .= " AND stpi_item_Item_CatItem.nbItemID=stpi_item_SousItem.nbItemID";
		if ($nboolRegistre)
		{
			$SQL .= " AND ((stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0) OR stpi_item_Item_DispItem.nbDispItemID=2)";
		}
		else
		{		
			$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0";
		}
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbCatItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbItemID()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_Item_CatItem.nbItemID FROM stpi_item_Item_CatItem, stpi_item_Item_Lg";
		$SQL .= " WHERE stpi_item_Item_CatItem.nbItemID=stpi_item_Item_Lg.nbItemID";
		$SQL .= " AND nbCatItemID='" . $this->nbID . "'";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selNbTypeItemID()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_TypeItem_Lg.nbTypeItemID";
		$SQL .= " FROM stpi_item_Item_CatItem, stpi_item_Item, stpi_item_TypeItem_Lg";
		$SQL .= " WHERE stpi_item_Item_CatItem.nbItemID = stpi_item_Item.nbItemID";
		$SQL .= " AND stpi_item_Item_CatItem.nbCatItemID = '" . $this->nbID . "'";
		$SQL .= " AND stpi_item_Item.nbTypeItemID = stpi_item_TypeItem_Lg.nbTypeItemID";
		$SQL .= " AND stpi_item_TypeItem_Lg.strLg = '" . LG . "'";
		$SQL .= " ORDER BY stpi_item_TypeItem_Lg.strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selNbTypeItemIDPublic($nboolRegistre = 0)
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_TypeItem_Lg.nbTypeItemID";
		$SQL .= " FROM stpi_item_Item_CatItem, stpi_item_Item, stpi_item_TypeItem_Lg, stpi_item_Item_DispItem, stpi_item_SousItem";
		$SQL .= " WHERE stpi_item_Item_CatItem.nbItemID = stpi_item_Item.nbItemID";
		$SQL .= " AND stpi_item_Item_CatItem.nbCatItemID = '" . $this->nbID . "'";
		$SQL .= " AND stpi_item_Item.nbTypeItemID = stpi_item_TypeItem_Lg.nbTypeItemID";
		$SQL .= " AND stpi_item_Item.nbItemID=stpi_item_Item_DispItem.nbItemID";
		$SQL .= " AND stpi_item_Item.nbItemID=stpi_item_SousItem.nbItemID";
		if ($nboolRegistre)
		{
			$SQL .= " AND ((stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0) OR stpi_item_Item_DispItem.nbDispItemID=2)";
		}
		else
		{		
			$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0";
		}
		$SQL .= " AND stpi_item_TypeItem_Lg.strLg = '" . LG . "'";
		$SQL .= " ORDER BY stpi_item_TypeItem_Lg.strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeItemID"];
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
