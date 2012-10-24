<?php
require_once(dirname(__FILE__) . "/clstypeitemlg.php");
require_once(dirname(__FILE__) . "/../img/clsimg.php");
class clstypeitem
{
	private $nbImgs = 2;
	
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeItemLg;
	private $objImg;
	
	private $nbID;
	private $nbImageID;
	private $nbNumImage;
	
	private $arrObjTypeItemLg;
	private $arrNbImgWidthMax = array();
	private $arrNbImgHeightMax = array();
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypeitem");
		$this->objLang = new clslang();
		$this->objTypeItemLg = new clstypeitemlg();
		$this->objImg = new clsimg("stpi_item_ImgTypeItem");
		$this->arrNbImgHeightMax[1] = 100;
		$this->arrNbImgWidthMax[1] = 200;
		$this->arrNbImgHeightMax[2] = 180;
		$this->arrNbImgWidthMax[2] = 280;
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbImageID = 0;
			$this->arrObjTypeItemLg = array();
		}
		else
		{
			if(!$this->stpi_setnbID($nnbID))
			{
				return false;
			}
			$this->arrObjTypeItemLg = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypeItemID", "stpi_item_TypeItem"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbNumImage($nnbNumImage)
	{
		if (!is_numeric($nnbNumImage))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidnumimage") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbNumImage < 1 OR $nnbNumImage > $this->nbImgs)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidnumimage") . "&nbsp;([1," . $this->nbImgs . "])</span><br/>\n");
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
		return true;
	}
	
	public function stpi_setNbNumImage($nnbNumImage)
	{
		if (!$this->stpi_chkNbNumImage($nnbNumImage))
		{
			return false;
		}
		$this->nbNumImage = $nnbNumImage;
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
	
	public function stpi_setArrObjTypeItemLgFromBdd()
	{
		if (!$this->objTypeItemLg->stpi_setNbTypeItemID($this->nbID))
		{
			return false;
		}
		if (!$arrNbTypeItemId = $this->objTypeItemLg->stpi_selNbTypeItemID())
		{
			return false;
		}
		foreach ($arrNbTypeItemId as $strLg => $nbTypeItemLgID)
		{
			if (!$this->arrObjTypeItemLg[$strLg] = new clsTypeItemlg($nbTypeItemLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjTypeItemLgFromBdd()
	{
		if (!$this->objTypeItemLg->stpi_setNbTypeItemID($this->nbID))
		{
			return false;
		}
		if (!$nbTypeItemLgId = $this->objTypeItemLg->stpi_selNbTypeItemIDLG())
		{
			return false;
		}
		if (!$this->objTypeItemLg->stpi_setNbID($nbTypeItemLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbNumImage()
	{
		return $this->nbNumImage;
	}
	
	public function stpi_getNbImageID()
	{
		return $this->nbImageID;
	}
	
	public function stpi_getNbImgWidthMax()
	{
		if ($this->nbNumImage == 0)
		{
			return false;
		}
		return $this->arrNbImgWidthMax[$this->nbNumImage];
	}
	
	public function stpi_getNbImgHeightMax()
	{
		if ($this->nbNumImage == 0)
		{
			return false;
		}
		return $this->arrNbImgHeightMax[$this->nbNumImage];
	}	
	
	public function stpi_getObjTypeItemLg()
	{
		return $this->objTypeItemLg;
	}
	
	public function stpi_getObjImg()
	{
		return $this->objImg;
	}
	
	public function stpi_getArrObjTypeItemLg()
	{
		return $this->arrObjTypeItemLg;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_item_TypeItem (nbTypeItemID) VALUES (NULL)";
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
	
	public function stpi_update($nboolImage = false)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if ($nboolImage)
		{
			if ($this->nbImageID == 0)
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;nbImageID!=0</span><br/>\n");
				return false;
			}
			
			if ($temp = $this->stpi_selNbImageID())
			{
				$arrNbImageIDDB = $temp;
			}
			else
			{
				$arrNbImageIDDB = array();
			}
			
			if (!$arrNbImageIDDB[$this->nbNumImage])
			{
				$SQL = "INSERT INTO stpi_item_TypeItem_ImgTypeItem (nbTypeItemID, nbImageID, nbNumImage)";
				$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($this->nbImageID) . ", " . $this->objBdd->stpi_trsInputToBdd($this->nbNumImage) . ")";
				if (!$this->objBdd->stpi_insert($SQL))
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(image-insert)</span><br/>\n");
					return false;
				}
			}
			else
			{
				$SQL = "UPDATE stpi_item_TypeItem_ImgTypeItem SET nbImageID=" . $this->nbImageID;
				$SQL .= " WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
				$SQL .= " AND nbNumImage=" . $this->objBdd->stpi_trsInputToBdd($this->nbNumImage);
				if (!$this->objBdd->stpi_update($SQL))
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(image-update)</span><br/>\n");
					return false;
				}
				if (!$this->objBdd->stpi_chkExists($arrNbImageIDDB[$this->nbNumImage], "nbImageID", "stpi_item_SousItem_ImgSousItem"))
				{
					if (!$this->objImg->stpi_delete($arrNbImageIDDB[$this->nbNumImage]))
					{
						print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(image-delete)</span><br/>\n");
						return false;
					}
				}
			}
		}
		return true;
	}
	
	public function stpi_delete($nnbTypeItemID)
	{
		if (!$this->stpi_chkNbID($nnbTypeItemID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_TypeItem WHERE nbTypeItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeItemID);
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		if ($arrNbImageID = $this->stpi_selNbImageID())
		{
			$SQL = "DELETE FROM stpi_item_TypeItem_ImgTypeItem WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbSousItemID);
			if (!$this->objBdd->stpi_delete($SQL))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(ImgSousItem)</span><br/>\n");
				return false;
			}
			foreach($arrNbImageID as $nbImageID)
			{
				if (!$this->objBdd->stpi_chkExists($nbImageID, "nbImageID", "stpi_item_TypeItem_ImgTypeItem"))
				{
					if (!$this->objImg->stpi_delete($nbImageID))
					{
						print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(image)</span><br/>\n");
						return false;
					}
				}
			}
		}
		return true;
	}
	
	
	public function stpi_affPublic($nnbCatItemID = 0, $nboolTitre = 1, $nboolDesc = 1, $nboolLien = 1, $nboolImage = 1)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$strPage = basename($_SERVER["SCRIPT_NAME"]);
		
		$this->stpi_setObjTypeItemLgFromBdd();
						
		$objTypeItemLg =& $this->stpi_getObjTypeItemLg();
		
		print("<table style=\"padding: 0px; margin: 10px;\" >\n");
		print("<tr>\n");

		if ($nboolImage)
		{
			if ($this->nbImageID != 0)
			{
				print("<td style=\"width: " . $this->objBdd->stpi_trsBddToHTML($this->arrNbImgWidthMax[1]) . "px; height: " . $this->objBdd->stpi_trsBddToHTML($this->arrNbImgHeightMax[1]) . "px; text-align: left; vertical-align: top;\" >\n");
				if ($nboolLien)
				{
					print("<a href=\"./" . $this->objBdd->stpi_trsBddToHTML($strPage) . "?l=" . $this->objBdd->stpi_trsBddToHTML(LG));
					if ($nnbCatItemID != 0)
					{
						print("&amp;nbCatItemID=" . $this->objBdd->stpi_trsBddToHTML($nnbCatItemID));
					}
					print("&amp;nbTypeItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" >");
				}
				
				print("<img alt=\"" .  $this->objBdd->stpi_trsBddToHTML($objTypeItemLg->stpi_getStrName()) . "\" src=\"./typeitemimgaff.php?nbImageID=" .  $this->objBdd->stpi_trsBddToHTML($this->nbImageID) . "\" />\n");
	
				if ($nboolLien)
				{
					print("</a>\n");
				}
	
				print("</td>\n");							
			}
		}
		
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		
		if ($nboolTitre)
		{
			print("<h2>\n");
			if ($nboolLien)
			{
				print("<a class=\"titre\" href=\"./" . $this->objBdd->stpi_trsBddToHTML($strPage) . "?l=" . $this->objBdd->stpi_trsBddToHTML(LG));
				if ($nnbCatItemID != 0)
				{
					print("&amp;nbCatItemID=" . $this->objBdd->stpi_trsBddToHTML($nnbCatItemID));
				}
				print("&amp;nbTypeItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" >");
			}
			print($this->objBdd->stpi_trsBddToHTML($objTypeItemLg->stpi_getStrName()));
			if ($nboolLien)
			{
				print("</a>\n");
			}
			print("</h2>\n");
		}
		
		$strDesc = $objTypeItemLg->stpi_getStrDesc();
		if (!empty($strDesc))
		{
			if ($nboolDesc)
			{
				print("<p>" .  $this->objBdd->stpi_trsBddToBBCodeHTML($objTypeItemLg->stpi_getStrDesc()) . "</p>\n");
			}					
		}
		print("</td>\n");
		
		print("</tr>\n");
		print("</table>\n");
	}
	
	public function stpi_affJsImageLoop($nboolRegistre = 0)
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		<?php
			if ($arrNbTypeItemID = $this->stpi_selAllPublic($nboolRegistre))
			{
				print("var imgs = new Array(" . count($arrNbTypeItemID) . ");");
				$i = 0;
				foreach ($arrNbTypeItemID as $nbTypeItemID)
				{
					
					print("imgs[" . $i . "] = new Image;");
					print("imgs[0].src = './typeitemimgaff.php?nbImageID=35';");
				}
			}
		?>
		var imgs = new Array(4);
		
		imgs[0] = new Image;
		imgs[0].src = "./typeitemimgaff.php";
		
		
		function stpi_SearchTypeItem(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeItem").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeitemsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeItem").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchTypeItem(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affTypeItem").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affTypeItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeitemsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affTypeItem").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("typeitem") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchTypeItem(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affTypeItem\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addTypeItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_TypeItemAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddTypeItem").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeItemName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeItemDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemtypeitem.php?l=" + "<?php print(LG); ?>" + "&nbTypeItemID=";
		  				var nbTypeItemIDIndex = xmlHttp.responseText.indexOf("nbTypeItemID") + 13;
		  				var nbTypeItemIDLen = xmlHttp.responseText.length - nbTypeItemIDIndex;
		  				var nbTypeItemID = xmlHttp.responseText.substr(nbTypeItemIDIndex, nbTypeItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddTypeItem").style.visibility = "visible";
			  			document.getElementById("stpi_TypeItemAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemtypeitemadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeItemName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strTypeItemDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_TypeItemAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddTypeItem\" type=\"button\" onclick=\"stpi_addTypeItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
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
				document.getElementById("strTypeItemName" + strLg[i]).disabled = false;
				document.getElementById("strTypeItemDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editTypeItem()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbTypeItemID=" + encodeURIComponent(document.getElementById("nbTypeItemID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeItemName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strTypeItemDesc" + strLg[i]).value);
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemtypeitem.php?l=" + "<?php print(LG); ?>" + "&nbTypeItemID=";
		  				var nbTypeItemIDIndex = xmlHttp.responseText.indexOf("nbTypeItemID") + 13;
		  				var nbTypeItemIDLen = xmlHttp.responseText.length - nbTypeItemIDIndex;
		  				var nbTypeItemID = xmlHttp.responseText.substr(nbTypeItemIDIndex, nbTypeItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbTypeItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemtypeitemedit.php", true);
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
		print("<form method=\"post\" action=\"./itemtypeitemimgadd.php?l=" . LG);
		print("&amp;nbTypeItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "&amp;nbNumImage=" . $this->objBdd->stpi_trsBddToHTML($this->nbNumImage));
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
		print("<form method=\"post\" action=\"./itemtypeitemimgedit.php?l=" . LG);
		print("&amp;nbTypeItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "&amp;nbNumImage=" . $this->objBdd->stpi_trsBddToHTML($this->nbNumImage));
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
		$arrNbImageID = $this->stpi_selNbImageID();
		for ($nbNumImage = 1; $nbNumImage <= $this->nbImgs; $nbNumImage++)
		{
			if ($arrNbImageID[$nbNumImage] AND $this->objImg->stpi_setnbID($arrNbImageID[$nbNumImage]))
			{
				print("<img alt=\"" . $arrNbImageID[$nbNumImage] . "\" src=\"./itemtypeitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $arrNbImageID[$nbNumImage] . "\"/><br/>\n");
				print("<a href=\"./itemtypeitemimgedit.php?l=" . LG . "&amp;nbNumImage=" . $nbNumImage . "&amp;nbTypeItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("editimg") . " " . $nbNumImage . "</a><br/>\n");
			}
			else
			{
				print("<a href=\"./itemtypeitemimgadd.php?l=" . LG . "&amp;nbNumImage=" . $nbNumImage . "&amp;nbTypeItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("addimg") . " " . $nbNumImage . "</a><br/>\n");
			}
		}
		
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		foreach ($arrLang as $strLg)	
		{
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strTypeItemName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeItemLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strTypeItemDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjTypeItemLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editTypeItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delTypeItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delTypeItem()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeitemdel.php?nbTypeItemID=" + document.getElementById("nbTypeItemID").value;
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
		function stpi_delTypeItemConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemtypeitemdel.php?nbTypeItemID=" + document.getElementById("nbTypeItemID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delTypeItemConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_TypeItem.nbTypeItemID FROM stpi_item_TypeItem, stpi_item_TypeItem_Lg";
		$SQL .= " WHERE stpi_item_TypeItem.nbTypeItemID=stpi_item_TypeItem_Lg.nbTypeItemID";
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY strName";
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
	
	public function stpi_selAllNumImage($nnumImage, $nboolRegistre = 0)
	{
		if (!$this->stpi_chkNbNumImage($nnumImage))
		{
			return false;
		}
		
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_Item.nbTypeItemID FROM stpi_item_Item, stpi_item_TypeItem_Lg, stpi_item_TypeItem_ImgTypeItem, stpi_item_Item_DispItem, stpi_item_SousItem";
		$SQL .= " WHERE stpi_item_Item.nbTypeItemID=stpi_item_TypeItem_Lg.nbTypeItemID";
		$SQL .= " AND stpi_item_Item.nbTypeItemID=stpi_item_TypeItem_ImgTypeItem.nbTypeItemID";
		$SQL .= " AND stpi_item_TypeItem_ImgTypeItem.nbNumImage=" . $nnumImage;
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
		$SQL .= " AND strLg='" . LG . "'";
		$SQL .= " ORDER BY RAND()";
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
	
	public function stpi_selNbItemIDPublic($nboolRegistre = 0)
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_Item.nbItemID FROM stpi_item_Item, stpi_item_Item_Lg, stpi_item_Item_DispItem, stpi_item_SousItem";
		$SQL .= " WHERE stpi_item_Item.nbItemID=stpi_item_Item_Lg.nbItemID";
		$SQL .= " AND nbTypeItemID='" . $this->nbID . "'";
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
	
	public function stpi_selNbItemID()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_Item.nbItemID FROM stpi_item_Item, stpi_item_Item_Lg";
		$SQL .= " WHERE stpi_item_Item.nbItemID=stpi_item_Item_Lg.nbItemID";
		$SQL .= " AND nbTypeItemID='" . $this->nbID . "'";
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
	
	public function stpi_selNbImageID()
	{
		$arrID = array();
		$SQL = "SELECT nbImageID, nbNumImage FROM stpi_item_TypeItem_ImgTypeItem WHERE nbTypeItemID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[$row["nbNumImage"]] = $row["nbImageID"];
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