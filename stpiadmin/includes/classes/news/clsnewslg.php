<?php
class clsnewslg
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	
	private $nbID;
	private $nbNewsID;
	private $strTitre;
	private $strNews;
	private $strLg;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtnews");
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbNewsID = 0;
			$this->strTitre = "";
			$this->strNews = "";
			$this->strLg = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbNewsLgID", "stpi_news_News_Lg"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbNewsID($nnbNewsID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbNewsID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(lg)</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbNewsID, "nbNewsID", "stpi_news_News"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkStrTitre($nstrTitre)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrTitre))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtitre") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	public function stpi_chkStrNews($nstrNews)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrNews))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtext") . "&nbsp;(" . $this->strLg . ")</span><br/>\n");
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
		
		$SQL = "SELECT nbNewsID, strTitre, strNews, strLg FROM stpi_news_News_Lg WHERE nbNewsLgID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$this->nbNewsID = $row["nbNewsID"];
				$this->strTitre = $row["strTitre"];
				$this->strNews = $row["strNews"];
				$this->strLg = $row["strLg"];
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
	
	
	public function stpi_setNbNewsID($nnbNewsID)
	{
		if (!$this->stpi_chkNbNewsID($nnbNewsID))
		{
			return false;				
		}
		$this->nbNewsID = $nnbNewsID;
		return true;
	}
	
	
	public function stpi_setStrTitre($nstrTitre)
	{
		if (!$this->stpi_chkStrTitre($nstrTitre))
		{
			return false;
		}
		$this->strTitre = $nstrTitre;
		return true;
	}
	
	
	public function stpi_setStrNews($nstrNews)
	{
		if (!$this->stpi_chkStrNews($nstrNews))
		{
			return false;
		}
		$this->strNews = $nstrNews;
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
	
	
	public function stpi_getNbNewsID()
	{
		return $this->nbNewsID;
	}
	
	public function stpi_getStrTitre()
	{
		return $this->strTitre;
	}
	
	public function stpi_getStrNews()
	{
		return $this->strNews;
	}
	
	
	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO stpi_news_News_Lg (nbNewsID, strTitre, strNews, strLg)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbNewsID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strTitre) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strNews) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "')";

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
		$SQL = "UPDATE stpi_news_News_Lg";
		$SQL .= " SET strTitre='" . $this->objBdd->stpi_trsInputToBdd($this->strTitre) . "',";
		$SQL .= " strNews='" . $this->objBdd->stpi_trsInputToBdd($this->strNews) . "',";
		$SQL .= " strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLg) . "',";
		$SQL .= " nbNewsID='" . $this->objBdd->stpi_trsInputToBdd($this->nbNewsID) . "'";
		$SQL .= " WHERE nbNewsLgID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_deleteNewsId($nnbNewsID)
	{
		if (!$this->stpi_chkNbNewsID($nnbNewsID))
		{
			return false;
		}
		$SQL = "DELETE FROM stpi_news_News_Lg WHERE nbNewsID='" . $this->objBdd->stpi_trsInputToBdd($nnbNewsID) . "'";
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
	
	public function stpi_selSearchTitre($nstrTitre)
	{
		if (!$this->stpi_chkStrTitre($nstrTitre))
		{
			return false;
		}
		$SQL = "SELECT DISTINCT nbNewsID";
		$SQL .= " FROM stpi_news_News_Lg";
		$SQL .= " WHERE  strTitre LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrTitre) . "%'";
		$SQL .= " ORDER BY strTitre LIMIT 0,20";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbNewsID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
	
	
	public function stpi_selNbNewsID()
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		$arrID = array();
		foreach ($arrLang as $strLg)	
		{
			$SQL = "SELECT nbNewsLgID";
			$SQL .= " FROM stpi_news_News_Lg";
			$SQL .= " WHERE nbNewsID='" . $this->nbNewsID . "'";
			$SQL .= " AND strLg = '" . $strLg . "'";
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$arrID[$strLg] = $row["nbNewsLgID"];
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . $strLg . ")</span><br/>\n");
					return false;
				}
				mysql_free_result($result);
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg-" . $strLg . ")</span><br/>\n");
				return false;
			}
		}
		return $arrID;
	}
}
?>