<?php
require_once(dirname(__FILE__) . "/../img/clsimg.php");

class clsbannierelg
{
	private $nbImgWidthMax = 300;
	private $nbImgHeightMax = 100;
	
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objImg;
	
	private $nbID;
	private $nbBanniereID;
	private $nbImageID;
	
	private $strName;
	private $strLien;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtbanniere");
		$this->objImg = new clsimg("stpi_banniere_ImgBanniere");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbBanniereID = 0;
			$this->strName = "";
			$this->strLien = "";
			$this->strLg = "";
			$this->nbImageID = 0;
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
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbBanniereLgID", "stpi_banniere_Banniere_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbBanniereID($nnbBanniereID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbBanniereID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg-nbBanniereID)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbBanniereID, "nbBanniereID", "stpi_banniere_Banniere"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-nbBanniereID)</span><br/>\n");
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
	
	
	public function stpi_chkStrName($nstrName)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrName))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidname") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
			return false;				
		}
		return true;
	}
	
		
	public function stpi_chkStrLien($nstrLien)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrLien))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlien") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	public function stpi_chkStrLg($nstrLg)
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		if (!$this->objBdd->stpi_chkInputToBdd($nstrLg))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
			return false;
		}
		if (!in_array($nstrLg, $arrLang))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
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
		
		$SQL = "SELECT nbBanniereID, strName, strLien, strLg, nbImageID FROM stpi_banniere_Banniere_Lg WHERE nbBanniereLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbBanniereID = $row["nbBanniereID"];
				$this->strName = $row["strName"];
				$this->strLien = $row["strLien"];
				$this->strLg = $row["strLg"];
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
	
	
	public function stpi_setNbBanniereID($nnbBanniereID)
	{
		if (!$this->stpi_chkNbBanniereID($nnbBanniereID))
		{
			return false;				
		}
		$this->nbBanniereID = $nnbBanniereID;
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
	
	
	public function stpi_setStrName($nstrName)
	{
		if (!$this->stpi_chkStrName($nstrName))
		{
			return false;
		}
		$this->strName = $nstrName;
		return true;
	}
	
	
	public function stpi_setStrLien($nstrLien)
	{
		if (!$this->stpi_chkStrLien($nstrLien))
		{
			return false;
		}
		$this->strLien = $nstrLien;
		return true;
	}
	
	
	public function stpi_setStrLg($nstrLg)
	{
		if (!$this->stpi_chkStrLg($nstrLg))
		{
			return false;				
		}
		$this->strLg = $nstrLg;
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
	
	
	public function stpi_getStrName()
	{
		return $this->strName;
	}
	
	
	public function stpi_getStrLien()
	{
		return $this->strLien;
	}
	
	
	public function stpi_getStrLg()
	{
		return $this->strLg;
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
	
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_banniere_Banniere_Lg (nbBanniereID, strName, strLien, strLg, nbImageID)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbBanniereID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strLien) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->nbImageID) . "')";

		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			return $this->nbID;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "&nbsp;(lg-" . $this->strLg . ")</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_update()
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(lg-" . $this->strLg . ")</span><br/>\n");
			return false;
		}
		
		$SQL = "UPDATE stpi_banniere_Banniere_Lg";
		$SQL .= " SET nbBanniereID='" . $this->objBdd->stpi_trsInputToBdd($this->nbBanniereID) . "',";
		$SQL .= " strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " strName='" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "',";
		$SQL .= " strLien='" . $this->objBdd->stpi_trsInputToBdd($this->strLien) . "',";
		$SQL .= " nbImageID='" . $this->objBdd->stpi_trsInputToBdd($this->nbImageID) . "'";
		$SQL .= " WHERE nbBanniereLgID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if ($this->objBdd->stpi_update($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "&nbsp;(lg-" . $this->strLg . ")</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_deleteBanniereId($nnbBanniereID)
	{
		if (!$this->stpi_chkNbBanniereID($nnbBanniereID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_banniere_Banniere_Lg WHERE nbBanniereID='" . $this->objBdd->stpi_trsInputToBdd($nnbBanniereID) . "'";
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
	}
	
	
	public function stpi_affImgAdd()
	{
		print("<form method=\"post\" action=\"./banniereimgadd.php?l=" . LG);
		print("&amp;nbBanniereID=" . $this->objBdd->stpi_trsBddToHTML($this->nbBanniereID));
		print("&amp;strLg=" . $this->objBdd->stpi_trsBddToHTML($this->strLg));
		print("&amp;op=save\" enctype=\"multipart/form-data\">");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("path") . " - " . $this->objBdd->stpi_trsBddToHTML($this->strLg) . "<br/>\n");	
		print("<input type=\"file\" name=\"blobImage\" id=\"blobImage\"/><br/>\n");
		print("</p>\n");
		print("<p>\n");
		print("<input type=\"submit\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\"/><br/>\n");
		print("</p>\n");
		
		print("</form>\n");
	}
	
	
	public function stpi_affImgEdit()
	{
		print("<form method=\"post\" action=\"./banniereimgedit.php?l=" . LG);
		print("&amp;nbBanniereID=" . $this->objBdd->stpi_trsBddToHTML($this->nbBanniereID));
		print("&amp;strLg=" . $this->objBdd->stpi_trsBddToHTML($this->strLg));
		print("&amp;op=save\" enctype=\"multipart/form-data\">");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("path") . " - " . $this->objBdd->stpi_trsBddToHTML($this->strLg) . "<br/>\n");	
		print("<input type=\"file\" name=\"blobImage\" id=\"blobImage\"/><br/>\n");
		print("</p>\n");
		print("<p>\n");
		print("<input type=\"submit\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\"/><br/>\n");
		print("</p>\n");
		
		print("</form>\n");
	}
	
	
	public function stpi_selNbBanniereID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		foreach ($arrLang as $strLg)
		{
			$SQL = "SELECT nbBanniereLgID";
			$SQL .= " FROM stpi_banniere_Banniere_Lg";
			$SQL .= " WHERE nbBanniereID='" . $this->nbBanniereID . "'";
			$SQL .= " AND strLg = '" . $strLg . "'";
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbBanniereLgID"];
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
		}
		return $arrID;
	}
	
	public function stpi_selNbBanniereIDLG()
	{
		$SQL = "SELECT nbBanniereLgID";
		$SQL .= " FROM stpi_banniere_Banniere_Lg";
		$SQL .= " WHERE nbBanniereID='" . $this->nbBanniereID . "'";
		$SQL .= " AND strLg = '" . LG . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbID = $row["nbBanniereLgID"];
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
		return $nbID;
	}
	
	public function stpi_selSearchName($nstrName)
	{
		if (!$this->stpi_chkStrName($nstrName))
		{
			return false;
		}
		$SQL = "SELECT DISTINCT nbBanniereID";
		$SQL .= " FROM stpi_banniere_Banniere_Lg";
		$SQL .= " WHERE  strName LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrName) . "%'";
		$SQL .= " ORDER BY strName LIMIT 0,10";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbBanniereID"];
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