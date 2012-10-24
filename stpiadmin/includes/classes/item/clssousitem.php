<?php
require_once(dirname(__FILE__) . "/clsattribut.php");
require_once(dirname(__FILE__) . "/clsprix.php");
require_once(dirname(__FILE__) . "/../img/clsimg.php");
class clssousitem
{
	private $nbImgs = 2;
	
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objAttribut;
	private $objPrix;
	private $objImg;
	
	private $nbID;
	private $nbItemID;
	private $nbUnits;
	private $nbQte;
	private $nbQteMin;
	private $nbImageID;
	private $nbNumImage;
	private $strItemCode;
	private $boolTaxable;
	private $dtEntryDate;
	
	private $nbNewDays = 30;
	
	private $arrNbAttributID = array();
	// private $arrNbPrixID = array();
	private $arrNbImgWidthMax = array();
	private $arrNbImgHeightMax = array();
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtsousitem");
		$this->objLang = new clslang();
		$this->objImg = new clsimg("stpi_item_ImgSousItem");
		$this->arrNbImgHeightMax[1] = 150;
		$this->arrNbImgWidthMax[1] = 150;
		$this->arrNbImgHeightMax[2] = 500;
		$this->arrNbImgWidthMax[2] = 500;
		$this->objAttribut = new clsattribut();
		$this->objPrix = new clsprix();
		if ($nnbID == 0)
		{
			$this->nbItemID = 0;
			$this->nbUnits = 0;
			$this->nbQte = 0;
			$this->nbQteMin = 0;
			$this->nbImageID = 0;
			$this->nbNumImage = 0;
			$this->strItemCode = "";
			$this->boolTaxable = 0;
			$this->dtEntryDate = "";
		}
		else
		{
			if(!$this->stpi_setNbID($nnbID))
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbSousItemID", "stpi_item_SousItem"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbItemID($nnbID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbItemID", "stpi_item_Item"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(item)</span><br/>\n");
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
	
	public function stpi_chkNbUnits($nnbUnits)
	{
		if (!is_numeric($nnbUnits))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidunits") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbUnits < 0 OR $nnbUnits > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidunits") . "&nbsp;([0,1000000000])</span><br/>\n");
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
		if ($nnbQte < 0 OR $nnbQte > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqte") . "&nbsp;([0,1000000000])</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkNbQteMin($nnbQteMin)
	{
		if (!is_numeric($nnbQteMin))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqtemin") . "&nbsp;(!is_numeric)</span><br/>\n");
			return false;
		}
		if ($nnbQteMin < 0 OR $nnbQteMin > 1000000000)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidqtemin") . "&nbsp;([0,1000000000])</span><br/>\n");
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
	
	public function stpi_chkBoolTaxable($nboolTaxable)
	{
		if ($nboolTaxable != "1" AND $nboolTaxable != "0")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtaxable") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	

	public function stpi_chkIsNew()
	{
		if (time() < (strtotime($this->dtEntryDate) + ($this->nbNewDays * 24 * 60 * 60)))
		{
			return true;				
		}
		return false;
	}
	
	
	public function stpi_chkIsInRabais()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (!$this->objPrix->stpi_setNbID($this->nbID, 1))
		{
			return false;
		}
		
		if ($this->objPrix->stpi_getNbRabaisPour() != 0 || $this->objPrix->stpi_getNbRabaisStat() != 0)
		{
			return true;				
		}
		
		return false;
	}
	
	
	public function stpi_setNbID($nnbID)
	{
		if (!$this->stpi_chkNbID($nnbID))
		{
			return false;				
		}
		$this->nbID = $nnbID;
		
		$SQL = "SELECT nbItemID, nbUnits, nbQte, nbQteMin, strItemCode, boolTaxable, dtEntryDate";
		$SQL .= " FROM stpi_item_SousItem WHERE nbSousItemID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbItemID = $row["nbItemID"];
				$this->nbUnits = $row["nbUnits"];
				$this->nbQte = $row["nbQte"];
				$this->nbQteMin = $row["nbQteMin"];
				$this->strItemCode = $row["strItemCode"];
				$this->boolTaxable = $row["boolTaxable"];
				$this->dtEntryDate = $row["dtEntryDate"];
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
	
	public function stpi_setNbItemID($nnbItemID)
	{
		if (!$this->stpi_chkNbItemID($nnbItemID))
		{
			return false;				
		}
		$this->nbItemID = $nnbItemID;
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
	
	public function stpi_setNbUnits($nnbUnits)
	{
		if (!$this->stpi_chkNbUnits($nnbUnits))
		{
			return false;				
		}
		$this->nbUnits = $nnbUnits;
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
	
	public function stpi_setNbQteMin($nnbQteMin)
	{
		if (!$this->stpi_chkNbQteMin($nnbQteMin))
		{
			return false;				
		}
		$this->nbQteMin = $nnbQteMin;
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
	
	public function stpi_setBoolTaxable($nboolTaxable)
	{
		if (!$this->stpi_chkBoolTaxable($nboolTaxable))
		{
			return false;				
		}
		$this->boolTaxable = $nboolTaxable;
		return true;
	}
	
	public function stpi_setArrNbAttributID($narrNbAttributID)
	{
		if (count($narrNbAttributID) == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidattribut") . "</span><br/>\n");
			return false;
		}
		foreach ($narrNbAttributID as $nbAttributID)
		{
			if (!$this->objAttribut->stpi_chkNbID($nbAttributID))
			{
				return false;				
			}
		}
		$this->arrNbAttributID = $narrNbAttributID;
		return true;
	}
	
	public function stpi_setArrNbPrixID($narrNbPrixID)
	{
		if (count($narrNbPrixID) == 0 AND $narrNbPrixID != array())
		{
			return false;
		}
		foreach ($narrNbPrixID as $nbPrixID)
		{
			if (!$this->objPrix->stpi_chkNbID($nbPrixID))
			{
				return false;				
			}
		}
		$this->arrNbPrixID = $narrNbPrixID;
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbItemID()
	{
		return $this->nbItemID;
	}
	
	public function stpi_getNbUnits()
	{
		return $this->nbUnits;
	}
	
	public function stpi_getNbQte()
	{
		return $this->nbQte;
	}
	
	public function stpi_getNbQteMin()
	{
		return $this->nbQteMin;
	}
	
	public function stpi_getStrItemCode()
	{
		return $this->strItemCode;
	}
	
	public function stpi_getBoolTaxable()
	{
		return $this->boolTaxable;
	}
	
	public function stpi_getObjAttribut()
	{
		return $this->objAttribut;
	}
	
	public function stpi_getObjPrix()
	{
		return $this->objPrix;
	}
	
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	public function stpi_getObjImg()
	{
		return $this->objImg;
	}
	
	public function stpi_getNbArrAttributID()
	{
		return $this->arrNbAttributID;
	}
	
	public function stpi_getNbArrPrixID()
	{
		return $this->arrNbPrixID;
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
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		$SQL = "INSERT INTO stpi_item_SousItem (nbItemID, nbUnits, nbQte, nbQteMin, strItemCode, boolTaxable, dtEntryDate)";
		$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbItemID);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbUnits);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbQte);
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->nbQteMin);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strItemCode) . "'";
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->boolTaxable);
		$SQL .= ", NOW())";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			foreach ($this->arrNbAttributID as $nbAttributID)
			{
				$SQL = "INSERT INTO stpi_item_SousItem_Attribut (nbSousItemID, nbAttributID) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($nbAttributID) . ")";
				if (!$this->objBdd->stpi_insert($SQL))
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "&nbsp;(Attribut)</span><br/>\n");
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
	
	public function stpi_update($nboolImage = false, $nboolAttributs = false)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		if (!$arrNbAttributID = $this->objAttribut->stpi_selAll())
		{
			return false;
		}
		if ($temp = $this->stpi_selNbAttributID())
		{
			$arrNbAttributIDDB = $temp;
		}
		else
		{
			$arrNbAttributIDDB = array();
		}

		$SQL = "UPDATE stpi_item_SousItem";
		$SQL .= " SET nbItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbItemID);
		$SQL .= ", nbUnits=" . $this->objBdd->stpi_trsInputToBdd($this->nbUnits);
		$SQL .= ", nbQte=" . $this->objBdd->stpi_trsInputToBdd($this->nbQte);
		$SQL .= ", nbQteMin=" . $this->objBdd->stpi_trsInputToBdd($this->nbQteMin);
		$SQL .= ", strItemCode='" . $this->objBdd->stpi_trsInputToBdd($this->strItemCode) . "'";
		$SQL .= ", boolTaxable=" . $this->objBdd->stpi_trsInputToBdd($this->boolTaxable);
		$SQL .= " WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		
		if ($this->objBdd->stpi_update($SQL))
		{
			if ($nboolAttributs)
			{
				foreach ($arrNbAttributID as $nbAttributID)
				{
					if (!in_array($nbAttributID, $arrNbAttributIDDB) AND in_array($nbAttributID, $this->arrNbAttributID))
					{
						$SQL = "INSERT INTO stpi_item_SousItem_Attribut (nbSousItemID, nbAttributID) VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($nbAttributID) . ")";
						if (!$this->objBdd->stpi_insert($SQL))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(attribut-insert)</span><br/>\n");
							return false;
						}
					}
					elseif (in_array($nbAttributID, $arrNbAttributIDDB) AND !in_array($nbAttributID, $this->arrNbAttributID))
					{
						$SQL = "DELETE FROM stpi_item_SousItem_Attribut WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . " AND nbAttributID=" . $this->objBdd->stpi_trsInputToBdd($nbAttributID);
						if (!$this->objBdd->stpi_delete($SQL))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(attribut-delete)</span><br/>\n");
							return false;
						}
					}
				}
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
					$SQL = "INSERT INTO stpi_item_SousItem_ImgSousItem (nbSousItemID, nbImageID, nbNumImage)";
					$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . ", " . $this->objBdd->stpi_trsInputToBdd($this->nbImageID) . ", " . $this->objBdd->stpi_trsInputToBdd($this->nbNumImage) . ")";
					if (!$this->objBdd->stpi_insert($SQL))
					{
						print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(image-insert)</span><br/>\n");
						return false;
					}
				}
				else
				{
					$SQL = "UPDATE stpi_item_SousItem_ImgSousItem SET nbImageID=" . $this->nbImageID;
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
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_delete($nnbSousItemID)
	{
		if (!$this->stpi_setNbID($nnbSousItemID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_item_SousItem WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbSousItemID);
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		$SQL = "DELETE FROM stpi_item_SousItem_Attribut WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbSousItemID);
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(attribut)</span><br/>\n");
			return false;
		}
		
		if ($arrNbImageID = $this->stpi_selNbImageID())
		{
			$SQL = "DELETE FROM stpi_item_SousItem_ImgSousItem WHERE nbSousItemID=" . $this->objBdd->stpi_trsInputToBdd($nnbSousItemID);
			if (!$this->objBdd->stpi_delete($SQL))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(ImgSousItem)</span><br/>\n");
				return false;
			}
			foreach($arrNbImageID as $nbImageID)
			{
				if (!$this->objBdd->stpi_chkExists($nbImageID, "nbImageID", "stpi_item_SousItem_ImgSousItem"))
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
	
	
	public function stpi_affJsTagImgPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--		
		function stpi_affSousItemTagImg(nnbItemID, narrTypeAttributID)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affItemImg" + nnbItemID).innerHTML = strErrXmlHttpObject;
		  		return;
			}
			var strParam = "nbItemID=" + encodeURIComponent(nnbItemID);
			var i;
			for (i in narrTypeAttributID)
  			{
  				strParam = strParam + "&nbAttributID" + i + "=" + encodeURIComponent(document.getElementById("selNbSousItemID" + nnbItemID + "_" + narrTypeAttributID[i]).value);
  			}			
			strParam = strParam + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
					try
					{
						document.getElementById("stpi_affItemImg" + nnbItemID).innerHTML = xmlHttp.responseText;
					}
					catch(e)
					{
						// rien
					}
		  		}
			}
			xmlHttp.open("POST", "sousitemtagimgaff.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsTagImgLargePublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--		
		function stpi_affSousItemTagImgLarge(nnbImageID, nnbWidth, nnbHeight, nstrAlt)
		{
			var strImgTag = "<img style=\"cursor: pointer;\" onclick=\"window.open ('sousitemimgaff.php?l=<? print(LG); ?>&amp;nbImageID=" + encodeURIComponent(nnbImageID) + "', '', config='height=550, width=550, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')\" width='" + nnbWidth + "px' height='" + nnbHeight + "px' alt='" + nstrAlt + "' src='./sousitemimgaff.php?l=<? print(LG); ?>&amp;nbImageID=" + nnbImageID + "' />\n";
			document.getElementById("stpi_affSousItemImg").innerHTML = strImgTag;
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsSousItemToCommande()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affCartUrl()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		return;
			} 
			var strUrl = "commandeurlaff.php?sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("cart").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		function stpi_addSousItemToCommande(nnbSousItemID, nnbQte, nnbItemID)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_addSousItemToCommande" + nnbItemID).innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			document.getElementById("stpi_buttonAddSousItemToCommande" + nnbItemID).disabled = true;
			var strUrl = "commandesousitemadd.php?nbSousItemID=" + nnbSousItemID + "&nbQte=" + nnbQte + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_addSousItemToCommande" + nnbItemID).innerHTML = xmlHttp.responseText;
		  			document.getElementById("stpi_buttonAddSousItemToCommande" + nnbItemID).disabled = false;
		  			stpi_affCartUrl();
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}		
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affJsSousItemToRegistre()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addSousItemToRegistre(nnbSousItemID, nnbQte, nnbItemID)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_addSousItemToCommande" + nnbItemID).innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			document.getElementById("stpi_buttonAddSousItemToRegistre" + nnbItemID).disabled = true;
			var strUrl = "registresousitemaddpublic.php?nbSousItemID=" + nnbSousItemID + "&nbQte=" + nnbQte + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_addSousItemToCommande" + nnbItemID).innerHTML = xmlHttp.responseText;
		  			document.getElementById("stpi_buttonAddSousItemToRegistre" + nnbItemID).disabled = false;
		  			stpi_affCartUrl();
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}		
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsSousItemToCommandeRegistre()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affCartUrl()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		return;
			} 
			var strUrl = "commandeurlaff.php?sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("cart").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		function stpi_addSousItemToCommandeRegistre(nnbSousItemID, nnbQte)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_addSousItemToCommandeRegistre" + nnbSousItemID).innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			document.getElementById("stpi_buttonAddSousItemToCommandeRegistre" + nnbSousItemID).disabled = true;
			var strUrl = "commanderegistresousitemadd.php?nbSousItemID=" + nnbSousItemID + "&nbQte=" + nnbQte + "&nbRegistreID=" + document.getElementById("nbRegistreID").value + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_addSousItemToCommandeRegistre" + nnbSousItemID).innerHTML = xmlHttp.responseText;
		  			document.getElementById("stpi_buttonAddSousItemToCommandeRegistre" + nnbSousItemID).disabled = false;
		  			stpi_affCartUrl();
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}		
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsEditSousItemFromCommande()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_editSousItemFromCommande(narrSousItemID)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_message").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			var strParam = "sid=" + Math.random();
			for (i in narrSousItemID)
			{
				if (narrSousItemID[i] != 0)
				{
					strParam = strParam + "&nbSousItem" + narrSousItemID[i] + "=" + encodeURIComponent(document.getElementById("nbQuantity" + narrSousItemID[i]).value);
				}
			}
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./checkout1.php?l=<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_message").innerHTML = xmlHttp.responseText;
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
	
	
	public function stpi_affJsEditSousItemFromCommandeRegistre()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_editSousItemFromCommandeRegistre(narrSousItemID)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messageregistre").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			var strParam = "sid=" + Math.random();
			for (i in narrSousItemID)
			{
				if (narrSousItemID[i] != 0)
				{
					strParam = strParam + "&nbSousItem" + narrSousItemID[i] + "=" + encodeURIComponent(document.getElementById("nbQuantityRegistre" + narrSousItemID[i]).value);
				}
			}
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./checkout1.php?l=<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_messageregistre").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "commanderegistresousitemedit.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}	
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsDelSousItemFromCommande()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delSousItemFromCommande(nnbSousItemID)
		{
			if (nnbSousItemID == "")
			{
				return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_message").innerHTML = strErrXmlHttpObject;
		  		return;
			}
		
			var strUrl = "commandesousitemdel.php?nbSousItemID=" + nnbSousItemID + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./checkout1.php?l=<?php print(LG); ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_message").innerHTML = xmlHttp.responseText;
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
	
	
	public function stpi_affJsDelSousItemFromCommandeRegistre()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delSousItemFromCommandeRegistre(nnbSousItemID)
		{
			if (nnbSousItemID == "")
			{
				return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messageregistre").innerHTML = strErrXmlHttpObject;
		  		return;
			}
		
			var strUrl = "commanderegistresousitemdel.php?nbSousItemID=" + nnbSousItemID + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./checkout1.php?l=<?php print(LG); ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
		  				document.getElementById("stpi_messageregistre").innerHTML = xmlHttp.responseText;
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
	
	
	public function stpi_affJsPrixPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affSousItemPrix(nnbItemID, narrTypeAttributID)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affSousItemPrix" + nnbItemID).innerHTML = strErrXmlHttpObject;
		  		return;
			}
			var strParam = "nbItemID=" + encodeURIComponent(nnbItemID);
			
			var i;
			for (i in narrTypeAttributID)
  			{
  				if (document.getElementById("selNbSousItemID" + nnbItemID + "_" + narrTypeAttributID[i]).value == "")
  				{
  					document.getElementById("stpi_affSousItemPrix" + nnbItemID).innerHTML = "";
					stpi_affSousItemTagImg(nnbItemID, Array());
  					return;
  				}
  				
  				strParam = strParam + "&nbAttributID" + i + "=" + encodeURIComponent(document.getElementById("selNbSousItemID" + nnbItemID + "_" + narrTypeAttributID[i]).value);
  			}	
			strParam = strParam + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affSousItemPrix" + nnbItemID).innerHTML = xmlHttp.responseText;
		  			stpi_affSousItemTagImg(nnbItemID, narrTypeAttributID);
		  		}
			}
			
			xmlHttp.open("POST", "sousitemprixaff.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}		
		-->
		<?php
		print("</script>\n");
	}
		

	public function stpi_affPrixPublic($nboolShop = 0, $boolAddCart = 0, $nboolAddRegistre = 0)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (!$this->objPrix->stpi_setNbID($this->nbID, 1))
		{
			return false;
		}
		
		$nbPrix = $this->objPrix->stpi_getNbPrix();
		$nbRabaisPour = $this->objPrix->stpi_getNbRabaisPour();
		$nbRabaisStat = $this->objPrix->stpi_getNbRabaisStat();
		
		print($this->objBdd->stpi_trsBddToHtml($nbPrix) . " $ ");
		if ($nbRabaisPour != 0 || $nbRabaisStat != 0)
		{
			if ($nbRabaisPour != 0 && $nbRabaisStat != 0)
			{
				print("- " . $this->objBdd->stpi_trsBddToHtml($nbRabaisPour) . "%");
				print(" - " . $this->objBdd->stpi_trsBddToHtml($nbRabaisStat) . " $");
				$nbPrc = $nbPrix * $nbRabaisPour / 100;
				$nbPrixRabais = $nbPrix - $nbPrc;
				$nbPrixRabais = $nbPrixRabais - $nbRabaisStat;
				print(" = " . $this->objBdd->stpi_trsBddToHtml(number_format($nbPrixRabais, 2)) . " $");
			}
			elseif ($nbRabaisPour != 0)
			{
				print("- " . $this->objBdd->stpi_trsBddToHtml($nbRabaisPour) . "%");
				$nbPrc = $nbPrix * $nbRabaisPour / 100;
				$nbPrixRabais = $nbPrix - $nbPrc;
				print(" = " . $this->objBdd->stpi_trsBddToHtml(number_format($nbPrixRabais, 2)) . " $");
			}
			elseif ($nbRabaisStat != 0)
			{
				print("- " . $this->objBdd->stpi_trsBddToHtml($nbRabaisStat) . " $");
				$nbPrixRabais = $nbPrix - $nbRabaisStat;
				print(" = " . $this->objBdd->stpi_trsBddToHtml(number_format($nbPrixRabais, 2)) . " $");
			}
			if ($nboolShop)
			{
				print("<br/>\n");
			}
			print("<img width=\"75px\" height=\"50px\" alt=\"OnSale\" src=\"./images/onsale" . LG . ".jpg\" />");
		}
		
		print("<br/>\n");
		print("<input type=\"text\" maxlength=\"3\" size=\"2\" id=\"nbQuantity" . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . "\" value=\"1\" />\n");
		if ($nboolShop)
		{
			print("<br/>\n");
		}
		
		if ($boolAddCart)
		{
			print("<input id=\"stpi_buttonAddSousItemToCommande" . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . "\" onclick=\"stpi_addSousItemToCommande(" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . ", document.getElementById('nbQuantity" . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . "').value, " . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . ")\" type=\"button\" value=\"" . $this->objTexte->stpi_getArrTxt("buttoncart") . "\" />\n");
			print("<br/>\n");
		}
		
		if ($nboolAddRegistre)
		{
			print("<input id=\"stpi_buttonAddSousItemToRegistre" . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . "\" onclick=\"stpi_addSousItemToRegistre(" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . ", document.getElementById('nbQuantity" . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . "').value, " . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . ")\" type=\"button\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonregistre") . "\" />\n");
			print("<br/>\n");
		}
		
		print("<span id=\"stpi_addSousItemToCommande" . $this->objBdd->stpi_trsBddToHTML($this->nbItemID) . "\" ></span>\n");
	}
	
	
	public function stpi_affPrixRegistrePublic($nnbAvailable = 0)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (!$this->objPrix->stpi_setNbID($this->nbID, 2))
		{
			return false;
		}
		
		$nbPrix = $this->objPrix->stpi_getNbPrix();
		$nbRabaisPour = $this->objPrix->stpi_getNbRabaisPour();
		$nbRabaisStat = $this->objPrix->stpi_getNbRabaisStat();
		
		print($this->objBdd->stpi_trsBddToHtml($nbPrix) . " $ ");
		if ($nbRabaisPour != 0 || $nbRabaisStat != 0)
		{
			if ($nbRabaisPour != 0 && $nbRabaisStat != 0)
			{
				print("- " . $this->objBdd->stpi_trsBddToHtml($nbRabaisPour) . "%");
				print(" - " . $this->objBdd->stpi_trsBddToHtml($nbRabaisStat) . " $");
				$nbPrc = $nbPrix * $nbRabaisPour / 100;
				$nbPrixRabais = $nbPrix - $nbPrc;
				$nbPrixRabais = $nbPrixRabais - $nbRabaisStat;
				print(" = " . $this->objBdd->stpi_trsBddToHtml(number_format($nbPrixRabais, 2)) . " $");
			}
			elseif ($nbRabaisPour != 0)
			{
				print("- " . $this->objBdd->stpi_trsBddToHtml($nbRabaisPour) . "%");
				$nbPrc = $nbPrix * $nbRabaisPour / 100;
				$nbPrixRabais = $nbPrix - $nbPrc;
				print(" = " . $this->objBdd->stpi_trsBddToHtml(number_format($nbPrixRabais, 2)) . " $");
			}
			elseif ($nbRabaisStat != 0)
			{
				print("- " . $this->objBdd->stpi_trsBddToHtml($nbRabaisStat) . " $");
				$nbPrixRabais = $nbPrix - $nbRabaisStat;
				print(" = " . $this->objBdd->stpi_trsBddToHtml(number_format($nbPrixRabais, 2)) . " $");
			}
			print("<br/>\n");
			print("<img alt=\"OnSale\" src=\"./images/onsale" . LG . ".jpg\" />");
		}
		
		print("<br/>\n");
		print("<input type=\"text\" maxlength=\"3\" size=\"2\" id=\"nbQuantity" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" value=\"1\" />\n");
		if ($nnbAvailable != 0)
		{
			print(" / " . $this->objBdd->stpi_trsBddToHTML($nnbAvailable));
		}
		print("<br/>\n");
		print("<input id=\"stpi_buttonAddSousItemToCommandeRegistre" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" onclick=\"stpi_addSousItemToCommandeRegistre(" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . ", document.getElementById('nbQuantity" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "').value)\" type=\"button\" value=\"" . $this->objTexte->stpi_getArrTxt("buttoncart") . "\" />\n");
		print("<br/>\n");
		print("<span id=\"stpi_addSousItemToCommandeRegistre" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" ></span>\n");
	}
	
	
	public function stpi_affNewPublic($nboolShop = 0)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if ($this->stpi_chkIsNew())
		{
			print("<img alt=\"New\" src=\"./images/new" . LG . ".jpg\" />");
			print("<br/>\n");
		}
	}
	
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchSousItem(nstrName)
		{
			if (nstrName.length == 0)
			{ 
				document.getElementById("stpi_affSousItem").innerHTML = "";
		  		return;
		  	}
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affSousItem").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemsousitemsaff.php?strName=" + nstrName + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affSousItem").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("sousitem") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchSousItem(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affSousItem\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addSousItem(narrAttribut, narrPrix)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_SousItemAdd").innerHTML = strErrXmlHttpObject[stpi_chkLang()];
		  		return;
			}
			document.getElementById("stpi_AddSousItem").style.visibility = "hidden";
			var strParam = "nbItemID=" + encodeURIComponent(document.getElementById("nbItemID").value);
			strParam = strParam + "&strItemCode=" + encodeURIComponent(document.getElementById("strItemCode").value);
			strParam = strParam + "&nbUnits=" + encodeURIComponent(document.getElementById("nbUnits").value);
			strParam = strParam + "&nbQte=" + encodeURIComponent(document.getElementById("nbQte").value);
			strParam = strParam + "&nbQteMin=" + encodeURIComponent(document.getElementById("nbQteMin").value);
			if (document.getElementById("chkTaxable").checked)
			{
				strParam = strParam + "&boolTaxable=1";
			}
			else
			{
				strParam = strParam + "&boolTaxable=0";
			}
			for (i in narrPrix)
			{
				if (narrPrix[i] != 0)
				{
					strParam = strParam + "&nbPrix" + narrPrix[i] + "=" + encodeURIComponent(document.getElementById("nbPrix" + narrPrix[i]).value);
					strParam = strParam + "&nbRabaisPour" + narrPrix[i] + "=" + encodeURIComponent(document.getElementById("nbRabaisPour" + narrPrix[i]).value);
					strParam = strParam + "&nbRabaisStat" + narrPrix[i] + "=" + encodeURIComponent(document.getElementById("nbRabaisStat" + narrPrix[i]).value);
				}
			}
			for (i in narrAttribut)
			{
				if (narrAttribut[i] != 0)
				{
					strParam = strParam + "&nbAttributID" + narrAttribut[i] + "=" + encodeURIComponent(document.getElementById("nbAttributID" + narrAttribut[i]).value);
				}
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemsousitem.php?l=" + "<?php print(LG); ?>" + "&nbSousItemID=";
		  				var nbSousItemIDIndex = xmlHttp.responseText.indexOf("nbSousItemID") + 13;
		  				var nbSousItemIDLen = xmlHttp.responseText.length - nbSousItemIDIndex;
		  				var nbSousItemID = xmlHttp.responseText.substr(nbSousItemIDIndex, nbSousItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbSousItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddSousItem").style.visibility = "visible";
			  			document.getElementById("stpi_SousItemAdd").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemsousitemadd.php", true);
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
		print($this->objTexte->stpi_getArrTxt("codeitem") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strItemCode\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("units") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"0\" id=\"nbUnits\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("qte") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"0\" id=\"nbQte\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("qtemin") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"0\" id=\"nbQteMin\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<input id=\"chkTaxable\" type=\"checkbox\"/>" . $this->objTexte->stpi_getArrTxt("taxable"));
		print("</p>\n");
		
		$ajsTypePrix .= "";
		if ($arrNbTypePrixID = $this->objPrix->stpi_getObjTypePrix()->stpi_selAll())
		{
			foreach($arrNbTypePrixID as $nbTypePrixID)
			{
				$ajsTypePrix .= "," . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID);
				if ($this->objPrix->stpi_getObjTypePrix()->stpi_setNbID($nbTypePrixID))
				{
					if ($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_setNbTypePrixID($nbTypePrixID))
					{
						if ($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_setNbID($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_selNbTypePrixIDLG()))
						{		
							print("<br/>" . $this->objTexte->stpi_getArrTxt("prix") . " " . $this->objBdd->stpi_trsBddToHTML($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_getStrName()) . "\n");
							print("<table><tr>\n");
							print("<td>" . $this->objTexte->stpi_getArrTxt("prix") . "</td>\n");
							print("<td>" . $this->objTexte->stpi_getArrTxt("rabaispour") . "</td>\n");
							print("<td>" . $this->objTexte->stpi_getArrTxt("rabaisstat") . "</td></tr>\n");
							print("<tr><td><input type=\"text\" maxlength=\"12\" size=\"13\" value=\"0.00\" id=\"nbPrix" . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID) . "\"/></td>\n");
							print("<td><input type=\"text\" maxlength=\"12\" size=\"13\" value=\"0.00\" id=\"nbRabaisPour" . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID) . "\"/></td>\n");
							print("<td><input type=\"text\" maxlength=\"12\" size=\"13\" value=\"0.00\" id=\"nbRabaisStat" . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID) . "\"/></td>\n");
							print("</tr></table>\n");
						}
					}
				}
			}
		}
		
		$ajsTypeAttribut .= "";
		if ($arrNbTypeAttributID = $this->objAttribut->stpi_getObjTypeAttribut()->stpi_selAll())
		{
			foreach($arrNbTypeAttributID as $nbTypeAttributID)
			{
				$ajsTypeAttribut .= "," . $this->objBdd->stpi_trsBddToHTML($nbTypeAttributID);
				if ($this->objAttribut->stpi_getObjTypeAttribut()->stpi_setNbID($nbTypeAttributID))
				{
					if ($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_setNbTypeAttributID($nbTypeAttributID))
					{
						if ($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_setNbID($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_selNbTypeAttributIDLG()))
						{
							print("<p>\n");			
							print($this->objTexte->stpi_getArrTxt("attribut") . " " . $this->objBdd->stpi_trsBddToHTML($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_getStrName()) . "<br/>\n");
							print("<select id=\"nbAttributID" . $this->objBdd->stpi_trsBddToHTML($nbTypeAttributID) . "\">\n");
							print("<option value=\"\"></option>\n");
							if ($arrNbAttributID = $this->objAttribut->stpi_getObjTypeAttribut()->stpi_selNbAttributID())
							{
								foreach($arrNbAttributID as $nbAttributID)
								{
									if ($this->objAttribut->stpi_setNbID($nbAttributID))
									{
										if ($this->objAttribut->stpi_getObjAttributLg()->stpi_setNbAttributID($nbAttributID))
										{
											if ($this->objAttribut->stpi_getObjAttributLg()->stpi_setNbID($this->objAttribut->stpi_getObjAttributLg()->stpi_selNbAttributIDLG()))
											{
												print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbAttributID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objAttribut->stpi_getObjAttributLg()->stpi_getStrName()) . "</option>\n");
											}
										}
									}
								}
							}
							print("</select>\n");
							print("</p>\n");
						}
					}
				}
			}
		}
		print("<p>\n");
		print("<span id=\"stpi_SousItemAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddSousItem\" type=\"button\" onclick=\"stpi_addSousItem(Array(0" . $ajsTypeAttribut . "),Array(0" . $ajsTypePrix . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable(narrAttribut, narrPrix)
		{
			document.getElementById("strItemCode").disabled = false;
			document.getElementById("nbUnits").disabled = false;
			document.getElementById("nbQte").disabled = false;
			document.getElementById("nbQteMin").disabled = false;
			document.getElementById("chkTaxable").disabled = false;
			for (i in narrPrix)
			{
				if (narrPrix[i] != 0)
				{
					document.getElementById("nbPrix" + narrPrix[i]).disabled = false;
					document.getElementById("nbRabaisPour" + narrPrix[i]).disabled = false;
					document.getElementById("nbRabaisStat" + narrPrix[i]).disabled = false;
				}
			}
			for (i in narrAttribut)
			{
				if (narrPrix[i] != 0)
				{
					document.getElementById("nbAttributID" + narrAttribut[i]).disabled = false;
				}
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editSousItem(narrAttribut, narrPrix)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbSousItemID=" + encodeURIComponent(document.getElementById("nbSousItemID").value);
			strParam = strParam + "&strItemCode=" + encodeURIComponent(document.getElementById("strItemCode").value);
			strParam = strParam + "&nbUnits=" + encodeURIComponent(document.getElementById("nbUnits").value);
			strParam = strParam + "&nbQte=" + encodeURIComponent(document.getElementById("nbQte").value);
			strParam = strParam + "&nbQteMin=" + encodeURIComponent(document.getElementById("nbQteMin").value);
			if (document.getElementById("chkTaxable").checked)
			{
				strParam = strParam + "&boolTaxable=1";
			}
			else
			{
				strParam = strParam + "&boolTaxable=0";
			}
			for (i in narrPrix)
			{
				if (narrPrix[i] != 0)
				{
					strParam = strParam + "&nbPrix" + narrPrix[i] + "=" + encodeURIComponent(document.getElementById("nbPrix" + narrPrix[i]).value);
					strParam = strParam + "&nbRabaisPour" + narrPrix[i] + "=" + encodeURIComponent(document.getElementById("nbRabaisPour" + narrPrix[i]).value);
					strParam = strParam + "&nbRabaisStat" + narrPrix[i] + "=" + encodeURIComponent(document.getElementById("nbRabaisStat" + narrPrix[i]).value);
				}
			}
			for (i in narrAttribut)
			{
				if (narrAttribut[i] != 0)
				{
					strParam = strParam + "&nbAttributID" + narrAttribut[i] + "=" + encodeURIComponent(document.getElementById("nbAttributID" + narrAttribut[i]).value);
				}
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./itemsousitem.php?l=" + "<?php print(LG); ?>" + "&nbSousItemID=";
		  				var nbSousItemIDIndex = xmlHttp.responseText.indexOf("nbSousItemID") + 13;
		  				var nbSousItemIDLen = xmlHttp.responseText.length - nbSousItemIDIndex;
		  				var nbSousItemID = xmlHttp.responseText.substr(nbSousItemIDIndex, nbSousItemIDLen);
		  				strUrlRedirect = strUrlRedirect + nbSousItemID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			xmlHttp.open("POST", "itemsousitemedit.php", true);
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
		$SQL = "SELECT DISTINCT nbImageID FROM stpi_item_SousItem_ImgSousItem, stpi_item_SousItem";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID=" . $this->nbItemID;
		$SQL .= " AND nbNumImage=" . $this->nbNumImage;
		$SQL .= " AND stpi_item_SousItem.nbSousItemID=stpi_item_SousItem_ImgSousItem.nbSousItemID";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			print("<form method=\"post\" action=\"./itemsousitemimgedit.php?l=" . LG);
			print("&amp;nbSousItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "&amp;nbNumImage=" . $this->objBdd->stpi_trsBddToHTML($this->nbNumImage));
			print("&amp;op=useother\" enctype=\"multipart/form-data\">");
			print("<table>\n");
			while($row = mysql_fetch_assoc($result))
			{
				print("<tr>\n");
				print("<td>&nbsp;&nbsp;<input name=\"nbImageID\" type=\"radio\" value=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/>");
				print("<img alt=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\" src=\"./itemsousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/></td>\n");
				if ($row = mysql_fetch_assoc($result))
				{
					print("<td>&nbsp;&nbsp;<input name=\"nbImageID\" type=\"radio\" value=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/>");
					print("<img alt=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\" src=\"./itemsousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/></td>\n");
				}
				print("</tr>");
			}
			print("</table>\n");
			print("<p>");
			print("<input type=\"submit\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\"/><br/>");
			print("</p>\n");
			print("</form>\n");
			mysql_free_result($result);
		}
		
		print("<form method=\"post\" action=\"./itemsousitemimgadd.php?l=" . LG);
		print("&amp;nbSousItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "&amp;nbNumImage=" . $this->objBdd->stpi_trsBddToHTML($this->nbNumImage));
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
		$SQL = "SELECT DISTINCT nbImageID FROM stpi_item_SousItem_ImgSousItem, stpi_item_SousItem";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID=" . $this->nbItemID;
		$SQL .= " AND nbNumImage=" . $this->nbNumImage;
		$SQL .= " AND stpi_item_SousItem.nbSousItemID=stpi_item_SousItem_ImgSousItem.nbSousItemID";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			print("<form method=\"post\" action=\"./itemsousitemimgadd.php?l=" . LG);
			print("&amp;nbSousItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "&amp;nbNumImage=" . $this->objBdd->stpi_trsBddToHTML($this->nbNumImage));
			print("&amp;op=useother\" enctype=\"multipart/form-data\">");
			print("<table>\n");
			while($row = mysql_fetch_assoc($result))
			{
				print("<tr>\n");
				print("<td>&nbsp;&nbsp;<input name=\"nbImageID\" type=\"radio\" value=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/>");
				print("<img alt=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\" src=\"./itemsousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/></td>\n");
				if ($row = mysql_fetch_assoc($result))
				{
					print("<td>&nbsp;&nbsp;<input name=\"nbImageID\" type=\"radio\" value=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/>");
					print("<img alt=\"" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\" src=\"./itemsousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $this->objBdd->stpi_trsBddToHTML($row["nbImageID"]) . "\"/></td>\n");
				}
				print("</tr>");
			}
			print("</table>\n");
			print("<p>");
			print("<input type=\"submit\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\"/><br/>");
			print("</p>\n");
			print("</form>\n");
			mysql_free_result($result);
		}
		
		print("<form method=\"post\" action=\"./itemsousitemimgadd.php?l=" . LG);
		print("&amp;nbSousItemID=" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "&amp;nbNumImage=" . $this->objBdd->stpi_trsBddToHTML($this->nbNumImage));
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
				print("<img alt=\"" . $arrNbImageID[$nbNumImage] . "\" src=\"./itemsousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $arrNbImageID[$nbNumImage] . "\"/><br/>\n");
				print("<a href=\"./itemsousitemimgedit.php?l=" . LG . "&amp;nbNumImage=" . $nbNumImage . "&amp;nbSousItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("editimg") . " " . $nbNumImage . "</a><br/>\n");
			}
			else
			{
				print("<a href=\"./itemsousitemimgadd.php?l=" . LG . "&amp;nbNumImage=" . $nbNumImage . "&amp;nbSousItemID=" . $this->nbID . "\">" . $this->objTexte->stpi_getArrTxt("addimg") . " " . $nbNumImage . "</a><br/>\n");
			}
		}
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("codeitem") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strItemCode) . "\" id=\"strItemCode\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("units") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbUnits) . "\" id=\"nbUnits\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("qte") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbQte) . "\" id=\"nbQte\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("qtemin") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbQteMin) . "\" id=\"nbQteMin\"/><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<input disabled=\"disabled\" id=\"chkTaxable\" type=\"checkbox\"");
		if ($this->boolTaxable == 1)
		{
			print(" checked=\"checked\"");
		}
		print("/>&nbsp;" . $this->objTexte->stpi_getArrTxt("taxable"));
		print("</p>\n");
		
		$ajsTypePrix .= "";
		if ($arrNbTypePrixID = $this->objPrix->stpi_getObjTypePrix()->stpi_selAll())
		{
			foreach($arrNbTypePrixID as $nbTypePrixID)
			{
				$ajsTypePrix .= "," . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID);
				if ($this->objPrix->stpi_setNbID($this->nbID, $nbTypePrixID))
				{
					if ($this->objPrix->stpi_getObjTypePrix()->stpi_setNbID($nbTypePrixID))
					{
						if ($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_setNbTypePrixID($nbTypePrixID))
						{
							if ($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_setNbID($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_selNbTypePrixIDLG()))
							{		
								print("<br/>" . $this->objTexte->stpi_getArrTxt("prix") . " " . $this->objBdd->stpi_trsBddToHTML($this->objPrix->stpi_getObjTypePrix()->stpi_getObjTypePrixLg()->stpi_getStrName()) . "\n");
								print("<table><tr>\n");
								print("<td>" . $this->objTexte->stpi_getArrTxt("prix") . "</td>\n");
								print("<td>" . $this->objTexte->stpi_getArrTxt("rabaispour") . "</td>\n");
								print("<td>" . $this->objTexte->stpi_getArrTxt("rabaisstat") . "</td></tr>\n");
								print("<tr><td><input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objPrix->stpi_getNbPrix()) . "\" id=\"nbPrix" . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID) . "\"/></td>\n");
								print("<td><input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objPrix->stpi_getNbRabaisPour()) . "\" id=\"nbRabaisPour" . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID) . "\"/></td>\n");
								print("<td><input disabled=\"disabled\" type=\"text\" maxlength=\"12\" size=\"13\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objPrix->stpi_getNbRabaisStat()) . "\" id=\"nbRabaisStat" . $this->objBdd->stpi_trsBddToHTML($nbTypePrixID) . "\"/></td>\n");
								print("</tr></table>\n");
							}
						}
					}
				}
			}
		}

		$ajsTypeAttribut .= "";
		if ($arrNbTypeAttributID = $this->objAttribut->stpi_getObjTypeAttribut()->stpi_selAll())
		{
			if ($temp = $this->stpi_selNbAttributID())
			{
				$this->arrNbAttributID = $temp;
			}
			
			foreach($arrNbTypeAttributID as $nbTypeAttributID)
			{
				$ajsTypeAttribut .= "," . $this->objBdd->stpi_trsBddToHTML($nbTypeAttributID);
				if ($this->objAttribut->stpi_getObjTypeAttribut()->stpi_setNbID($nbTypeAttributID))
				{
					if ($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_setNbTypeAttributID($nbTypeAttributID))
					{
						if ($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_setNbID($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_selNbTypeAttributIDLG()))
						{
							print("<p>\n");			
							print($this->objTexte->stpi_getArrTxt("attribut") . " " . $this->objBdd->stpi_trsBddToHTML($this->objAttribut->stpi_getObjTypeAttribut()->stpi_getObjTypeAttributLg()->stpi_getStrName()) . "<br/>\n");
							print("<select disabled=\"disabled\" id=\"nbAttributID" . $this->objBdd->stpi_trsBddToHTML($nbTypeAttributID) . "\">\n");
							print("<option value=\"\"></option>\n");
							if ($arrNbAttributID = $this->objAttribut->stpi_getObjTypeAttribut()->stpi_selNbAttributID())
							{
								foreach($arrNbAttributID as $nbAttributID)
								{
									if ($this->objAttribut->stpi_setNbID($nbAttributID))
									{
										if ($this->objAttribut->stpi_getObjAttributLg()->stpi_setNbAttributID($nbAttributID))
										{
											if ($this->objAttribut->stpi_getObjAttributLg()->stpi_setNbID($this->objAttribut->stpi_getObjAttributLg()->stpi_selNbAttributIDLG()))
											{
												print("<option");
												if (in_array($nbAttributID, $this->arrNbAttributID))
												{
													print(" selected=\"selected\"");
												}
												print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbAttributID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objAttribut->stpi_getObjAttributLg()->stpi_getStrName()) . "</option>\n");
											}
										}
									}
								}
							}
							print("</select>\n");
							print("</p>\n");
						}
					}
				}
			}
		}
		
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable(Array(0" . $ajsTypeAttribut . "),Array(0" . $ajsTypePrix . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editSousItem(Array(0" . $ajsTypeAttribut . "),Array(0" . $ajsTypePrix . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delSousItem()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delSousItem()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemsousitemdel.php?nbSousItemID=" + document.getElementById("nbSousItemID").value;
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
		function stpi_delSousItemConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "itemsousitemdel.php?nbSousItemID=" + document.getElementById("nbSousItemID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
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
		print("<input type=\"button\" onclick=\"stpi_delSousItemConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT nbSousItemID FROM stpi_item_SousItem";
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
	
	public function stpi_selNbAttributID()
	{
		$arrID = array();
		$SQL = "SELECT stpi_item_Attribut.nbAttributID FROM stpi_item_SousItem_Attribut, stpi_item_Attribut";
		$SQL .= " WHERE nbSousItemID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_item_SousItem_Attribut.nbAttributID=stpi_item_Attribut.nbAttributID";
		$SQL .= " ORDER BY nbTypeAttributID";
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
	
	public function stpi_selNbImageID()
	{
		$arrID = array();
		$SQL = "SELECT nbImageID, nbNumImage FROM stpi_item_SousItem_ImgSousItem WHERE nbSousItemID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
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