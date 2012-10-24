<?php
	require_once(dirname(__FILE__) . "/clstypepage.php");
	
	class clspage
	{
		private $objBdd;
		private $objTexte;
		private $objTypePage;
		
		private $nbID;
		private $nbTypePageID;
		private $strName;
		private $boolCrypted;
		
		public function __construct($nnbID = 0)
		{
			$this->objBdd = clsbdd::singleton();
			$this->objTexte = new clstexte(dirname(__FILE__) . "/txtpage");
			$this->objTypePage = new clstypepage();
			if ($nnbID == 0)
			{
				$this->nbID = 0;
				$this->nbTypePageID = 0;
				$this->strName = "";
				$this->boolCrypted = 0;
			}
			else
			{
				if(!$this->stpi_setnbID($nnbID))
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
			if (!$this->objBdd->stpi_chkExists($nnbID, "nbPageID", "stpi_niv_Page"))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
				return false;
			}
			return true;
		}
		
		
		public function stpi_chkNbTypePageID($nnbTypePageID)
		{
			if (!$this->objTypePage->stpi_chkNbID($nnbTypePageID))
			{
				return false;				
			}
			return true;
		}
		
		
		public function stpi_chkStrName($nstrName)
		{
			if (!$this->objBdd->stpi_chkInputToBdd($nstrName))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidname") . "</span><br/>\n");
				return false;				
			}
			return true;
		}
		
		
		public function stpi_chkboolCrypted($nboolCrypted)
		{
			if ($nboolCrypted != 0 && $nboolCrypted != 1)
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidencrypt") . "</span><br/>\n");
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
			
			$SQL = "SELECT nbTypePageID, strName, boolCrypted";
			$SQL .= " FROM stpi_niv_Page";
			$SQL .= " WHERE nbPageID = " . $this->nbID;
			
			if ($result = $this->objBdd->stpi_select($SQL))
			{
				if($row = mysql_fetch_assoc($result))
				{
					$this->nbTypePageID = $row["nbTypePageID"];
					$this->strName = $row["strName"];
					$this->boolCrypted = $row["boolCrypted"];
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
		
		
		public function stpi_setNbTypePageID($nnbTypePageID)
		{
			if (!$this->stpi_chkTypePageID($nnbTypePageID))
			{
				return false;
			}
			
			$this->nbTypePageID = $nnbTypePageID;
			
			return true;
		}
				
		
		public function stpi_setObjTypePageFromBdd()
		{
			if ($this->nbTypePageID == 0)
			{
				return false;
			}
			if (!$this->objTypePage->stpi_setNbID($this->nbTypePageID))
			{
				return false;
			}
			return true;
		}
		
		
		public function stpi_setStrName($nbstrName)
		{
			if (!$this->stpi_chkStrName($nbstrName))
			{
				return false;
			}
			
			$this->strName = $nbstrName;
			
			return true;
		}
					
		public function stpi_getNbID()
		{
			return $this->nbID;
		}
		
		
		public function stpi_getNbTypePageID()
		{
			return $this->nbTypePageID;
		}
		
		
		public function stpi_getObjTypePage()
		{
			return $this->objTypePage;
		}
		
		
		public function stpi_getStrName()
		{
			return $this->strName;
		}
		
		
		public function stpi_getBoolCrypted()
		{
			return $this->boolCrypted;
		}
	}
?>