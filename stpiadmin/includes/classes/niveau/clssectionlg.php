<?php
	class clssectionlg
	{
		private $objBdd;
		private $objTexte;
		private $objLang;
		
		private $nbID;
		private $nbSectionID;
		private $strName;
		private $strLg;
		
		public function __construct($nnbID = 0)
		{
			$this->objBdd = clsbdd::singleton();
			$this->objLang = new clslang();
			$this->objTexte = new clstexte(dirname(__FILE__) . "/txtsection");
			if ($nnbID == 0)
			{
				$this->nbID = 0;
				$this->nbSectionID = 0;
				$this->strName = "";
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
			if (!$this->objBdd->stpi_chkExists($nnbID, "nbSectionLgID", "stpi_niv_Section_Lg"))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "&nbsp;(lg)</span><br/>\n");
				return false;
			}
			return true;
		}
		
		
		public function stpi_chkNbSectionID($nnbSectionID)
		{
			if (!$this->objBdd->stpi_chkInputToBdd($nnbSectionID))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
				return false;				
			}
			if (!$this->objBdd->stpi_chkExists($nnbSectionID, "nbSectionID", "stpi_niv_Section"))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
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
			
			$SQL = "SELECT nbSectionID, strName, strLg";
			$SQL .= " FROM stpi_niv_Section_Lg";
			$SQL .= " WHERE nbSectionLgID = " . $this->objBdd->stpi_trsInputToBdd($this->nbID);
			
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$this->nbSectionID = $row["nbSectionID"];
					$this->strName = $row["strName"];
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
		
		
		public function stpi_setNbSectionID($nnbSectionID)
		{
			if (!$this->stpi_chkNbSectionID($nnbSectionID))
			{
				return false;				
			}
			$this->nbSectionID = $nnbSectionID;
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
		
		
		public function stpi_getNbSectionID()
		{
			return $this->nbSectionID;
		}
		
		
		public function stpi_getStrName()
		{
			return $this->strName;
		}
		
				
		public function stpi_getStrLg()
		{
			return $this->strLg;
		}
		
		
		public function stpi_selNbSectionID()
		{
			if ($this->nbSectionID == 0)
			{
				return false;
			}
			
			$this->objLang->stpi_setArrLang();
			$arrLang = $this->objLang->stpi_getArrLang();
			$arrID = array();
			
			foreach ($arrLang as $strLg)	
			{
				$SQL = "SELECT nbSectionLgID";
				$SQL .= " FROM stpi_niv_Section_Lg";
				$SQL .= " WHERE nbSectionID = '" . $this->nbSectionID . "'";
				$SQL .= " AND strLg = '" . $strLg . "'";
				
				if ($result = $this->objBdd->stpi_select($SQL))
				{
					if ($row = mysql_fetch_assoc($result))
					{
						$arrID[$strLg] = $row["nbSectionLgID"];
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
		
		public function stpi_selNbSectionIDLg()
		{
			if ($this->nbSectionID == 0)
			{
				return false;
			}
			
			$SQL = "SELECT nbSectionLgID";
			$SQL .= " FROM stpi_niv_Section_Lg";
			$SQL .= " WHERE nbSectionID = '" . $this->nbSectionID . "'";
			$SQL .= " AND strLg = '" . LG . "'";
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if ($row = mysql_fetch_assoc($result))
				{
					$nbID = $row["nbSectionLgID"];
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
	}
?>