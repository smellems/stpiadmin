<?php
	require_once(dirname(__FILE__) . "/clstypepagelg.php");
	
	class clstypepage
	{
		private $objBdd;
		private $objTexte;
		private $objTypePageLg;
		
		private $nbID;
		
		private $arrObjTypePageLg;
		
		public function __construct($nnbID = 0)
		{
			$this->objBdd = clsbdd::singleton();
			$this->objTexte = new clstexte(dirname(__FILE__) . "/txttypepage");
			$this->objTypePageLg = new clstypepagelg();
			if ($nnbID == 0)
			{
				$this->nbID = 0;
				$this->arrObjTypePageLg = array();
			}
			else
			{
				if(!$this->stpi_setnbID($nnbID))
				{
					return false;
				}
				$this->arrTypePageLg = array();
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
			if (!$this->objBdd->stpi_chkExists($nnbID, "nbTypePageID", "stpi_niv_TypePage"))
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
			
			return true;
		}
		
		public function stpi_setArrObjTypePageLgFromBdd()
		{
			if (!$this->objTypePageLg->stpi_setNbTypePageID($this->nbID))
			{
				return false;
			}
			if (!$arrNbTypePageID = $this->objTypePageLg->stpi_selNbTypePageID())
			{
				return false;
			}
			foreach ($arrNbTypePageID as $strLg => $nbTypePageLgID)
			{
				if (!$this->arrObjSectionLg[$strLg] = new clsSectionlg($nbTypePageLgID))
				{
					return false;
				}
			}
			return true;
		}
		
		public function stpi_setObjTypePageLgFromBdd()
		{
			if (!$this->objTypePageLg->stpi_setNbTypePageID($this->nbID))
			{
				return false;
			}
			if (!$nbTypePageLgId = $this->objTypePageLg->stpi_selNbTypePageIDLg())
			{
				return false;
			}
			if (!$this->objTypePageLg->stpi_setNbID($nbTypePageLgId))
			{
				return false;
			}
			return true;
		}
		
		public function stpi_getNbID()
		{
			return $this->nbID;
		}
		
			
		public function stpi_getObjTypePageLg()
		{
			return $this->objTypePageLg;
		}
		
		public function stpi_getArrObjTypePageLg()
		{
			return $this->arrObjTypePageLg;
		}
	}
?>