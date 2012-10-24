<?php
	require_once(dirname(__FILE__) . "/clssectionlg.php");
	require_once(dirname(__FILE__) . "/clspage.php");
	
	class clssection
	{
		private $objBdd;
		private $objTexte;
		private $objSectionLg;
		private $objPage;
		
		private $nbID;
		private $boolActive;
		private $strMainPage;
		
		private $arrObjSectionLg;
		private $arrNbPageID;
		
		public function __construct($nnbID = 0)
		{
			$this->objBdd = clsbdd::singleton();
			$this->objTexte = new clstexte(dirname(__FILE__) . "/txtsection");
			$this->objSectionLg = new clssectionlg();
			$this->objPage = new clspage();
			if ($nnbID == 0)
			{
				$this->nbID = 0;
				$this->boolActive = 0;
				$this->strMainPage = "";
				$this->arrObjSectionLg = array();
			}
			else
			{
				if(!$this->stpi_setnbID($nnbID))
				{
					return false;
				}
				$this->arrObjSectionLg = array();
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
			if (!$this->objBdd->stpi_chkExists($nnbID, "nbSectionID", "stpi_niv_Section"))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
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
			
			$SQL = "SELECT boolActive, strMainPage FROM stpi_niv_Section WHERE nbSectionID = " . $this->nbID;
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if($row = mysql_fetch_assoc($result))
				{
					$this->boolActive = $row["boolActive"];
					$this->strMainPage = $row["strMainPage"];
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
		
		
		public function stpi_setArrObjSectionLgFromBdd()
		{
			if (!$this->objSectionLg->stpi_setNbSectionID($this->nbID))
			{
				return false;
			}
			if (!$arrNbSectionID = $this->objSectionLg->stpi_selNbSectionID())
			{
				return false;
			}
			foreach ($arrNbSectionID as $strLg => $nbSectionLgID)
			{
				if (!$this->arrObjSectionLg[$strLg] = new clsSectionlg($nbSectionLgID))
				{
					return false;
				}
			}
			return true;
		}
		
		
		public function stpi_setObjSectionLgFromBdd()
		{
			if (!$this->objSectionLg->stpi_setNbSectionID($this->nbID))
			{
				return false;
			}
			if (!$nbSectionLgId = $this->objSectionLg->stpi_selNbSectionIDLg())
			{
				return false;
			}
			if (!$this->objSectionLg->stpi_setNbID($nbSectionLgId))
			{
				return false;
			}
			return true;
		}
		
		
		public function stpi_getNbID()
		{
			return $this->nbID;
		}
		
		
		public function stpi_getBoolActive()
		{
			return $this->boolActive;
		}
		
		
		public function stpi_getStrMainPage()
		{
			return $this->strMainPage;
		}
		
		
		public function stpi_getObjSectionLg()
		{
			return $this->objSectionLg;
		}
		
		
		public function stpi_getArrObjSectionLg()
		{
			return $this->arrObjSectionLg;
		}
		
		
		public function stpi_getArrNbPageID()
		{
			return $this->arrNbPageID;
		}
		
		
		public function stpi_selAll()
		{
			$SQL = "SELECT nbSectionID";
			$SQL .= " FROM stpi_niv_Section";
			$SQL .= " ORDER BY nbSectionID";
			
			$arrID = array();
		
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				while ($row = mysql_fetch_assoc($result))
				{
					$arrID[] = $row["nbSectionID"];
				}
				mysql_free_result($result);
			}
			else
			{
				return false;
			}
			return $arrID;
		}
		
		
		public function stpi_selNbPageID()
		{
			if ($this->nbID == 0)
			{
				return false;
			}
			
			$arrID = array();
			
			$SQL = "SELECT nbPageID";
			$SQL .= " FROM stpi_niv_Section_Page";
			$SQL .= " WHERE nbSectionID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
			$SQL .= " ORDER BY strName";
			
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				while($row = mysql_fetch_assoc($result))
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
		
		
		public function stpi_selNbTypePageID()
		{
			if ($this->nbID == 0)
			{
				return false;
			}
			
			$arrID = array();
			
			$SQL = "SELECT stpi_niv_Page.nbTypePageID";
			$SQL .= " FROM stpi_niv_Section_Page, stpi_niv_Page, stpi_niv_TypePage_Lg";
			$SQL .= " WHERE stpi_niv_Section_Page.nbSectionID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
			$SQL .= " AND stpi_niv_Section_Page.nbPageID = stpi_niv_Page.nbPageID";
			$SQL .= " AND stpi_niv_Page.nbTypePageID = stpi_niv_TypePage_Lg.nbTypePageID";
			$SQL .= " AND stpi_niv_TypePage_Lg.strLg = '" . LG . "'";
			$SQL .= " ORDER BY stpi_niv_TypePage_Lg.strName";
			
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				while($row = mysql_fetch_assoc($result))
				{
					$arrID[] = $row["nbTypePageID"];
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