<?php
require_once(dirname(__FILE__) . "/clsitemlg.php");
require_once(dirname(__FILE__) . "/clstypeitem.php");
require_once(dirname(__FILE__) . "/clscatitem.php");
require_once(dirname(__FILE__) . "/clsdispitem.php");
require_once(dirname(__FILE__) . "/clssousitem.php");
require_once(dirname(__FILE__) . "/../img/clsimg.php");
require_once(dirname(__FILE__) . "/../content/clsbody.php");
class clsitem
{
	private $nbImgWidthMax = 200;
	private $nbImgHeightMax = 200;
	
	private $objBdd;
	private $objBody;
	private $objTexte;
	private $objLang;
	private $thisLg;
	private $objImg;
	private $objTypeItem;
	private $objCatItem;
	private $objDispItem;
	private $objSousItem;
	
	private $nbID;
	private $nbTypeItemID;
	private $nbImageID;
	
	private $arrObjItemLg;
	private $arrNbCatItemID;
	private $arrNbDispItemID;
	private $arrNbSousItemID;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtitem");
		$this->objLang = new clslang();
		$this->objItemLg = new clsitemlg();
		$this->objImg = new clsimg("stpi_item_ImgItem");
		$this->objTypeItem = new clstypeitem();
		$this->objCatItem = new clscatitem();
		$this->objDispItem = new clsdispitem();
		$this->objSousItem = new clssousitem();
		$this->objBody = new clsbody();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbImageID = 0;
			$this->nbTypeItemID = 0;
			$this->nbCatItemID = 0;
			$this->arrObjItemLg = array();
			$this->arrNbCatItemID = array();
			$this->arrNbDispItemID = array();
		}
		else
		{
			if(!$this->stpi_setNbID($nnbID))
			{
				return false;
			}
			$this->arrObjItemLg = array();
			$this->arrNbCatItemID = array();
			$this->arrNbDispItemID = array();
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbItemID", "stpi_item_Item"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkIsNew()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (!$arrNbSousItemID = $this->stpi_selNbSousItemID())
		{
			return false;
		}
		
		$objSousItem =& $this->objSousItem;
		
		$boolIsNew = false;
		
		foreach ($arrNbSousItemID as $nbSousItemID)
		{
			if (!$objSousItem->stpi_setNbID($nbSousItemID))
			{
				return false;
			}
			if ($objSousItem->stpi_chkIsNew())
			{
				$boolIsNew = true;
			}
		}
		
		if ($boolIsNew)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	public function stpi_chkIsInRabais()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (!$arrNbSousItemID = $this->stpi_selNbSousItemID())
		{
			return false;
		}
				
		$objSousItem =& $this->objSousItem;
		
		$boolIsInRabais = false;
		
		foreach ($arrNbSousItemID as $nbSousItemID)
		{
			if (!$objSousItem->stpi_setNbID($nbSousItemID))
			{
				return false;
			}
			if ($objSousItem->stpi_chkIsInRabais())
			{
				$boolIsInRabais = true;
			}
		}
		
		if ($boolIsInRabais)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	public function stpi_chkDisponibilite($nnbItemDispID = 0)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if ($nnbItemDispID == 0)
		{
			return false;
		}
		
		if (!$arrNbDispItemID = $this->stpi_selNbDispItemID())
		{
			return false;
		}
		
		if (!in_array($nnbItemDispID, $arrNbDispItemID))
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
		
		$SQL = "SELECT nbTypeItemID, nbImageID FROM stpi_item_Item WHERE nbItemID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbTypeItemID = $row["nbTypeItemID"];
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
	
	public function stpi_setNbTypeItemID($nnbTypeItemID)
	{
		if (!$this->objTypeItem->stpi_chkNbID($nnbTypeItemID))
		{
			return false;				
		}
		$this->nbTypeItemID = $nnbTypeItemID;
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
	
	public function stpi_setArrObjItemLgFromBdd()
	{
		if (!$this->objItemLg->stpi_setNbItemID($this->nbID))
		{
			return false;
		}
		if (!$arrNbItemId = $this->objItemLg->stpi_selNbItemID())
		{
			return false;
		}
		foreach ($arrNbItemId as $strLg => $nbItemLgID)
		{
			if (!$this->arrObjItemLg[$strLg] = new clsItemlg($nbItemLgID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_setObjItemLgFromBdd()
	{
		if (!$this->objItemLg->stpi_setNbItemID($this->nbID))
		{
			return false;
		}
		if (!$nbItemLgId = $this->objItemLg->stpi_selNbItemIDLG())
		{
			return false;
		}
		if (!$this->objItemLg->stpi_setNbID($nbItemLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_setArrNbCatItemID($narrNbCatItemID)
	{
		if (count($narrNbCatItemID) == 0 AND $narrNbCatItemID != array())
		{
			return false;
		}
		foreach ($narrNbCatItemID as $nbCatItemID)
		{
			if (!$this->objCatItem->stpi_chkNbID($nbCatItemID))
			{
				return false;				
			}
		}
		$this->arrNbCatItemID = $narrNbCatItemID;
		return true;
	}
	
	public function stpi_setArrNbDispItemID($narrNbDispItemID)
	{
		if (count($narrNbDispItemID) == 0 AND $narrNbDispItemID != array())
		{
			return false;
		}
		foreach ($narrNbDispItemID as $nbDispItemID)
		{
			if (!$this->objDispItem->stpi_chkNbID($nbDispItemID))
			{
				return false;				
			}
		}
		$this->arrNbDispItemID = $narrNbDispItemID;
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbTypeItemID()
	{
		return $this->nbTypeItemID;
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
	
	public function stpi_getObjItemLg()
	{
		return $this->objItemLg;
	}
	
	public function stpi_getObjTypeItem()
	{
		return $this->objTypeItem;
	}
	
	public function stpi_getObjCatItem()
	{
		return $this->objCatItem;
	}
	
	public function stpi_getObjImg()
	{
		return $this->objImg;
	}
	
	public function stpi_getObjDispItem()
	{
		return $this->objDispItem;
	}
	
	public function stpi_getObjSousItem()
	{
		return $this->objSousItem;
	}
	
	public function stpi_getNbArrCatItemID()
	{
		return $this->arrNbCatItemID;
	}
	
	public function stpi_getArrNbDispItemID()
	{
		return $this->arrNbDispItemID;
	}
	
	public function stpi_getArrObjItemLg()
	{
		return $this->arrObjItemLg;
	}
	
	public function stpi_getStrSousItemDesc()
	{
		$strDesc = "";
		if ($this->objTypeItem->stpi_setNbID($this->stpi_getNbTypeItemID()))
		{
			if ($this->objTypeItem->stpi_setObjTypeItemLgFromBdd())
			{
				$strDesc .= $this->objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrName();
			}
		}
		
		if ($this->objItemLg->stpi_setNbItemID($this->stpi_getObjSousItem()->stpi_getNbItemID()))
		{
			if ($this->objItemLg->stpi_setNbID($this->stpi_getObjItemLg()->stpi_selNbItemIDLG()))
			{
				$strDesc .= " - " . $this->objItemLg->stpi_getStrName();
			}
		}
		
		if ($arrNbAttributID = $this->stpi_getObjSousItem()->stpi_selNbAttributID())
		{
			foreach($arrNbAttributID as $nbAttributID)
			{
				if ($this->objSousItem->stpi_getObjAttribut()->stpi_setNbID($nbAttributID))
				{
					if ($this->objSousItem->stpi_getObjAttribut()->stpi_setObjAttributLgFromBdd())
					{
						$strDesc .= ", " . $this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrName();
					}
				}
			}
		}
		return $strDesc;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_item_Item (nbItemID, nbTypeItemID, nbImageID) VALUES (NULL, " . $this->objBdd->stpi_trsInputToBdd($this->nbTypeItemID) . ", 0)";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			foreach ($this->arrNbCatItemID as $nbCatItemID)
			{
				$SQL = "INSERT INTO stpi_item_Item_CatItem (nbItemID, nbCatItemID) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($nbCatItemID) . ")";
				if (!$this->objBdd->stpi_insert($SQL))
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "&nbsp;(Cat)</span><br/>\n");
					return false;
				}
			}
			foreach ($this->arrNbDispItemID as $nbDispItemID)
			{
				$SQL = "INSERT INTO stpi_item_Item_DispItem (nbItemID, nbDispItemID) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($nbDispItemID) . ")";
				if (!$this->objBdd->stpi_insert($SQL))
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "&nbsp;(Disp)</span><br/>\n");
					return false;
				}
			}
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
		if (!$arrNbCatItemID = $this->stpi_getObjCatItem()->stpi_selAll())
		{
			return false;
		}
		if ($temp = $this->stpi_selNbCatItemID())
		{
			$arrNbCatItemIDDB = $temp;
		}
		else
		{
			$arrNbCatItemIDDB = array();
		}
		if (!$arrNbDispItemID = $this->stpi_getObjDispItem()->stpi_selAll())
		{
			return false;
		}
		if ($temp = $this->stpi_selNbDispItemID())
		{
			$arrNbDispItemIDDB = $temp;
		}
		else
		{
			$arrNbDispItemIDDB = array();
		}
		$SQL = "UPDATE stpi_item_Item";
		$SQL .= " SET nbTypeItemId=" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeItemID);
		$SQL .= ", nbImageId=" . $this->objBdd->stpi_trsInputToBdd($this->nbImageID);
		$SQL .= " WHERE nbItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		
		if ($this->objBdd->stpi_update($SQL))
		{
			if (!$nboolImage)
			{
				foreach ($arrNbCatItemID as $nbCatItemID)
				{
					if (!in_array($nbCatItemID, $arrNbCatItemIDDB) AND in_array($nbCatItemID, $this->arrNbCatItemID))
					{
						$SQL = "INSERT INTO stpi_item_Item_CatItem (nbItemID, nbCatItemID) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($nbCatItemID) . ")";
						if (!$this->objBdd->stpi_insert($SQL))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(Cat-insert)</span><br/>\n");
							return false;
						}
					}
					elseif (in_array($nbCatItemID, $arrNbCatItemIDDB) AND !in_array($nbCatItemID, $this->arrNbCatItemID))
					{
						$SQL = "DELETE FROM stpi_item_Item_CatItem WHERE nbItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . " AND nbCatItemID=" . $this->objBdd->stpi_trsInputToBdd($nbCatItemID);
						if (!$this->objBdd->stpi_delete($SQL))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(Cat-delete)</span><br/>\n");
							return false;
						}
					}
				}
				foreach ($arrNbDispItemID as $nbDispItemID)
				{
					if (!in_array($nbDispItemID, $arrNbDispItemIDDB) AND in_array($nbDispItemID, $this->arrNbDispItemID))
					{
						$SQL = "INSERT INTO stpi_item_Item_DispItem (nbItemID, nbDispItemID) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($nbDispItemID) . ")";
						if (!$this->objBdd->stpi_insert($SQL))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(Disp-insert)</span><br/>\n");
							return false;
						}
					}
					elseif (in_array($nbDispItemID, $arrNbDispItemIDDB) AND !in_array($nbDispItemID, $this->arrNbDispItemID))
					{
						$SQL = "DELETE FROM stpi_item_Item_DispItem WHERE nbItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . " AND nbDispItemID=" . $this->objBdd->stpi_trsInputToBdd($nbDispItemID);
						if (!$this->objBdd->stpi_delete($SQL))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(Disp-delete)</span><br/>\n");
							return false;
						}
					}
				}
			}
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_delete($nnbItemID)
	{
		if (!$this->stpi_setNbID($nnbItemID))
		{
			return false;				
		}
		
		if ($arrNbSousItemID = $this->stpi_selNbSousItemID())
		{
			foreach($arrNbSousItemID as $nbSousItemID)
			{
				if (!$this->objSousItem->stpi_delete($nbSousItemID))
				{
					return false;
				}
			}
		}
		
		if ($this->objBdd->stpi_chkExists($nnbItemID, "nbItemID", "stpi_item_Item_DispItem"))
		{
			$SQL = "DELETE FROM stpi_item_Item_DispItem WHERE nbItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbItemID);
			if (!$this->objBdd->stpi_delete($SQL))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(disp)</span><br/>\n");
				return false;
			}
		}
		
		if ($this->objBdd->stpi_chkExists($nnbItemID, "nbItemID", "stpi_item_Item_CatItem"))
		{
			$SQL = "DELETE FROM stpi_item_Item_CatItem WHERE nbItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbItemID);
			if (!$this->objBdd->stpi_delete($SQL))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(cat)</span><br/>\n");
				return false;
			}
		}

		$SQL = "DELETE FROM stpi_item_Item WHERE nbItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbItemID);
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_affSideMenuPublic($nboolRegistre = 0, $nnbCatItemID = 0, $nnbTypeItemID = 0)
	{
		$objCatItem =& $this->objCatItem;
		$objTypeItem =& $this->objTypeItem;
		$objCatItemLg =& $objCatItem->stpi_getObjCatItemLg();
		$objTypeItemLg =& $objTypeItem->stpi_getObjTypeItemLg();
		
		if ($nboolRegistre != 0 && $nboolRegistre != 1)
		{
			return false;
		}
		
		if (!$arrNbCatItemID = $objCatItem->stpi_selAllPublic($nboolRegistre))
		{
			$arrNbCatItemID = array();
		}
		print("</div></div>");

		print("<div id=\"wb-sec\"><div id=\"wb-sec-in\"><nav role=\"navigation\"><h2 id=\"wb-nav\">Secondary menu</h2><div class=\"wb-sec-def\">\n");
		foreach ($arrNbCatItemID as $nbCatItemID)
		{
			if (!$objCatItem->stpi_setNbID($nbCatItemID))
			{
				return false;
			}
			
			if (!$objCatItem->stpi_setObjCatItemLgFromBdd())
			{
				return false;
			}
			
			if ($nbCatItemID == $nnbCatItemID)
			{
				if (!$arrNbTypeItemID = $objCatItem->stpi_selNbTypeItemIDPublic($nboolRegistre))
				{
					$arrNbTypeItemID = array();
				}
				
				print("<section>");
				if ($nnbTypeItemID != 0)
				{
					print("<h3><a class=\"catactive\" href=\"./shop.php?l=" . LG . "&amp;nbCatItemID=" . $nbCatItemID . "\">");
				}
				else
				{
					print("<h3><a class=\"catactive\" href=\"./shop.php?l=" . LG . "\">");
				}
				print($this->objBdd->stpi_trsBddToHTML($objCatItemLg->stpi_getStrName()));
				print("</a></h3>\n");
				
				if (!empty($arrNbTypeItemID))
				{				
					print("<ul>\n");					
					foreach ($arrNbTypeItemID as $nbTypeItemID)
					{
						if (!$objTypeItem->stpi_setNbID($nbTypeItemID))
						{
							return false;
						}
						
						if (!$objTypeItem->stpi_setObjTypeItemLgFromBdd())
						{
							return false;
						}
						
						if ($nbTypeItemID == $nnbTypeItemID)
						{
							print("<li>\n");
							print("<a class=\"active\" href=\"./shop.php?l=" . LG . "&amp;nbCatItemID=" . $nbCatItemID . "&amp;nbTypeItemID=" . $nbTypeItemID . "\">");
							print($this->objBdd->stpi_trsBddToHTML($objTypeItemLg->stpi_getStrName()));
							print("</a>\n");
							print("</li>\n");	
						}
						else
						{
							print("<li>\n");
							print("<a href=\"./shop.php?l=" . LG . "&amp;nbCatItemID=" . $nbCatItemID . "&amp;nbTypeItemID=" . $nbTypeItemID . "\">");
							print($this->objBdd->stpi_trsBddToHTML($objTypeItemLg->stpi_getStrName()));
							print("</a>\n");
							print("</li>\n");
						}
					}					
					print("</ul>\n");
				}
				print("</section>\n");
			}
			else
			{
				print("<section>\n");
				print("<h3><a class=\"cat\" href=\"./shop.php?l=" . LG . "&amp;nbCatItemID=" . $nbCatItemID . "\">");
				print($this->objBdd->stpi_trsBddToHTML($objCatItemLg->stpi_getStrName()));
				print("</a></h3>\n");
				print("</section>\n");
			}
		}
		print("</div></nav></div></div></div></div>");

	}
	
	
	public function stpi_affJsSelectItem()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affSelectItem(nnbTypeItemID)
		{
			if (nnbTypeItemID.length == 0)
			{ 
				document.getElementById("stpi_selItem").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_selItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemselectaff.php?l=" + "<?php print(LG); ?>" + "&nbTypeItemID=" + nnbTypeItemID + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_selItem").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affSelectTypeItem($nnbTypeItemIDSelected = "", $nnbItemIDSelected = "", $nnbSousItemIDSelected = "", $nboolDisabled = 0)
	{
		if (!$arrNbTypeItemID = $this->objTypeItem->stpi_selAll())
		{
			$arrNbTypeItemID = array();
		}
		
		print("<select ");
		if ($nboolDisabled == 1)
		{
			print("disabled=\"disabled\"");
		}
		print(" onchange=\"stpi_affSelectItem(this.value)\" id=\"nbTypeItemID\">\n");
		print("<option value=\"\"></option>\n");
		foreach($arrNbTypeItemID as $nbTypeItemID)
		{
			if ($this->objTypeItem->stpi_setNbID($nbTypeItemID))
			{
				if ($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_setNbTypeItemID($nbTypeItemID))
				{
					if ($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_setNbID($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_selNbTypeItemIDLG()))
					{
						print("<option");
						if ($nbTypeItemID == $nnbTypeItemIDSelected)
						{
							print(" selected=\"selected\"");
						}
						print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeItemID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrName()) . "</option>\n");
					}
				}
			}
		}
		print("</select><br/>\n");
		print("<span id=\"stpi_selItem\">\n");
		if ($nnbTypeItemIDSelected != "")
		{
			$this->objTypeItem->stpi_setNbID($nnbTypeItemIDSelected);
			$this->stpi_affSelectItem($nnbItemIDSelected, $nnbSousItemIDSelected, $nboolDisabled);
		}
		print("</span><br/>\n");
		return true;
	}
	
	public function stpi_affJsSelectSousItem()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affSelectSousItem(nnbItemID)
		{
			if (nnbItemID.length == 0)
			{ 
				document.getElementById("stpi_selSousItem").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_selSousItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemsousitemselectaff.php?l=" + "<?php print(LG); ?>" + "&nbItemID=" + nnbItemID + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_selSousItem").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affSelectItem($nnbItemIDSelected = "", $nnbSousItemIDSelected = "", $nboolDisabled = 0)
	{
		if (!$arrNbItemID = $this->objTypeItem->stpi_selNbItemID())
		{
			$arrNbItemID = array();
		}
		print("<select ");
		if ($nboolDisabled == 1)
		{
			print("disabled=\"disabled\"");
		}
		print(" onchange=\"stpi_affSelectSousItem(this.value)\" id=\"nbItemID\">\n");
		print("<option value=\"\"></option>\n");
		foreach($arrNbItemID as $nbItemID)
		{
			if ($this->stpi_setNbID($nbItemID))
			{
				if ($this->stpi_getObjItemLg()->stpi_setNbItemID($nbItemID))
				{
					if ($this->stpi_getObjItemLg()->stpi_setNbID($this->stpi_getObjItemLg()->stpi_selNbItemIDLG()))
					{
						print("<option");
						if ($nbItemID == $nnbItemIDSelected)
						{
							print(" selected=\"selected\"");
						}
						print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbItemID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getObjItemLg()->stpi_getStrName()) . "</option>\n");
					}
				}
			}
		}
		print("</select><br/>\n");
		print("<span id=\"stpi_selSousItem\">\n");
		if ($nnbItemIDSelected != "")
		{
			$this->stpi_setNbID($nnbItemIDSelected);
			$this->stpi_affSelectSousItem($nnbSousItemIDSelected, $nboolDisabled);
		}
		print("</span><br/>\n");
		
		return true;
	}
	
	public function stpi_affSelectSousItem($nnbSousItemIDSelected = "", $nboolDisabled = 0)
	{
		if (!$arrNbSousItemID = $this->stpi_selNbSousItemID())
		{
			$arrNbSousItemID = array();
		}
		print("<select ");
		if ($nboolDisabled == 1)
		{
			print("disabled=\"disabled\"");
		}
		print(" onchange=\"stpi_affSousItemInfo(this.value)\" id=\"nbSousItem\">\n");
		print("<option value=\"\"></option>\n");
		foreach($arrNbSousItemID as $nbSousItemID)
		{
			print("<option");
			if ($nbSousItemID == $nnbSousItemIDSelected)
			{
				print(" selected=\"selected\"");
			}
			print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbSousItemID) . "\">");
			if ($this->objSousItem->stpi_setNbID($nbSousItemID))
			{
				if ($arrNbAttributID = $this->objSousItem->stpi_selNbAttributID())
				{
					print("(" . $this->objSousItem->stpi_getStrItemCode() . ")");
					foreach($arrNbAttributID as $nbAttributID)
					{
						if ($this->objSousItem->stpi_getObjAttribut()->stpi_setNbID($nbAttributID))
						{
							if ($this->objSousItem->stpi_getObjAttribut()->stpi_setObjAttributLgFromBdd())
							{
								print(" - " . $this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrName());
							}
						}
					}
				}
			}
			print("</option>\n");
		}
		print("</select>\n");
		return true;
	}
		
	
	public function stpi_affPublicPrixRange()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (empty($arrNbSousItemID))
		{
			if (!$arrNbSousItemID = $this->stpi_selNbSousItemIDPublic())
			{
				return false;
			}
		}
		
		$objSousItem =& $this->objSousItem;
		$objPrix =& $objSousItem->stpi_getObjPrix();
		
		if (!$objSousItem->stpi_setNbID($arrNbSousItemID[0]))
		{
			return false;
		}
		
		if (!$objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 1))
		{
			return false;
		}
		
		$nbPrix = $objPrix->stpi_getNbPrix();
			
		$nbPrix -= $nbPrix * $objPrix->stpi_getNbRabaisPour();
		
		$nbPrix -= $objPrix->stpi_getNbRabaisStat();
		
		$nbMinPrix = $nbPrix; 
		$nbMaxPrix = $nbPrix;
		
		for ($i = 1; $i < count($arrNbSousItemID); $i++)
		{
			if (!$objSousItem->stpi_setNbID($arrNbSousItemID[$i]))
			{
				return false;
			}
			
			if (!$objPrix->stpi_setNbID($objSousItem->stpi_getNbID(), 1))
			{
				return false;
			}
			
			$nbPrix = $objPrix->stpi_getNbPrix();
			
			$nbPrix -= $nbPrix * $objPrix->stpi_getNbRabaisPour();
			
			$nbPrix -= $objPrix->stpi_getNbRabaisStat();
			
			if ($nbMinPrix > $nbPrix)
			{
				$nbMinPrix = $nbPrix;
			}
			
			if ($nbMaxPrix < $nbPrix)
			{
				$nbMaxPrix = $nbPrix;
			}			
		}
		
		if ($nbMinPrix == $nbMaxPrix)
		{
			print("<p>" .$this->objBdd->stpi_trsBddToHTML($this->objBody->stpi_trsNbToPrix($nbMinPrix)) . " $</p>\n");
		}
		else
		{
			print("<p>" . $this->objBdd->stpi_trsBddToHTML($this->objBody->stpi_trsNbToPrix($nbMinPrix)) . " $ - " . $this->objBdd->stpi_trsBddToHTML($this->objBody->stpi_trsNbToPrix($nbMaxPrix)) . " $</p>\n");
		}
		
		return true;
	}
	
	
	public function stpi_affPublic($nboolRegistre = 0)
	{
		$arrNbTypeAttributIDFirst = array();
		$arrNbTypeAttributIDSecond = array();
		
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$this->stpi_setObjItemLgFromBdd();
		
		if (!$arrNbSousItemID = $this->stpi_selNbSousItemIDPublic())
		{
			return false;
		}
		
		print("<table style=\"width: 100%; padding: 0px; margin: 10px 0px;\">\n");
		print("<tr>\n");
		print("<td id=\"stpi_affItemImg" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" style=\"vertical-align: top; text-align: center; width:" . $this->objBdd->stpi_trsBddToHTML($this->nbImgWidthMax) . "px; height:" . $this->objBdd->stpi_trsBddToHTML($this->nbImgHeightMax) . "px;\" >\n");
		if (count($arrNbSousItemID) == 1)
		{
			if ($this->stpi_getObjSousItem()->stpi_setNbID($arrNbSousItemID[0]))
			{
				if (!$arrNbImageID = $this->stpi_getObjSousItem()->stpi_selNbImageID())
				{
					$arrNbImageID = array();
				}
				
				if (isset($arrNbImageID[1]))
				{
					print("<img style=\"cursor: pointer;\" onclick=\"window.open ('sousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($arrNbImageID[2]) . "', '', config='height=550, width=550, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')\" alt=\"" . $this->objBdd->stpi_trsBddToHTML($this->objItemLg->stpi_getStrName()) . "\" src=\"./sousitemimgaff.php?nbImageID=" . $this->objBdd->stpi_trsBddToHTML($arrNbImageID[1]) . "\" />\n");				
				}
			}
		}
		elseif ($this->nbImageID != 0)
		{
			print("<img alt=\"" . $this->objBdd->stpi_trsBddToHTML($this->objItemLg->stpi_getStrName()) . "\" src=\"./itemimgaff.php?nbImageID=" . $this->objBdd->stpi_trsBddToHTML($this->nbImageID) . "\" />\n");
		}
		print("</td>\n");
		print("<td style=\"vertical-align: top; text-align: left;\" >\n");
		print("<h3>\n");
		print($this->objBdd->stpi_trsBddToHTML($this->objItemLg->stpi_getStrName()));
		print("</h3>\n");
		
		if ($this->stpi_chkIsNew())
		{
			print("<img style=\"margin: 0px; padding: 0px 10px;\" width=\"75px\" height=\"50px\" alt=\"New\" src=\"./images/new" . LG . ".jpg\" />");
			print("<br/>\n");
		}
		
		$strDesc = $this->objItemLg->stpi_getStrDesc();
		if (!empty($strDesc))
		{
			print("<p>" . $this->objBdd->stpi_trsBddToBBCodeHTML($strDesc) . "</p>\n");
		}
		
		if (!$arrNbTypeAttributID = $this->stpi_selNbTypeAttributID())
		{
			$arrNbTypeAttributID = array();
		}
		
		foreach ($arrNbTypeAttributID as $nbTypeAttributID)
		{
			if (!$arrNbAttributID = $this->stpi_selNbAttributIDPublic($nbTypeAttributID, $nboolRegistre))
			{
				$arrNbAttributID = array();
			}
			
			if (count($arrNbAttributID) == 1)
			{
				$arrNbTypeAttributIDFirst[] = $nbTypeAttributID;
			}
			elseif (count($arrNbAttributID) > 1)
			{
				$arrNbTypeAttributIDSecond[] = $nbTypeAttributID;
			}		
		}

		foreach ($arrNbTypeAttributIDFirst as $nbTypeAttributID)
		{
			if (!$this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setNbID($nbTypeAttributID))
			{
				return false;
			}
			
			if ($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setObjTypeAttributLgFromBdd())
			{			
				print("<h4>\n");
				print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_getStrName()));
				print("</h4>\n");
			}
			
			if (!$arrNbAttributID = $this->stpi_selNbAttributIDPublic($nbTypeAttributID, $nboolRegistre))
			{
				$arrNbAttributID = array();
			}
			
			
			if ($this->objSousItem->stpi_getObjAttribut()->stpi_setNbID($arrNbAttributID[0]))
			{
				if ($this->objSousItem->stpi_getObjAttribut()->stpi_setObjAttributLgFromBdd())
				{
					print("<h5>&nbsp;&nbsp;\n");
					print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrName()));
					print("</h5>\n");
					
					print("<input type=\"hidden\" id=\"selNbSousItemID" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "_" . $this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getNbID()) . "\" value=\"". $arrNbAttributID[0] . "\"/>\n");
					
					$strDesc = $this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrDesc();
					if (!empty($strDesc))
					{				
						print("<p>&nbsp;&nbsp;" . $this->objBdd->stpi_trsBddToBBCodeHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrDesc()) . "</p>\n");
					}
				}
			}
			
		}
		
		foreach ($arrNbTypeAttributIDSecond as $nbTypeAttributID)
		{
			if (!$this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setNbID($nbTypeAttributID))
			{
				return false;
			}
			
			if ($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setObjTypeAttributLgFromBdd())
			{			
				print("<h4>\n");
				print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_getStrName()));
				print("</h4>\n");
			}
			
			if (!$arrNbAttributID = $this->stpi_selNbAttributIDPublic($nbTypeAttributID, $nboolRegistre))
			{
				$arrNbAttributID = array();
			}	
		
			print("<p>\n");			
			print("<select id=\"selNbSousItemID" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "_" . $this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getNbID()) . "\" onchange=\"stpi_affSousItemPrix(" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . ", Array('" . $this->objBdd->stpi_trsBddToHTML(implode("','", $arrNbTypeAttributID)) . "'))\" >\n");
			print("<option value=\"\" ></option>\n");
			foreach ($arrNbAttributID as $nbAttributID)
			{
				if ($this->objSousItem->stpi_getObjAttribut()->stpi_setNbID($nbAttributID))
				{
					if ($this->objSousItem->stpi_getObjAttribut()->stpi_setObjAttributLgFromBdd())
					{
						print("<option value=\"". $nbAttributID . "\" >");
						print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrName()));
						print("</option>\n");
					}
				}
			}
			print("</select>\n");
			print("</p>\n");
		}
				
		print("<div style=\"padding: 0px 10px; margin: 10px 0px 0px 0px;\" id=\"stpi_affSousItemPrix" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" >\n");
		if (count($arrNbSousItemID) == 1)
		{
			if ($this->objSousItem->stpi_setNbID($arrNbSousItemID[0]))
			{
				$boolAddCart = 0;
				if ($this->stpi_chkDisponibilite(1))
				{
					$boolAddCart = 1;
				}
				if ($nboolRegistre)
				{
					$nboolAddRegistre = 1;
					if (!$this->stpi_chkDisponibilite(2))
					{
						$nboolAddRegistre = 0;
					}
				}
				$this->objSousItem->stpi_affPrixPublic(0, $boolAddCart, $nboolAddRegistre);
			}
		}
		print("</div>\n");
		print("</td>\n");
		print("</tr>\n");		
		print("</table>\n");
	}
	
	public function stpi_affShopPublic($nboolRegistre = 0)
	{
		$arrNbTypeAttributIDFirst = array();
		$arrNbTypeAttributIDSecond = array();
		
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$this->stpi_setObjItemLgFromBdd();
		
		if (!$arrNbSousItemID = $this->stpi_selNbSousItemIDPublic())
		{
			return false;
		}
		
		print("<table style=\"padding: 0px; margin: 10px 0px;\">\n");
		print("<tr>\n");
		print("<td style=\"vertical-align: top; margin: 0px; padding: 0px 10px; width:" . $this->objBdd->stpi_trsBddToHTML($this->nbImgWidthMax) . "px;\" >\n");
		
		
		if ($this->stpi_getNbImageID() != 0)
		{
			print("<div style=\"width: " . $this->objBdd->stpi_trsBddToHTML($this->stpi_getNbImgWidthMax()) . "px; height: " . $this->objBdd->stpi_trsBddToHTML($this->stpi_getNbImgHeightMax()) . "px; text-align: center; vertical-align: top;\" >");
			print("<a href=\"./item.php?l=" . LG . "&amp;nbItemID=" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getNbID()));
			if ($this->stpi_getNbTypeItemID())
			{
				print("&amp;nbTypeItemID=" . $this->objBody->stpi_trsInputToHTML($this->stpi_getNbTypeItemID()));
			}
			if ($arrNbCatItemID = $this->stpi_selNbCatItemID($this->stpi_getNbTypeItemID()))
			{
				print("&amp;nbCatItemID=" . $this->objBody->stpi_trsInputToHTML($arrNbCatItemID[0]));
			}
			print("\" >\n");
			print("<img alt=\"" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getObjItemLg()->stpi_getStrName()) . "\" src=\"./itemimgaff.php?nbImageID=" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getNbImageID()) . "\" />\n");
			print("</a>\n");
			print("</div>");
		}
		
		if ($this->stpi_chkIsNew())
		{
			print("<img style=\"margin: 0px; padding: 0px 10px;\" width=\"75px\" height=\"50px\" alt=\"New\" src=\"./images/new" . LG . ".jpg\" />");
			print("<br/>\n");
		}
		
		print("<h5>");
		print("<a class=\"titre\" href=\"./item.php?l=" . LG . "&amp;nbItemID=" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getNbID()));
		if ($this->stpi_getNbTypeItemID())
		{
			print("&amp;nbTypeItemID=" . $this->objBody->stpi_trsInputToHTML($this->stpi_getNbTypeItemID()));
		}
		if ($arrNbCatItemID = $this->stpi_selNbCatItemID($this->stpi_getNbTypeItemID()))
		{
			print("&amp;nbCatItemID=" . $this->objBody->stpi_trsInputToHTML($arrNbCatItemID[0]));
		}
		print("\" >\n");
		print($this->objBdd->stpi_trsBddToHTML($this->stpi_getObjItemLg()->stpi_getStrName()));
		print("</a><br/>\n");
		print("</h5>\n");
		
		if (!$arrNbSousItemID = $this->stpi_selNbSousItemIDPublic())
		{
			$arrNbSousItemID = array();
		}
		
		if (count($arrNbSousItemID) > 1)
		{
			$this->stpi_affPublicPrixRange();
		}
			
		if (!$arrNbTypeAttributID = $this->stpi_selNbTypeAttributID())
		{
			$arrNbTypeAttributID = array();
		}
		
		foreach ($arrNbTypeAttributID as $nbTypeAttributID)
		{
			if (!$arrNbAttributID = $this->stpi_selNbAttributIDPublic($nbTypeAttributID, $nboolRegistre))
			{
				$arrNbAttributID = array();
			}
			
			if (count($arrNbAttributID) == 1)
			{
				$arrNbTypeAttributIDFirst[] = $nbTypeAttributID;
			}
			elseif (count($arrNbAttributID) > 1)
			{
				$arrNbTypeAttributIDSecond[] = $nbTypeAttributID;
			}		
		}

		foreach ($arrNbTypeAttributIDFirst as $nbTypeAttributID)
		{
			if (!$this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setNbID($nbTypeAttributID))
			{
				return false;
			}
			
			if (!$arrNbAttributID = $this->stpi_selNbAttributIDPublic($nbTypeAttributID, $nboolRegistre))
			{
				$arrNbAttributID = array();
			}
			
			if ($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setObjTypeAttributLgFromBdd())
			{			
				print("<h5>\n");
				print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_getStrName()));
				
				if ($this->objSousItem->stpi_getObjAttribut()->stpi_setNbID($arrNbAttributID[0]))
				{
					if ($this->objSousItem->stpi_getObjAttribut()->stpi_setObjAttributLgFromBdd())
					{
						print("&nbsp;:&nbsp;\n");
						print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrName()));
						print("<input type=\"hidden\" id=\"selNbSousItemID" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "_" . $this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getNbID()) . "\" value=\"". $arrNbAttributID[0] . "\"/>\n");
					}
				}	
				print("</h5>\n");
			}
		}
		
		foreach ($arrNbTypeAttributIDSecond as $nbTypeAttributID)
		{
			
			if (!$this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setNbID($nbTypeAttributID))
			{
				return false;
			}
			
			if (!$arrNbAttributID = $this->stpi_selNbAttributIDPublic($nbTypeAttributID, $nboolRegistre))
			{
				$arrNbAttributID = array();
			}
			
			if ($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_setObjTypeAttributLgFromBdd())
			{			
				print("<h5>\n");
				print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_getStrName()));
				print("</h5>\n");
			}
			
			print("<p>\n");			
			print("<select id=\"selNbSousItemID" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "_" . $this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjTypeAttribut()->stpi_getNbID()) . "\" onchange=\"stpi_affSousItemPrix(" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . ", Array('" . $this->objBdd->stpi_trsBddToHTML(implode("','", $arrNbTypeAttributID)) . "'))\" >\n");
			print("<option value=\"\" ></option>\n");
			foreach ($arrNbAttributID as $nbAttributID)
			{
				if ($this->objSousItem->stpi_getObjAttribut()->stpi_setNbID($nbAttributID))
				{
					if ($this->objSousItem->stpi_getObjAttribut()->stpi_setObjAttributLgFromBdd())
					{
						print("<option value=\"". $nbAttributID . "\" >");
						print($this->objBdd->stpi_trsBddToHTML($this->objSousItem->stpi_getObjAttribut()->stpi_getObjAttributLg()->stpi_getStrName()));
						print("</option>\n");
					}
				}
			}
			print("</select>\n");
			print("</p>\n");
		}

		print("<span id=\"stpi_affSousItemPrix" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" style=\"text-align: right;\" >\n");
		if (count($arrNbSousItemID) == 1)
		{
			if ($this->objSousItem->stpi_setNbID($arrNbSousItemID[0]))
			{
				$boolAddToCart = 0;
				if ($this->stpi_chkDisponibilite(1))
				{
					if ($this->objSousItem->stpi_getNbQte() > 0)
					{
						$boolAddToCart = 1;
					}
				}
				if ($nboolRegistre)
				{
					if (!$this->stpi_chkDisponibilite(2))
					{
						$nboolRegistre = 0;
					}
				}
				$this->objSousItem->stpi_affPrixPublic(0, $boolAddToCart, $nboolRegistre);
			}
		}
				
		print("</span>\n");
		print("</td>");
		print("</tr>\n");		
		print("</table>\n");
	}
				
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchItem(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affItem").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affItem").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("item") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchItem(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affItem\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addItem(narrCat, narrDisp)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_ItemAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddItem").style.visibility = "hidden";
			var strParam = "";
			for (i in strLg)
			{
				if (i != 0)
		  		{
		  			strParam = strParam + "&";
		  		}
		 		strParam = strParam + "strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strItemName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strItemDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeItemID=" + encodeURIComponent(document.getElementById("nbTypeItemID").value);
			for (i in narrCat)
			{
				if (narrCat[i] != 0)
				{
					strParam = strParam + "&nbCat" + narrCat[i] + "=" + encodeURIComponent(document.getElementById("nbCat" + narrCat[i]).value);
					if (document.getElementById("nbCat" + narrCat[i]).checked)
					{
						strParam = strParam + "&nbCat" + narrCat[i] + "=1";
					}
					else
					{
						strParam = strParam + "&nbCat" + narrCat[i] + "=0";
					}
				}
			}
			for (i in narrDisp)
			{
				if (narrDisp[i] != 0)
				{
					if (document.getElementById("nbDisp" + narrDisp[i]).checked)
					{
						strParam = strParam + "&nbDisp" + narrDisp[i] + "=1";
					}
					else
					{
						strParam = strParam + "&nbDisp" + narrDisp[i] + "=0";
					}
				}
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./item.php?l=" + "<?php print(LG); ?>" + "&nbItemID=";
		  				var nbItemIDIndex = xmlHttp.responseText.indexOf("nbItemID") + 9;
		  				var nbItemIDLen = xmlHttp.responseText.length - nbItemIDIndex;
		  				var nbItemID = xmlHttp.responseText.substr(nbItemIDIndex, nbItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddItem").style.visibility = "visible";
			  			document.getElementById("stpi_ItemAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemadd.php", true);
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
			print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strItemName" . $strLg . "\" value=\"\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea rows=\"4\" cols=\"40\" id=\"strItemDesc" . $strLg . "\"></textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typeitem") . "<br/>\n");
		print("<select id=\"nbTypeItemID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeItemID = $this->objTypeItem->stpi_selAll())
		{
			foreach($arrNbTypeItemID as $nbTypeItemID)
			{
				if ($this->objTypeItem->stpi_setNbID($nbTypeItemID))
				{
					if ($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_setNbTypeItemID($nbTypeItemID))
					{
						if ($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_setNbID($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_selNbTypeItemIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeItemID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}		
		print("</select>\n");
		print("</p>\n");
		
		$ajsCat = "";
		print($this->objTexte->stpi_getArrTxt("catitem") . "\n");
		if ($arrNbCatItemID = $this->objCatItem->stpi_selAll())
		{
			print("<table>\n");
			$nb = count($arrNbCatItemID);
			for($x = 0; $x < $nb; $x++)
			{
				$nbCatItemID = $arrNbCatItemID[$x];
				if ($this->objCatItem->stpi_setNbID($nbCatItemID))
				{
					if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbCatItemID($nbCatItemID))
					{
						if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbID($this->objCatItem->stpi_getObjCatItemLg()->stpi_selNbCatItemIDLG()))
						{
							print("<tr>\n");
							$ajsCat .= "," . $this->objBdd->stpi_trsBddToHTML($nbCatItemID);
							print("<td>&nbsp;&nbsp;<input id=\"nbCat" . $this->objBdd->stpi_trsBddToHTML($nbCatItemID) . "\" type=\"checkbox\"/>" . $this->objBdd->stpi_trsBddToHTML($this->objCatItem->stpi_getObjCatItemLg()->stpi_getStrName()) . "</td>\n");

							if ($nbCatItemID = $arrNbCatItemID[++$x])
							{
								if ($this->objCatItem->stpi_setNbID($nbCatItemID))
								{
									if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbCatItemID($nbCatItemID))
									{
										if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbID($this->objCatItem->stpi_getObjCatItemLg()->stpi_selNbCatItemIDLG()))
										{
											$ajsCat .= "," . $this->objBdd->stpi_trsBddToHTML($nbCatItemID);
											print("<td>&nbsp;&nbsp;&nbsp;<input id=\"nbCat" . $this->objBdd->stpi_trsBddToHTML($nbCatItemID) . "\" type=\"checkbox\"/>" . $this->objBdd->stpi_trsBddToHTML($this->objCatItem->stpi_getObjCatItemLg()->stpi_getStrName()) . "</td>\n");
										}
									}
								}
							}
							print("</tr>");
						}
					}
				}
			}
			print("</table>\n");
		}
		print("<br/>");
		$ajsDisp = "";
		print($this->objTexte->stpi_getArrTxt("dispitem") . "\n");
		if ($arrNbDispItemID = $this->objDispItem->stpi_selAll())
		{
			print("<table>\n");
			$nb = count($arrNbDispItemID);
			for($x = 0; $x < $nb; $x++)
			{
				$nbDispItemID = $arrNbDispItemID[$x];
				if ($this->objDispItem->stpi_setNbID($nbDispItemID))
				{
					if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbDispItemID($nbDispItemID))
					{
						if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbID($this->objDispItem->stpi_getObjDispItemLg()->stpi_selNbDispItemIDLG()))
						{
							print("<tr>\n");
							$ajsDisp .= "," . $this->objBdd->stpi_trsBddToHTML($nbDispItemID);
							print("<td>&nbsp;&nbsp;<input id=\"nbDisp" . $this->objBdd->stpi_trsBddToHTML($nbDispItemID) . "\" type=\"checkbox\"/>" . $this->objBdd->stpi_trsBddToHTML($this->objDispItem->stpi_getObjDispItemLg()->stpi_getStrName()) . "</td>\n");
							
							if ($nbDispItemID = $arrNbDispItemID[++$x])
							{
								if ($this->objDispItem->stpi_setNbID($nbDispItemID))
								{
									if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbDispItemID($nbDispItemID))
									{
										if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbID($this->objDispItem->stpi_getObjDispItemLg()->stpi_selNbDispItemIDLG()))
										{
											$ajsDisp .= "," . $this->objBdd->stpi_trsBddToHTML($nbDispItemID);
											print("<td>&nbsp;&nbsp;&nbsp;<input id=\"nbDisp" . $this->objBdd->stpi_trsBddToHTML($nbDispItemID) . "\" type=\"checkbox\"/>" . $this->objBdd->stpi_trsBddToHTML($this->objDispItem->stpi_getObjDispItemLg()->stpi_getStrName()) . "</td>\n");
										}
									}
								}
							}
							print("</tr>");
						}
					}
				}
			}
			print("</table>\n");
		}
		
		print("<p>\n");
		print("<span id=\"stpi_ItemAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddItem\" type=\"button\" onclick=\"stpi_addItem(Array(0" . $ajsCat . "),Array(0" . $ajsDisp . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable(narrCat, narrDisp)
		{
			for (i in strLg)
			{
				document.getElementById("strItemName" + strLg[i]).disabled = false;
				document.getElementById("strItemDesc" + strLg[i]).disabled = false;
			}
			document.getElementById("nbTypeItemID").disabled = false;
			for (i in narrCat)
			{
				if (narrCat[i] != 0)
				{
					document.getElementById("nbCat" + narrCat[i]).disabled = false;
				}
			}
			
			for (i in narrDisp)
			{
				if (narrDisp[i] != 0)
				{
					document.getElementById("nbDisp" + narrDisp[i]).disabled = false;
				}
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editItem(narrCat, narrDisp)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbItemID=" + encodeURIComponent(document.getElementById("nbItemID").value);
			for (i in strLg)
			{
		 		strParam = strParam + "&strName" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strItemName" + strLg[i]).value);
				strParam = strParam + "&strDesc" + strLg[i] + "=" + encodeURIComponent(document.getElementById("strItemDesc" + strLg[i]).value);
			}
			strParam = strParam + "&nbTypeItemID=" + encodeURIComponent(document.getElementById("nbTypeItemID").value);
			for (i in narrCat)
			{
				if (narrCat[i] != 0)
				{
					strParam = strParam + "&nbCat" + narrCat[i] + "=" + encodeURIComponent(document.getElementById("nbCat" + narrCat[i]).value);
					if (document.getElementById("nbCat" + narrCat[i]).checked)
					{
						strParam = strParam + "&nbCat" + narrCat[i] + "=1";
					}
					else
					{
						strParam = strParam + "&nbCat" + narrCat[i] + "=0";
					}
				}
			}
			for (i in narrDisp)
			{
				if (narrDisp[i] != 0)
				{
					if (document.getElementById("nbDisp" + narrDisp[i]).checked)
					{
						strParam = strParam + "&nbDisp" + narrDisp[i] + "=1";
					}
					else
					{
						strParam = strParam + "&nbDisp" + narrDisp[i] + "=0";
					}
				}
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./item.php?l=" + "<?php print(LG); ?>" + "&nbItemID=";
		  				var nbItemIDIndex = xmlHttp.responseText.indexOf("nbItemID") + 9;
		  				var nbItemIDLen = xmlHttp.responseText.length - nbItemIDIndex;
		  				var nbItemID = xmlHttp.responseText.substr(nbItemIDIndex, nbItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemedit.php", true);
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
		print("<form method=\"post\" action=\"./itemimgedit.php?l=" . LG);
		print("&amp;nbItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
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
		print("<form method=\"post\" action=\"./itemimgadd.php?l=" . LG);
		print("&amp;nbItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID));
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
			print("<img alt=\"" . $this->nbImageID . "\" src=\"./itemimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->nbImageID . "\"/><br/>\n");
			print("<a href=\"./itemimgedit.php?l=" . LG . "&amp;nbItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("editimg") . "</a><br/>\n");
		}
		else
		{
			print("<a href=\"./itemimgadd.php?l=" . LG . "&amp;nbItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("addimg") . "</a><br/>\n");
		}
		
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		foreach ($arrLang as $strLg)	
		{
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("name") . " " . $strLg . "<br/>\n");
			print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strItemName" . $strLg . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->arrObjItemLg[$strLg]->stpi_getStrName()) . "\" /><br/>\n");
			print("</p>\n");
			
			print("<p>\n");
			print($this->objTexte->stpi_getArrTxt("desc") . " " . $strLg . " (" . $this->objTexte->stpi_getArrTxt("facultatif") . ")<br/>\n");
			print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strItemDesc" . $strLg . "\">" . $this->objBdd->stpi_trsBddToHTML($this->arrObjItemLg[$strLg]->stpi_getStrDesc()) . "</textarea><br/>\n");
			print("</p>\n");
		}
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("typeitem") . "<br/>\n");				
		print("<select disabled=\"disabled\" id=\"nbTypeItemID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbTypeItemID = $this->objTypeItem->stpi_selAll())
		{
			foreach($arrNbTypeItemID as $nbTypeItemID)
			{
				if ($this->objTypeItem->stpi_setNbID($nbTypeItemID))
				{
					if ($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_setNbTypeItemID($nbTypeItemID))
					{
						if ($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_setNbID($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_selNbTypeItemIDLG()))
						{
							print("<option");
							if ($this->nbTypeItemID == $this->objTypeItem->stpi_getNbID())
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbTypeItemID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objTypeItem->stpi_getObjTypeItemLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		
		$ajsCat = "";
		print($this->objTexte->stpi_getArrTxt("catitem") . "\n");
		if ($arrNbCatItemID = $this->objCatItem->stpi_selAll())
		{
			if ($temp = $this->stpi_selNbCatItemID())
			{
				$this->arrNbCatItemID = $temp;
			}
			print("<table>\n");
			$nb = count($arrNbCatItemID);
			for($x = 0; $x < $nb; $x++)
			{
				$nbCatItemID = $arrNbCatItemID[$x];
				if ($this->objCatItem->stpi_setNbID($nbCatItemID))
				{
					if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbCatItemID($nbCatItemID))
					{
						if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbID($this->objCatItem->stpi_getObjCatItemLg()->stpi_selNbCatItemIDLG()))
						{
							print("<tr>\n");
							$ajsCat .= "," . $this->objBdd->stpi_trsBddToHTML($nbCatItemID);
							print("<td>&nbsp;&nbsp;<input disabled=\"disabled\" id=\"nbCat" . $this->objBdd->stpi_trsBddToHTML($nbCatItemID) . "\" type=\"checkbox\"");
							if (in_array($nbCatItemID, $this->arrNbCatItemID))
							{
								print(" checked=\"checked\"");
							}
							print("/>" . $this->objBdd->stpi_trsBddToHTML($this->objCatItem->stpi_getObjCatItemLg()->stpi_getStrName()) . "</td>\n");

							if ($nbCatItemID = $arrNbCatItemID[++$x])
							{
								if ($this->objCatItem->stpi_setNbID($nbCatItemID))
								{
									if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbCatItemID($nbCatItemID))
									{
										if ($this->objCatItem->stpi_getObjCatItemLg()->stpi_setNbID($this->objCatItem->stpi_getObjCatItemLg()->stpi_selNbCatItemIDLG()))
										{
											$ajsCat .= "," . $this->objBdd->stpi_trsBddToHTML($nbCatItemID);
											print("<td>&nbsp;&nbsp;&nbsp;<input disabled=\"disabled\" id=\"nbCat" . $this->objBdd->stpi_trsBddToHTML($nbCatItemID) . "\" type=\"checkbox\"");
											if (in_array($nbCatItemID, $this->arrNbCatItemID))
											{
												print(" checked=\"checked\"");
											}
											print("/>" . $this->objBdd->stpi_trsBddToHTML($this->objCatItem->stpi_getObjCatItemLg()->stpi_getStrName()) . "</td>\n");
										}
									}
								}
							}
							print("</tr>");
						}
					}
				}
			}
			print("</table>\n");
		}
		print("<br/>");
		$ajsDisp = "";
		print($this->objTexte->stpi_getArrTxt("dispitem") . "\n");
		if ($arrNbDispItemID = $this->objDispItem->stpi_selAll())
		{
			if ($temp = $this->stpi_selNbDispItemID())
			{
				$this->arrNbDispItemID = $temp;
			}
			print("<table>\n");
			$nb = count($arrNbDispItemID);
			for($x = 0; $x < $nb; $x++)
			{
				$nbDispItemID = $arrNbDispItemID[$x];
				if ($this->objDispItem->stpi_setNbID($nbDispItemID))
				{
					if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbDispItemID($nbDispItemID))
					{
						if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbID($this->objDispItem->stpi_getObjDispItemLg()->stpi_selNbDispItemIDLG()))
						{
							print("<tr>\n");
							$ajsDisp .= "," . $this->objBdd->stpi_trsBddToHTML($nbDispItemID);
							print("<td>&nbsp;&nbsp;<input disabled=\"disabled\" id=\"nbDisp" . $this->objBdd->stpi_trsBddToHTML($nbDispItemID) . "\" type=\"checkbox\"");
							if (in_array($nbDispItemID, $this->arrNbDispItemID))
							{
								print(" checked=\"checked\"");
							}
							print("/>" . $this->objBdd->stpi_trsBddToHTML($this->objDispItem->stpi_getObjDispItemLg()->stpi_getStrName()) . "</td>\n");
							
							if ($nbDispItemID = $arrNbDispItemID[++$x])
							{
								if ($this->objDispItem->stpi_setNbID($nbDispItemID))
								{
									if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbDispItemID($nbDispItemID))
									{
										if ($this->objDispItem->stpi_getObjDispItemLg()->stpi_setNbID($this->objDispItem->stpi_getObjDispItemLg()->stpi_selNbDispItemIDLG()))
										{
											$ajsDisp .= "," . $this->objBdd->stpi_trsBddToHTML($nbDispItemID);
											print("<td>&nbsp;&nbsp;&nbsp;<input disabled=\"disabled\" id=\"nbDisp" . $this->objBdd->stpi_trsBddToHTML($nbDispItemID) . "\" type=\"checkbox\"");
											if (in_array($nbDispItemID, $this->arrNbDispItemID))
											{
												print(" checked=\"checked\"");
											}
											print("/>" . $this->objBdd->stpi_trsBddToHTML($this->objDispItem->stpi_getObjDispItemLg()->stpi_getStrName()) . "</td>\n");
										}
									}
								}
							}
							print("</tr>");
						}
					}
				}
			}
			print("</table>\n");
		}
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable(Array(0" . $ajsCat . "),Array(0" . $ajsDisp . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editItem(Array(0" . $ajsCat . "),Array(0" . $ajsDisp . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delItem()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemdel.php?nbItemID=" + document.getElementById("nbItemID").value;
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
		function stpi_delItemConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemdel.php?nbItemID=" + document.getElementById("nbItemID").value;
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
		print("<input type=\"button\" onclick=\"stpi_delItemConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_selNbCatItemID($nbTypeItemID = 0)
	{
		$arrID = array();
		$SQL = "SELECT nbCatItemID";
		$SQL .= " FROM stpi_item_Item_CatItem, stpi_item_Item WHERE stpi_item_Item.nbItemID='" . $this->nbID . "'";
		$SQL .= " AND stpi_item_Item_CatItem.nbItemID=stpi_item_Item.nbItemID";
		if ($nbTypeItemID != 0)
		{
			$SQL .= " AND stpi_item_Item.nbTypeItemID=" . $this->objBdd->stpi_trsInputToBdd($nbTypeItemID);
		}
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
	
	public function stpi_selNbDispItemID()
	{
		$arrID = array();
		$SQL = "SELECT nbDispItemID FROM stpi_item_Item_DispItem WHERE nbItemID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbDispItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbSousItemID()
	{
		$arrID = array();
		$SQL = "SELECT nbSousItemID FROM stpi_item_SousItem WHERE nbItemID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSousItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selNbSousItemIDPublic($nboolRegistre = 0)
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_SousItem.nbSousItemID";
		$SQL .= " FROM stpi_item_SousItem, stpi_item_Item_DispItem";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_item_SousItem.nbItemID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_item_SousItem.nbItemID = stpi_item_Item_DispItem.nbItemID";
		if ($nboolRegistre)
		{
			$SQL .= " AND ((stpi_item_Item_DispItem.nbDispItemID = 1 AND stpi_item_SousItem.nbQte > 0) OR stpi_item_Item_DispItem.nbDispItemID = 2)";
		}
		else
		{		
			$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID = 1 AND stpi_item_SousItem.nbQte > 0";
		}
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSousItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selNbTypeAttributID()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_TypeAttribut_Lg.nbTypeAttributID";
		$SQL .= " FROM stpi_item_SousItem, stpi_item_SousItem_Attribut, stpi_item_Attribut, stpi_item_TypeAttribut_Lg";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_item_SousItem.nbSousItemID = stpi_item_SousItem_Attribut.nbSousItemID";
		$SQL .= " AND stpi_item_SousItem_Attribut.nbAttributID = stpi_item_Attribut.nbAttributID";
		$SQL .= " AND stpi_item_Attribut.nbTypeAttributID = stpi_item_TypeAttribut_Lg.nbTypeAttributID";
		$SQL .= " AND stpi_item_TypeAttribut_Lg.strLg = '" . LG . "'";
		$SQL .= " ORDER BY stpi_item_TypeAttribut_Lg.strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypeAttributID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selNbAttributID($nnbTypeAttributID = 0)
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_Attribut.nbAttributID";
		$SQL .= " FROM stpi_item_SousItem, stpi_item_SousItem_Attribut, stpi_item_Attribut, stpi_item_Attribut_Lg";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_item_SousItem.nbSousItemID = stpi_item_SousItem_Attribut.nbSousItemID";
		$SQL .= " AND stpi_item_SousItem_Attribut.nbAttributID = stpi_item_Attribut.nbAttributID";
		$SQL .= " AND stpi_item_Attribut.nbAttributID = stpi_item_Attribut_Lg.nbAttributID";
		$SQL .= " AND stpi_item_Attribut_Lg.strLg = '" . LG . "'";
		if ($nnbTypeAttributID != 0)
		{
			$SQL .= " AND stpi_item_Attribut.nbTypeAttributID = '" . $this->objBdd->stpi_trsInputToBdd($nnbTypeAttributID) . "'";
		}
		$SQL .= " ORDER BY stpi_item_Attribut.nbOrdre, stpi_item_Attribut_Lg.strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbAttributID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbAttributIDPublic($nnbTypeAttributID = 0, $nboolRegistre = 0)
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_Attribut.nbAttributID";
		$SQL .= " FROM stpi_item_SousItem, stpi_item_SousItem_Attribut, stpi_item_Attribut, stpi_item_Attribut_Lg, stpi_item_Item_DispItem";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_item_SousItem.nbItemID=stpi_item_Item_DispItem.nbItemID";
		$SQL .= " AND stpi_item_SousItem.nbSousItemID = stpi_item_SousItem_Attribut.nbSousItemID";
		$SQL .= " AND stpi_item_SousItem_Attribut.nbAttributID = stpi_item_Attribut.nbAttributID";
		$SQL .= " AND stpi_item_Attribut.nbAttributID = stpi_item_Attribut_Lg.nbAttributID";
		if ($nboolRegistre)
		{
			$SQL .= " AND ((stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0) OR stpi_item_Item_DispItem.nbDispItemID=2)";
		}
		else
		{		
			$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0";
		}
		$SQL .= " AND stpi_item_Attribut_Lg.strLg = '" . LG . "'";
		if ($nnbTypeAttributID != 0)
		{
			$SQL .= " AND stpi_item_Attribut.nbTypeAttributID = '" . $this->objBdd->stpi_trsInputToBdd($nnbTypeAttributID) . "'";
		}
		$SQL .= " ORDER BY stpi_item_Attribut.nbOrdre, stpi_item_Attribut_Lg.strName";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbAttributID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selSousItemNbImageID($nboolRegistre = 0)
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT stpi_item_SousItem_ImgSousItem.nbImageID";
		$SQL .= " FROM stpi_item_SousItem, stpi_item_SousItem_ImgSousItem, stpi_item_Item_DispItem";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_item_SousItem.nbItemID=stpi_item_Item_DispItem.nbItemID";
		$SQL .= " AND stpi_item_SousItem.nbSousItemID = stpi_item_SousItem_ImgSousItem.nbSousItemID";
		$SQL .= " AND stpi_item_SousItem_ImgSousItem.nbNumImage = 1";
		if ($nboolRegistre)
		{
			$SQL .= " AND ((stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0) OR stpi_item_Item_DispItem.nbDispItemID=2)";
		}
		else
		{		
			$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0";
		}
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbImageID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selAllPublic($nnbTypeItemID = 0, $nbCatItemID = 0, $nboolRegistre = 0)
	{
		$SQL = "SELECT DISTINCT stpi_item_Item.nbItemID";
		$SQL .= " FROM stpi_item_Item_CatItem, stpi_item_Item, stpi_item_Item_Lg, stpi_item_Item_DispItem, stpi_item_SousItem";
		$SQL .= " WHERE stpi_item_Item_CatItem.nbItemID=stpi_item_Item.nbItemID";
		$SQL .= " AND stpi_item_Item.nbItemID=stpi_item_Item_Lg.nbItemID";
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
		if ($nbCatItemID)
		{
			if ($this->objCatItem->stpi_chkNbID($nbCatItemID))
			{
				$SQL .= " AND stpi_item_Item_CatItem.nbCatItemID = '" . $this->objBdd->stpi_trsInputToBdd($nbCatItemID) . "'";
			}
		}
		if ($nnbTypeItemID)
		{
			if ($this->objTypeItem->stpi_chkNbID($nnbTypeItemID))
			{
				$SQL .= " AND stpi_item_Item.nbTypeItemID = '" . $this->objBdd->stpi_trsInputToBdd($nnbTypeItemID) . "'";
			}
		}
		$SQL .= " AND stpi_item_Item_Lg.strLg = '" . LG . "'";
		$SQL .= " ORDER BY stpi_item_Item_Lg.strName";
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
	
	
	public function stpi_selAllBestSeller($nnbLimit = 0, $nboolRegistre = 0)
	{
		//vérifier que l'item existe est disponible...
		$arrID = array();
		$SQL = "SELECT stpi_item_SousItem.nbItemID, COUNT(*)*stpi_commande_Commande_SousItem.nbQte AS nbCommande";
		$SQL .= " FROM stpi_commande_Commande_SousItem, stpi_item_SousItem, stpi_item_Item_DispItem";
		$SQL .= " WHERE stpi_commande_Commande_SousItem.nbSousItemID=stpi_item_SousItem.nbSousItemID";
		$SQL .= " AND stpi_item_SousItem.nbItemID=stpi_item_Item_DispItem.nbItemID";
		if ($nboolRegistre)
		{
			$SQL .= " AND ((stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0) OR stpi_item_Item_DispItem.nbDispItemID=2)";
		}
		else
		{		
			$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0";
		}
		$SQL .= " GROUP BY stpi_item_SousItem.nbItemID";
		$SQL .= " ORDER BY nbCommande DESC";
		if ($nnbLimit != 0)
		{
			 $SQL .= " LIMIT 0, " . $nnbLimit;
		}
		
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
}
?>
