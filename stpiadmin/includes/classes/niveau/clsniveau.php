<?php

require_once(dirname(__FILE__) . "/clssection.php");
require_once(dirname(__FILE__) . "/clstypepage.php");

class clsniveau
{
	private $objBdd;
	private $objTexte;
	private $objSection;
	private $objTypePage;
	
	private $nbID;
	private $strName;
	private $boolDelete;
	
	private $arrNbSectionID;
	private $arrNbTypePageID;
	
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtniveau");
		$this->objSection = new clssection();
		$this->objTypePage = new clstypepage();
		$this->arrNbSectionID = array();
		$this->arrNbTypePageID = array();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->strName = "";
			$this->boolDelete = 1;
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbNiveauID", "stpi_niv_Niveau"))
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
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidname") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	public function stpi_chkBoolDelete($nboolDelete)
	{
		if ($nboolDelete != 0 && $nboolDelete != 0)
		{
			return false;				
		}
		return true;
	}
	
	
	//Vérifier si les niveaux ont tous les nouvelles section d'ajouter
	public function stpi_chkNiveauSectionIntegrity()
	{
		if (!$arrNbNiveauID = $this->stpi_selAll())
		{
			return false;
		}
		if (!$arrNbSectionID = $this->objSection->stpi_selAll())
		{
			return false;
		}
		foreach ($arrNbNiveauID as $v1)
		{
			foreach($arrNbSectionID as $v2)
			{
				$SQL1 = "SELECT nbNiveauSectionID";
				$SQL1 .= " FROM stpi_niv_Niveau_Section";
				$SQL1 .= " WHERE nbNiveauID = '" . $v1 . "'";
				$SQL1 .= " AND nbSectionID = '" . $v2 . "'";
				
				if ($result1 = $this->objBdd->stpi_select($SQL1))
				{
					//good
					mysql_free_result($result1);
				}
				else
				{
					//Le lien n'existe pas, il faut le créer
					$SQL2 = "INSERT INTO stpi_niv_Niveau_Section";
					$SQL2 .= " (nbNiveauID, nbSectionID)";
					$SQL2 .= " VALUES ('" . $v1 . "',";
					$SQL2 .= " '" . $v2 . "')";
					
					if (!$this->objBdd->stpi_insert($SQL2))
					{
						print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("niveausectionintegrity") . "</span><br/>\n");
						return false;
					}
				}
			}
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
		
		$SQL = "SELECT strName, boolDelete";
		$SQL .= " FROM stpi_niv_Niveau";
		$SQL .= " WHERE nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";

		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->strName = $row["strName"];
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
	
	
	public function stpi_setStrName($nstrName)
	{
		if (!$this->stpi_chkStrName($nstrName))
		{
			return false;
		}
		$this->strName = $nstrName;
		
		return true;
	}
	
	
	public function stpi_setBoolDelete($nboolDelete)
	{
		if (!$this->stpi_chkBoolDelete($nboolDelete))
		{
			return false;
		}
		$this->boolDelete = $nboolDelete;
		
		return true;
	}
	
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	
	public function stpi_getStrName()
	{
		return $this->strName;
	}

	
	public function stpi_getBoolDelete()
	{
		return $this->boolDelete;
	}
	
	
	public function stpi_getObjSection()
	{
		return $this->objSection;
	}
	
	
	public function stpi_getObjTypePage()
	{
		return $this->objTypePage;
	}
	
	
	public function stpi_getArrNbSectionID()
	{
		return $this->arrNbSectionID;
	}
	
	
	public function stpi_getArrNbTypePageID()
	{
		return $this->arrNbTypePageID;
	}	
		
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span>\n");
			return false;
		}
		
		if ($this->strName == "")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidname") . "</span>\n");
			return false;
		}
		
		if ($this->objBdd->stpi_chkExists($this->strName, "strName", "stpi_niv_Niveau"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("existsname") . "</span><br/>\n");
			return false;
		}
		
		if (!$this->objBdd->stpi_startTransaction())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
		
		//Création de la requête pour insérer le niveau
		$SQL1 = "INSERT INTO stpi_niv_Niveau";
		$SQL1 .= " (strName, boolDelete)";
		$SQL1 .= " VALUE ('" . $this->objBdd->stpi_trsInputToBdd($this->strName)  . "',"; 
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->boolDelete)  . "')";
		
		
		if ($this->objBdd->stpi_insert($SQL1))
		{
			//Aller chercher le id du nouveau niveau
			$this->nbID = mysql_insert_id();
		
			//Ajouter les liens entre le nouveau niveau et les sections
			if (!$this->stpi_chkNiveauSectionIntegrity())
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
				$this->objBdd->stpi_rollback();
				return false;
			}
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			$this->objBdd->stpi_rollback();
			return false;
		}
		
		if (!$this->objBdd->stpi_commit())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			$this->objBdd->stpi_rollback();
			return false;
		}	
		
		return true;
	}
	
	
	public function stpi_update($narrPermission)
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		
		if ($this->strName == "")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidname") . "</span><br/>\n");
			return false;
		}
				
		if (empty($narrPermission))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("securityoptionsinvalid") . "</span><br/>\n");
			return false;
		}
		
		if (!$arrNbSectionID = $this->stpi_selNbSectionID())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		
		if (!$this->objBdd->stpi_startTransaction())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		
		$SQL1 = "UPDATE stpi_niv_Niveau";
		$SQL1 .= " SET strName = '" . $this->objBdd->stpi_trsInputToBdd($this->strName) . "'";
		$SQL1 .= " WHERE nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if (!$this->objBdd->stpi_update($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			$this->objBdd->stpi_rollback();
			return false;
		}
		
		foreach ($arrNbSectionID as $nbSectionID)
		{
			if(!$this->objSection->stpi_setNbID($nbSectionID))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("securityoptionsinvalid") . "</span><br/>\n");
				$this->objBdd->stpi_rollback();
				return false;
			}
			
			if(!$arrNbSectionTypePageID = $this->objSection->stpi_selNbTypePageID())
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("securityoptionsinvalid") . "</span><br/>\n");
				$this->objBdd->stpi_rollback();
				return false;
			}
			
			if(isset($narrPermission[$nbSectionID]))
			{
				$arrNbTypePagePermissionID = $narrPermission[$nbSectionID];
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("securityoptionsinvalid") . "</span><br/>\n");
				$this->objBdd->stpi_rollback();
				return false;
			}
			
			foreach ($arrNbSectionTypePageID as $nbTypePageID)
			{
				if (isset($arrNbTypePagePermissionID[$nbTypePageID]))
				{
					$boolPermission = $arrNbTypePagePermissionID[$nbTypePageID];
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("securityoptionsinvalid") . "</span><br/>\n");
					$this->objBdd->stpi_rollback();
					return false;
				}
				
				$SQL2 = "SELECT nbNiveauSectionID";
				$SQL2 .= " FROM stpi_niv_Niveau_Section";
				$SQL2 .= " WHERE nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
				$SQL2 .= " AND nbSectionID = '" . $this->objBdd->stpi_trsInputToBdd($nbSectionID) . "'";
				
				if ($result = $this->objBdd->stpi_select($SQL2))
				{
					if ($row = mysql_fetch_assoc($result))
					{
						$nbNiveauSectionID = $row["nbNiveauSectionID"];
					}
					mysql_free_result($result);
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("securityoptionsinvalid") . "</span><br/>\n");
					$this->objBdd->stpi_rollback();
					return false;
				}
				
				
				$SQL3 = "SELECT nbNiveauSectionID, nbTypePageID";
				$SQL3 .= " FROM stpi_niv_Niveau_Section_TypePage";
				$SQL3 .= " WHERE nbNiveauSectionID = '" . $this->objBdd->stpi_trsInputToBdd($nbNiveauSectionID) . "'";
				$SQL3 .= " AND nbTypePageID = '" . $this->objBdd->stpi_trsInputToBdd($nbTypePageID) . "'";
			
				if ($result = $this->objBdd->stpi_select($SQL3))
				{
					if ($boolPermission)
					{
						//Good : la permission est déjà là
					}
					else
					{
						if ($row = mysql_fetch_assoc($result))
						{
							//La permission doit être enlevé
							$SQL4 = "DELETE FROM stpi_niv_Niveau_Section_TypePage";
							$SQL4 .= " WHERE nbNiveauSectionID = '" . $this->objBdd->stpi_trsInputToBdd($row["nbNiveauSectionID"]) . "'";
							$SQL4 .= " AND nbTypePageID = '" . $this->objBdd->stpi_trsInputToBdd($row["nbTypePageID"]) . "'";
							
							if (!$this->objBdd->stpi_delete($SQL4))
							{
								print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("erreursecurityoptionsinvalid") . "</span><br/>\n");
								$this->objBdd->stpi_rollback();
								return false;
							}
						}
					}
				}
				else
				{
					if ($boolPermission)
					{
						//La permission doit être ajoutée
						$SQL5 = "INSERT INTO stpi_niv_Niveau_Section_TypePage";
						$SQL5 .= " (nbNiveauSectionID, nbTypePageID)";		
						$SQL5 .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($nbNiveauSectionID) . "',";
						$SQL5 .= " '" . $this->objBdd->stpi_trsInputToBdd($nbTypePageID) . "')";

						if (!$this->objBdd->stpi_insert($SQL5))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("erreursecurityoptionsinvalid") . "</span><br/>\n");
							$this->objBdd->stpi_rollback();
							return false;
						}
					}
					else
					{
						//Good : la permission est déjà pas là	
					}
				}
			}
		}
		
		if (!$this->objBdd->stpi_commit())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			$this->objBdd->stpi_rollback();
			return false;
		}
		return true; 
	}
	
	
	public function stpi_delete()
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;				
		}
		
		if ($this->boolDelete == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("nonedeletable") . "</span><br/>\n");
			return false;
		}
		
		$SQL1 = "SELECT strTable";
		$SQL1 .= " FROM stpi_user_TypeUser";
		
		$boolUsed = false;
		
		if ($result = $this->objBdd->stpi_select($SQL1))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$SQL2 = "SELECT nbNiveauID";
				$SQL2 .= " FROM " . $row["strTable"];
				$SQL2 .= " WHERE nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
				
				if ($this->objBdd->stpi_select($SQL2))
				{
					$boolUsed = true;
				}
			}
			
			mysql_fetch_assoc($result);			
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		if ($boolUser)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("used") . "</span><br/>\n");
			return false;
		}
		
		if (!$this->objBdd->stpi_startTransaction())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		if ($this->arrNbSectionID = $this->stpi_selNbSectionID())
		{
			foreach ($this->arrNbSectionID as $nbSectionID)
			{
				$SQL3 = "SELECT nbNiveauSectionID";
				$SQL3 .= " FROM stpi_niv_Niveau_Section";
				$SQL3 .= " WHERE nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
				$SQL3 .= " AND nbSectionID = '" . $nbSectionID . "'";
				
				if ($result3 = $this->objBdd->stpi_select($SQL3))
				{
					if ($row3 = mysql_fetch_assoc($result3))
					{
						$nbNiveauSectionID = $row3["nbNiveauSectionID"];
					}
					else
					{
						print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
						$this->objBdd->stpi_rollback();
						return false;
					}
					mysql_free_result($result3);					
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
					$this->objBdd->stpi_rollback();
					return false;
				}
				
				if ($this->arrNbTypePageID = $this->stpi_selNbTypePageID($nbSectionID))
				{
					foreach ($this->arrNbTypePageID as $nbTypePageID)
					{
						$SQL4 = "DELETE FROM stpi_niv_Niveau_Section_TypePage";
						$SQL4 .= " WHERE nbNiveauSectionID = '" . $nbNiveauSectionID . "'";
						$SQL4 .= " AND nbTypePageID = '" . $nbTypePageID . "'";

						if (!$this->objBdd->stpi_delete($SQL4))
						{
							print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
							$this->objBdd->stpi_rollback();
							return false;
						}
					}
				}
				
				$SQL5 = "DELETE FROM stpi_niv_Niveau_Section";
				$SQL5 .= " WHERE nbNiveauSectionID = '" . $nbNiveauSectionID . "'";
				
				if (!$this->objBdd->stpi_delete($SQL5))
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
					$this->objBdd->stpi_rollback();
					return false;
				}
			}
		}
		
		$SQL6 = "DELETE FROM stpi_niv_Niveau";
		$SQL6 .= " WHERE nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
				
		if (!$this->objBdd->stpi_delete($SQL6))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			$this->objBdd->stpi_rollback();
			return false;
		}
		
		if (!$this->objBdd->stpi_commit())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			$this->objBdd->stpi_rollback();
			return false;
		}
		
		$this->nbID = 0;
		$this->strName = "";
		$this->boolDelete = 1;
		
		$this->arrNbSectionID = array();
		$this->arrNbTypePageID = array();
		
		return true;
	}

	
	public function stpi_affJsSelectNiveau()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_selNiveau(nnbNiveau)
		{
			if (nnbNiveau.length == 0)
			{ 
				document.getElementById("stpi_selNiveau").innerHTML = "";
		  		return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_selNiveau").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "niveau.php?nbNiveau=" + nnbNiveau + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_selNiveau").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affSelectNiveau()
	{
		if (!$arrNbNiveauID = $this->stpi_selAll())
		{
			return false;
		}
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("selectniveau"));
		print("<select id=\"nbNiveauSelect\" onchange=\"stpi_selNiveau(this.value)\">");
		print("<option selected=\"selected\" value=\"\"></option>\n");
		foreach ($arrNbNiveauID as $v)
		{
			$this->stpi_setNbID($v);
			print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($v) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->strName) . "</option>\n");				
		}
		print("</select><br/><br/>\n");
		print("</p>\n");
		print("<div id=\"stpi_selNiveau\"></div><br/>\n");
	}
	
	
	public function stpi_affJsSelectNiveauExternal()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_affNiveau(nnbNiveau)
		{
			if (nnbNiveau.length == 0)
			{ 
				document.getElementById("stpi_affNiveau").innerHTML = "";
		  		return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_selNiveau").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "niveauaff.php?nbNiveau=" + nnbNiveau + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affNiveau").innerHTML = xmlHttp.responseText;
				}
			}
			
			xmlHttp.open("GET", strUrl, true);
			
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affSelectNiveauExternal($nboolDisabled = false, $nnbNiveauID = 0)
	{
		if (!$arrNbNiveauID = $this->stpi_selAll())
		{
			return false;
		}
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("selectniveau") . "\n<br/>");
		if ($nboolDisabled)
		{
			print("<select disabled=\"disabled\" id=\"nbNiveau\" onchange=\"stpi_affNiveau(this.value)\">");
		}
		else
		{
			print("<select id=\"nbNiveau\" onchange=\"stpi_affNiveau(this.value)\">");
		}
		if ($nnbNiveauID == 0)
		{
			print("<option selected=\"selected\" value=\"\"></option>\n");
		}
		else
		{
			print("<option value=\"\"></option>\n");
		}
		foreach ($arrNbNiveauID as $nbNiveauID)
		{
			$this->stpi_setNbID($nbNiveauID);
			print("<option");
			if ($nbNiveauID == $nnbNiveauID)
			{
				print(" selected=\"selected\"");
			}			
			print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbNiveauID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->strName) . "</option>\n");				
		}
		print("</select><br/><br/>\n");
		print("</p>\n");
		print("<div id=\"stpi_affNiveau\"></div><br/>\n");
	}
	
	
	public function stpi_affNiveau()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$this->arrNbSectionID = $this->stpi_selNbSectionID();

		foreach ($this->arrNbSectionID as $nbSectionID)
		{
			if (!$this->arrNbTypePageID = $this->stpi_selNbTypePageID($nbSectionID))
			{
				continue;
			}			
			
			if (!$this->objSection->stpi_setNbID($nbSectionID))
			{
				return false;
			}
			
			if (!$this->objSection->stpi_setObjSectionLgFromBdd())
			{
				return false;
			}
			
			$objSectionLg = $this->objSection->stpi_getObjSectionLg();
			
			print("<div>\n");
			print($this->objBdd->stpi_trsBddToHTML($objSectionLg->stpi_getStrName()) . " : <br/>\n");
			
			print("<ul>\n");			
			foreach ($this->arrNbTypePageID as $nbTypePageID)
			{
				if (!$this->objTypePage->stpi_setNbID($nbTypePageID))
				{
					return false;
				}
				
				if (!$this->objTypePage->stpi_setObjTypePageLgFromBdd())
				{
					return false;
				}
				
				$objTypePageLg = $this->objTypePage->stpi_getObjTypePageLg();
				
				print("<li>" . $this->objBdd->stpi_trsBddToHTML($objTypePageLg->stpi_getStrName()) . "</li>\n");				
			}
			print("</ul>\n");
			print("</div>\n");
		}
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ClearAddNiveauForm()
		{
		  	document.getElementById("strAddName").value = "";
		  	document.getElementById("stpi_niveauAdd").innerHTML = "";
		}
		
		function stpi_addNiveau()
		{  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_niveauAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			
			document.getElementById("stpi_addNiveau").style.visibility = "hidden";
		
			var strUrl = "niveauadd.php?strName=" + encodeURIComponent(document.getElementById("strAddName").value) + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
						stpi_ClearAddNiveauForm();
						
						var nbNiveauIDIndex = xmlHttp.responseText.indexOf("nbNiveauID") + 11;
		  				var nbNiveauIDLen = xmlHttp.responseText.length - nbNiveauIDIndex;
		  				var nbNiveauID = xmlHttp.responseText.substr(nbNiveauIDIndex, nbNiveauIDLen);
		  				
		  				var o = document.createElement("option");
		  				o.text = nbNiveauID;
		  				o.value = nbNiveauID;
		  				try
		  				{
		  					document.getElementById("nbNiveauSelect").add(o,null);
		  				}
		  				catch(e)
		  				{
		  					document.getElementById("nbNiveauSelect").add(o);
		  				}
		 				document.getElementById("nbNiveauSelect").selectedIndex = document.getElementById("nbNiveauSelect").options.length - 1;
		 				
		 				stpi_selNiveau(nbNiveauID);
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_addNiveau").style.visibility = "visible";
			  			document.getElementById("stpi_niveauAdd").innerHTML = xmlHttp.responseText;
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
	
	
	public function stpi_affAdd()
	{
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("niveauname") . " ");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strAddName\" value=\"\" />\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_niveauAdd\"></span><br/>\n");
		print("<input id=\"stpi_addNiveau\" type=\"button\" onclick=\"stpi_addNiveau()\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonadd") . "\" />\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			document.getElementById("strEditName").disabled = false;
			var i = 0;
			while (true)
			{
				try
				{
					document.getElementById("chkbNiveau" + i).disabled = false;			
				}
				catch(e)
				{
					break;
				}
				i++;
			}
				
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		
		
		function stpi_niveauEdit()
		{
			xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_Save").style.visibility = "hidden";
		
			var i = 0;
			var boolChkBox;
			
			strParam =  "strName=" + document.getElementById("strEditName").value;
			
			strParam =  strParam + "&nbNiveauID=" + document.getElementById("nbNiveauSelect").value;
						
			while (true)
			{
				try
				{
					if (document.getElementById("chkbNiveau" + i).checked)
					{
						boolChkBox = 1;
					}
					else
					{
						boolChkBox = 0;
					}
					strParam = strParam + "&" + document.getElementById("chkbNiveau" + i).value + "=" + boolChkBox;			
				}
				catch(e)
				{
					break;
				}
				i++;
			}
			strParam = strParam + "&sid=" + Math.random();
			
			strParam = encodeURI(strParam);
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				stpi_selNiveau(document.getElementById("nbNiveauSelect").value);
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
		  			}
				}
			}
			
			xmlHttp.open("POST", "niveauedit.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affEdit()
	{
		$nbChkBoxCount = 0;
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("niveauname") . " ");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strEditName\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strName) . "\" /><br/>\n");
		print("</p>\n");
		
		$this->arrNbSectionID = $this->stpi_selNbSectionID();		
		
		foreach ($this->arrNbSectionID as $nbSectionID)
		{
			if (!$this->objSection->stpi_setNbID($nbSectionID))
			{
				return false;
			}
			
			if (!$this->objSection->stpi_setObjSectionLgFromBdd())
			{
				return false;
			}
			
			$arrStrTypePageName = array();
			
			foreach ($this->objSection->stpi_selNbTypePageID() as $nbTypePageID)
			{
				if (!$this->objTypePage->stpi_setNbID($nbTypePageID))
				{
					return false;
				}
				
				if (!$this->objTypePage->stpi_setObjTypePageLgFromBdd())
				{
					return false;
				}
				
				$objTypePageLg = $this->objTypePage->stpi_getObjTypePageLg();
				$arrStrTypePageName[$nbTypePageID] = $objTypePageLg->stpi_getStrName();
			}
			
			$objSectionLg = $this->objSection->stpi_getObjSectionLg();
			
			print("<br/><div>\n");
			print($this->objBdd->stpi_trsBddToHTML($objSectionLg->stpi_getStrName()) . " : <br/>\n");
			
			if (!$this->arrNbTypePageID = $this->stpi_selNbTypePageID($this->objSection->stpi_getNbID()))
			{
				$this->arrNbTypePageID = array();
			}
			
			foreach ($arrStrTypePageName as $nbTypePageID => $strName)
			{
				if (in_array($nbTypePageID, $this->arrNbTypePageID))
				{
					print("<input type=\"checkbox\"");
					print(" disabled=\"disabled\"");
					print(" checked=\"checked\"");
					print(" id=\"chkbNiveau" . $nbChkBoxCount . "\"");
					print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objSection->stpi_getNbID()) . "_" . $this->objBdd->stpi_trsBddToHTML($nbTypePageID) . "\" /> " . $this->objBdd->stpi_trsBddToHTML($strName) . "<br/>\n");
				}
				else
				{
					print("<input type=\"checkbox\"");
					print(" disabled=\"disabled\"");
					print(" id=\"chkbNiveau" . $nbChkBoxCount . "\"");
					print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->objSection->stpi_getNbID()) . "_" . $this->objBdd->stpi_trsBddToHTML($nbTypePageID) . "\" /> " . $this->objBdd->stpi_trsBddToHTML($strName) . "<br/>\n");
				}
				$nbChkBoxCount++;
			}
			print("</div>\n");
		}
		
			
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonedit") . "\" />\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_niveauEdit()\" value=\"" . $this->objTexte->stpi_getArrTxt("buttonsave") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" id=\"stpi_Del\" onclick=\"stpi_delNiveau()\" value=\"" . $this->objTexte->stpi_getArrTxt("buttondelete") . "\" />\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delNiveau()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "niveaudel.php?nbNiveauID=" + document.getElementById("nbNiveauSelect").value;
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
		function stpi_delNiveauConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "niveaudel.php?nbNiveauID=" + document.getElementById("nbNiveauSelect").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./niveaux.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delNiveauConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	
	public function stpi_selAll()
	{
		$SQL = "SELECT nbNiveauID";
		$SQL .= " FROM stpi_niv_Niveau";
		$SQL .= " ORDER BY nbNiveauID";
		
		$arrID = array();
	
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbNiveauID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}

	
	public function stpi_selNbSectionID()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$arrID = array();
			
		$SQL = "SELECT stpi_niv_Niveau_Section.nbSectionID";
		$SQL .= " FROM stpi_niv_Niveau_Section, stpi_niv_Section, stpi_niv_Section_Lg";
		$SQL .= " WHERE stpi_niv_Niveau_Section.nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_niv_Niveau_Section.nbSectionID = stpi_niv_Section.nbSectionID";
		$SQL .= " AND stpi_niv_Section.nbSectionID = stpi_niv_Section_Lg.nbSectionID";
		$SQL .= " AND stpi_niv_Section.boolActive = '1'";
		$SQL .= " AND stpi_niv_Section_Lg.strLg = '" . LG . "'";
		$SQL .= " ORDER BY stpi_niv_Section_Lg.strName";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSectionID"];
			}
		}
		else
		{
			return false;
		}
		
		return $arrID;
	}
	
	
	public function stpi_selNbTypePageID($nnbSection)
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$arrID = array();
			
		$SQL = "SELECT stpi_niv_Niveau_Section_TypePage.nbTypePageID";
		$SQL .= " FROM stpi_niv_Niveau_Section, stpi_niv_Section, stpi_niv_Niveau_Section_TypePage, stpi_niv_TypePage_Lg";
		$SQL .= " WHERE stpi_niv_Niveau_Section.nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		$SQL .= " AND stpi_niv_Niveau_Section.nbSectionID = '" . $this->objBdd->stpi_trsInputToBdd($nnbSection) . "'";
		$SQL .= " AND stpi_niv_Niveau_Section.nbSectionID = stpi_niv_Section.nbSectionID";
		$SQL .= " AND stpi_niv_Section.boolActive = '1'";
		$SQL .= " AND stpi_niv_Niveau_Section.nbNiveauSectionID = stpi_niv_Niveau_Section_TypePage.nbNiveauSectionID";
		$SQL .= " AND stpi_niv_Niveau_Section_TypePage.nbTypePageID = stpi_niv_TypePage_Lg.nbTypePageID";
		$SQL .= " AND stpi_niv_TypePage_Lg.strLg = '" . LG . "'";
		$SQL .= " ORDER BY stpi_niv_TypePage_Lg.strName";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbTypePageID"];
			}
		}
		else
		{
			return false;
		}
		
		return $arrID;
	}
}

?>