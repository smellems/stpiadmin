<?php
require_once(dirname(__FILE__) . "/../niveau/clsniveau.php");
require_once(dirname(__FILE__) . "/../security/clscryption.php");
require_once(dirname(__FILE__) . "/../email/clsemail.php");
require_once(dirname(__FILE__) . "/../area/clscountry.php");
class clsclient
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objNiveau;
	private $objCryption;
	private $objEmail;
	private $objCountry;
	
	private $nbID;
	private $nbNiveauID;
	private $strCourriel;
	private $strPassword;
	private $strNom;
	private $strPrenom;
	private $strCie;
	private $strTel;
	private $strAdresse;
	private $strVille;
	private $strProvinceID;
	private $strCountryID;
	private $strCodePostal;
	private $strLangID;
	private $dtEntryDate;
	private $boolDelete;
		
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtclient");
		$this->objLang = new clslang();
		$this->objNiveau = new clsniveau();
		$this->objCryption = new clscryption();
		$this->objEmail = new clsemail();
		$this->objCountry = new clscountry();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbNiveauID = 0;
			$this->strCourriel = "";
			$this->strPassword = "";
			$this->strNom = "";
			$this->strPrenom = "";
			$this->strCie = "";
			$this->strTel = "";
			$this->strAdresse = "";
			$this->strVille = "";
			$this->strProvinceID = "";
			$this->strCountryID = "";
			$this->strCodePostal = "";
			$this->strLangID = "";
			$this->dtEntryDate = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbClientID", "stpi_client_Client"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrEmail($nstrCourriel)
	{
		if (!$this->objEmail->stpi_chkStrEmail($nstrCourriel))
		{
			return false;
		}
		if ($this->nbID != 0)
		{
			if (!$this->objBdd->stpi_chkArrExists(array($nstrCourriel, $this->nbID), array("strCourriel", "nbClientID"), "stpi_client_Client"))
			{
				if ($this->objBdd->stpi_chkExists($nstrCourriel, "strCourriel", "stpi_client_Client"))
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("courrielexists") . "</span><br/>\n");
					return false;
				}
			}
		}
		else
		{
			if ($this->objBdd->stpi_chkExists($nstrCourriel, "strCourriel", "stpi_client_Client"))
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("courrielexists") . "</span><br/>\n");
				return false;
			}
		}
		
		return true;
	}
	
	public function stpi_chkStrPassword($nstrPassword)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrPassword))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidpassword") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objCryption->stpi_chkPasswordStrength($nstrPassword))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("passwordweak") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	public function stpi_chkStrOldPassword($nstrOldPassword)
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidoldpassword") . "</span><br/>\n");
			return false;	
		}
		if (!$this->objBdd->stpi_chkInputToBdd($nstrOldPassword))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidoldpassword") . "</span><br/>\n");
			return false;				
		}
		if ($this->strPassword != $this->objCryption->stpi_trsTextToCrypted($nstrOldPassword))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidoldpassword") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrNom($nstrNom)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrNom))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidnom") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrPrenom($nstrPrenom)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrPrenom))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidprenom") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrCie($nstrCie)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrCie) AND $nstrCie != "")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcie") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrTel($nstrTel)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($this->objBdd->stpi_trsTelToBdd($nstrTel)))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidtel") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrAdresse($nstrAdresse)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrAdresse))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidadresse") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrVille($nstrVille)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrVille))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidville") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrCodePostal($nstrCodePostal)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($this->objBdd->stpi_trsCodePostalToBdd($nstrCodePostal)))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcodepostal") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrLangID($nstrLangID)
	{
		$this->objLang->stpi_setArrLang();
		$arrLang = $this->objLang->stpi_getArrLang();
		if (!$this->objBdd->stpi_chkInputToBdd($nstrLangID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
			return false;				
		}
		if (!in_array($nstrLangID, $arrLang))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidlang") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkBoolDelete()
	{
		if ($this->boolDelete != 1)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrTxt("booldelete") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkLogin($nstrCourriel, $nstrPassword)
	{
		if (!$this->objEmail->stpi_chkStrEmail($nstrCourriel))
		{
			return false;
		}
		
		if (!$this->stpi_chkStrPassword($nstrPassword))
		{
			return false;
		}
		$nstrPassword = $this->objCryption->stpi_trsTextToCrypted($nstrPassword);
		
		$SQL = "SELECT nbClientID";
		$SQL .= " FROM stpi_client_Client";
		$SQL .= " WHERE  strCourriel = '" . $this->objBdd->stpi_trsInputToBdd($nstrCourriel) . "'";
		$SQL .= " AND strPassword = '" . $this->objBdd->stpi_trsInputToBdd($nstrPassword) . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				 return $row["nbClientID"];
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
	
	
	public function stpi_setNbID($nnbID)
	{
		if (!$this->stpi_chkNbID($nnbID))
		{
			return false;				
		}
		$this->nbID = $nnbID;
		
		$SQL = "SELECT nbNiveauID, strCourriel, strPassword, strNom, strPrenom, strCie, strTel, strAdresse, strVille, strProvinceID, strCountryID, strCodePostal, strLangID, dtEntryDate, boolDelete";
		$SQL .= " FROM stpi_client_Client WHERE nbClientID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);

		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbNiveauID = $row["nbNiveauID"];
				$this->strCourriel = $row["strCourriel"];
				$this->strPassword = $row["strPassword"];
				$this->strNom = $row["strNom"];
				$this->strPrenom = $row["strPrenom"];
				$this->strCie = $row["strCie"];
				$this->strTel = $this->objBdd->stpi_trsBddToTel($row["strTel"]);
				$this->strAdresse = $row["strAdresse"];
				$this->strVille = $row["strVille"];
				$this->strProvinceID = $row["strProvinceID"];
				$this->strCountryID = $row["strCountryID"];
				$this->strCodePostal = $row["strCodePostal"];
				$this->strLangID = $row["strLangID"];
				$this->dtEntryDate = $row["dtEntryDate"];
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
	
	
	public function stpi_setNbNiveauID($nnbNiveauID)
	{
		if (!$this->stpi_chkNbNiveauID($nnbNiveauID))
		{
			return false;
		}
		$this->nbNiveauID = $nnbNiveauID;
		
		return true;
	}
	
	public function stpi_setStrCourriel($nstrCourriel)
	{
		if (!$this->stpi_chkStrEmail($nstrCourriel))
		{
			return false;
		}
		$this->strCourriel = strtolower($nstrCourriel);
		return true;
	}
	
	public function stpi_setStrPassword($nstrPassword)
	{
		if (!$this->stpi_chkStrPassword($nstrPassword))
		{
			return false;
		}
		$this->strPassword = $this->objCryption->stpi_trsTextToCrypted($nstrPassword);
		return true;
	}
	
	public function stpi_setStrNom($nstrNom)
	{
		if (!$this->stpi_chkStrNom($nstrNom))
		{
			return false;
		}
		$this->strNom = $nstrNom;
		return true;
	}
	
	public function stpi_setStrPrenom($nstrPrenom)
	{
		if (!$this->stpi_chkStrPrenom($nstrPrenom))
		{
			return false;
		}
		$this->strPrenom = $nstrPrenom;
		
		return true;
	}
	
	public function stpi_setStrCie($nstrCie)
	{
		if (!$this->stpi_chkStrCie($nstrCie))
		{
			return false;
		}
		$this->strCie = $nstrCie;
		return true;
	}
	
	public function stpi_setStrTel($nstrTel)
	{
		if (!$this->stpi_chkStrTel($nstrTel))
		{
			return false;
		}
		$this->strTel = $this->objBdd->stpi_trsBddToTel($this->objBdd->stpi_trsTelToBdd($nstrTel));
		return true;
	}
	
	public function stpi_setStrAdresse($nstrAdresse)
	{
		if (!$this->stpi_chkStrAdresse($nstrAdresse))
		{
			return false;				
		}
		$this->strAdresse = $nstrAdresse;
		return true;
	}
	
	public function stpi_setStrVille($nstrVille)
	{
		if (!$this->stpi_chkStrVille($nstrVille))
		{
			return false;				
		}
		$this->strVille = $nstrVille;
		return true;
	}
	
	public function stpi_setStrCountryID($nstrCountryID)
	{
		if (!$this->objCountry->stpi_chkStrID($nstrCountryID))
		{
			return false;				
		}
		$this->strCountryID = $nstrCountryID;
		return true;
	}
	
	public function stpi_setStrProvinceID($nstrProvinceID)
	{
		if (!$this->objCountry->stpi_getObjProvince()->stpi_chkStrProvinceID($nstrProvinceID) AND $nstrProvinceID != "")
		{
			return false;				
		}
		$this->strProvinceID = $nstrProvinceID;
		return true;
	}
	
	public function stpi_setStrCodePostal($nstrCodePostal)
	{
		if (!$this->stpi_chkStrCodePostal($nstrCodePostal))
		{
			return false;				
		}
		$this->strCodePostal = $nstrCodePostal;
		return true;
	}
	
	public function stpi_setStrLangID($nstrLangID)
	{
		if (!$this->stpi_chkStrLangID($nstrLangID))
		{
			return false;
		}
		$this->strLangID = $nstrLangID;
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbNiveauID()
	{
		return $this->nbNiveauID;
	}
	
	public function stpi_getStrCourriel()
	{
		return $this->strCourriel;
	}
	
	public function stpi_getStrPassword()
	{
		return $this->strPassword;
	}
	
	public function stpi_getStrNom()
	{
		return $this->strNom;
	}
	
	public function stpi_getStrPrenom()
	{	
		return $this->strPrenom;
	}
	
	public function stpi_getStrCie()
	{
		return $this->strCie;
	}
	
	public function stpi_getStrTel()
	{
		return $this->strTel;
	}
	
	public function stpi_getStrAdresse()
	{
		return $this->strAdresse;
	}
	
	public function stpi_getStrVille()
	{
		return $this->strVille;
	}
	
	public function stpi_getStrCountryID()
	{
		return $this->strCountryID;
	}
	
	public function stpi_getStrProvinceID()
	{
		return $this->strProvinceID;
	}
	
	public function stpi_getStrCodePostal()
	{
		return $this->strCodePostal;
	}
	
	public function stpi_getStrLang()
	{
		return $this->strLangID;
	}
	
	public function stpi_getObjCountry()
	{
		return $this->objCountry;
	}
	
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	public function stpi_getObjEmail()
	{
		return $this->objEmail;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span>\n");
			return false;
		}
		
		$SQL = "INSERT INTO stpi_client_Client";
		$SQL .= " (nbNiveauID, strCourriel, strPassword, strNom, strPrenom, strCie, strTel, strAdresse, strVille, strProvinceID, strCountryID, strCodePostal, strLangID, dtEntryDate, boolDelete)";
		$SQL .= " VALUES (2, '" . $this->objBdd->stpi_trsInputToBdd($this->strCourriel) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strPassword) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strPrenom) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strCie) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->objBdd->stpi_trsTelToBdd($this->strTel)) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strAdresse) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strVille) . "',";
		if ($this->strProvinceID == "")
		{
			$SQL .= " NULL,";
		}
		else
		{
			$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "',";
		}
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->objBdd->stpi_trsCodePostalToBdd($this->strCodePostal)) . "',";
		$SQL .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strLangID) . "', NOW(), 1)";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = mysql_insert_id();
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
	}
	
	
	public function stpi_update()
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		$SQL1 = "UPDATE stpi_client_Client";
		$SQL1 .= " SET strCourriel='" . $this->objBdd->stpi_trsInputToBdd($this->strCourriel) . "'";
		$SQL1 .= ", strPassword='" . $this->objBdd->stpi_trsInputToBdd($this->strPassword) . "'";
		$SQL1 .= ", strNom='" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "'";
		$SQL1 .= ", strPrenom='" . $this->objBdd->stpi_trsInputToBdd($this->strPrenom) . "'";
		$SQL1 .= ", strCie='" . $this->objBdd->stpi_trsInputToBdd($this->strCie) . "'";
		$SQL1 .= ", strTel='" . $this->objBdd->stpi_trsInputToBdd($this->objBdd->stpi_trsTelToBdd($this->strTel)) . "'";
		$SQL1 .= ", strAdresse='" . $this->objBdd->stpi_trsInputToBdd($this->strAdresse) . "'";
		$SQL1 .= ", strVille='" . $this->objBdd->stpi_trsInputToBdd($this->strVille) . "'";
		if ($this->strProvinceID == "isNULL")
		{
			$SQL1 .= ", strProvinceID=NULL";
		}
		else
		{
			$SQL1 .= ", strProvinceID='" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
		}
		$SQL1 .= ", strCountryID='" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
		$SQL1 .= ", strCodePostal='" . $this->objBdd->stpi_trsInputToBdd($this->objBdd->stpi_trsCodePostalToBdd($this->strCodePostal)) . "'";
		$SQL1 .= ", strLangID='" . $this->objBdd->stpi_trsInputToBdd($this->strLangID) . "'";
		$SQL1 .= " WHERE nbClientID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "";
		
		if (!$this->objBdd->stpi_update($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_delete($nnbClientID)
	{
		if (!$this->stpi_chkNbID($nnbClientID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;				
		}
		
		$SQL = "DELETE FROM stpi_client_Client WHERE nbClientID = " .$this->objBdd->stpi_trsInputToBdd($nnbClientID);
						
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_resetPassword()
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("changingpass") . "</span><br/>\n");
			return false;				
		}
		
		$strPassword = $this->objCryption->stpi_selPasswordGenerator();
		
		$SQL1 = "UPDATE stpi_client_Client";
		$SQL1 .= " SET strPassword = '" . $this->objBdd->stpi_trsInputToBdd($this->objCryption->stpi_trsTextToCrypted($strPassword)) . "'";
		$SQL1 .= " WHERE nbClientID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if ($this->objBdd->stpi_update($SQL1))
		{
			print("<span style=\"color:#008000;\"><b>" . $this->objTexte->stpi_getArrTxt("passchanged") . " " . $strPassword . "</b></span><br/>\n");
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("changingpass") . "</span><br/>\n");
			return false;			
		}
	}
	
	
	public function stpi_changePassword()
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("changingpass") . "</span><br/>\n");
			return false;				
		}
		
		$SQL1 = "UPDATE stpi_client_Client";
		$SQL1 .= " SET strPassword = '" . $this->objBdd->stpi_trsInputToBdd($this->strPassword) . "'";
		$SQL1 .= " WHERE nbClientID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if ($this->objBdd->stpi_update($SQL1))
		{
			print("<span style=\"color:#008000;\"><b>" . $this->objTexte->stpi_getArrTxt("newpasschanged") . "</b></span><br/>\n");
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("changingpass") . "</span><br/>\n");
			return false;			
		}
	}

	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchClient(nstrText)
		{
			if (nstrText.length == 0)
			{ 
				document.getElementById("stpi_affClient").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affClient").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "clientsaff.php?strClient=" + nstrText + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affClient").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("client") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchClient(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affClient\"></span>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addClient()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_ClientAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddClient").style.visibility = "hidden";
			var strParam = "strCourriel=" + encodeURIComponent(document.getElementById("strCourriel").value);
			strParam = strParam + "&strPassword=" + encodeURIComponent(document.getElementById("strPassword").value);
			strParam = strParam + "&strPasswordConfirm=" + encodeURIComponent(document.getElementById("strPasswordConfirm").value);
			strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&strCie=" + encodeURIComponent(document.getElementById("strCie").value);
			strParam = strParam + "&strTel=" + encodeURIComponent(document.getElementById("strTel").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			strParam = strParam + "&strLangID=" + encodeURIComponent(document.getElementById("strLangID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./client.php?l=" + "<?php print(LG) ?>" + "&nbClientID=";
		  				var nbClientIDIndex = xmlHttp.responseText.indexOf("nbClientID") + 11;
		  				var nbClientIDLen = xmlHttp.responseText.length - nbClientIDIndex;
		  				var nbClientID = xmlHttp.responseText.substr(nbClientIDIndex, nbClientIDLen);
		  				strUrlRedirect = strUrlRedirect + nbClientID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddClient").style.visibility = "visible";
			  			document.getElementById("stpi_ClientAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "clientadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affJsChkPasswordStrength()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_chkPasswordStrength(nstrPassword)
		{
			if (nstrPassword.length == 0)
			{ 
				document.getElementById("stpi_chkPasswordStrength").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_chkPasswordStrength").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "clientchkpassstrength.php?strPassword=" + nstrPassword + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_chkPasswordStrength").innerHTML = xmlHttp.responseText;
				}
			}
			xmlHttp.open("GET", strUrl, true);
			xmlHttp.send(null);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsChkPasswordStrengthPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_chkPasswordStrength(nstrPassword)
		{
			if (nstrPassword.length == 0)
			{ 
				document.getElementById("stpi_chkPasswordStrength").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_chkPasswordStrength").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "stpiadmin/clientchkpassstrength.php?strPassword=" + nstrPassword + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_chkPasswordStrength").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("lang") . "<br/>\n");
		print("<select id=\"strLangID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrStrLangID = $this->objLang->stpi_selAll())
		{
			foreach($arrStrLangID as $strLangID)
			{
				if ($this->objLang->stpi_setStrID($strLangID))
				{
					print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($strLangID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objLang->stpi_getStrLang()) . "</option>\n");
				}
			}
		}		
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("courriel") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"200\" size=\"35\" id=\"strCourriel\" value=\"\" />\n");
		print("</p>\n");
		
		$this->stpi_affJsChkPasswordStrength();
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("password") . "<br/>\n");
		print("<input type=\"password\" onkeyup=\"stpi_chkPasswordStrength(this.value)\" maxlength=\"50\" size=\"18\" id=\"strPassword\" value=\"\" />\n");
		print("<span id=\"stpi_chkPasswordStrength\"></span><br/>\n");
		print($this->objTexte->stpi_getArrTxt("password2") . "<br/>\n");
		print("<input type=\"password\" maxlength=\"50\" size=\"18\" id=\"strPasswordConfirm\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("nom") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("prenom") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("cie") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strCie\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("tel") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strTel\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("adresse") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"255\" size=\"55\" id=\"strAdresse\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("ville") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("province") . "<br/>\n");
		print("<select id=\"strProvinceID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrStrIDs = $this->objCountry->stpi_getObjProvince()->stpi_selAll())
		{
			foreach($arrStrIDs as $strIDs)
			{
				list($strProvinceID, $strCountryID) = explode("-", $strIDs);
				if ($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setStrProvinceID($strProvinceID))
				{
					if ($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setStrCountryID($strCountryID))
					{
						if ($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setNbID($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_selStrIDLG()))
						{
							print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($strProvinceID) . "-" . $this->objBdd->stpi_trsBddToHTML($strCountryID) . "\">" . $strCountryID . " - " . $this->objBdd->stpi_trsBddToHTML($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}	
		print("</select><br/>\n");

		print($this->objTexte->stpi_getArrTxt("country") . "<br/>\n");
		print("<select id=\"strCountryID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrStrCountryID = $this->objCountry->stpi_selAll())
		{
			foreach($arrStrCountryID as $strCountryID)
			{
				if ($this->objCountry->stpi_getObjCountryLg()->stpi_setStrCountryID($strCountryID))
				{
					if ($this->objCountry->stpi_getObjCountryLg()->stpi_setNbID($this->objCountry->stpi_getObjCountryLg()->stpi_selStrCountryIDLG()))
					{
						print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($strCountryID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objCountry->stpi_getObjCountryLg()->stpi_getStrName()) . "</option>\n");
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("codepostal") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_ClientAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddClient\" type=\"button\" onclick=\"stpi_addClient()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsAddPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addClient(nRedirect)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_ClientAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddClient").style.visibility = "hidden";
			var strParam = "strCourriel=" + encodeURIComponent(document.getElementById("strCourriel").value);
			strParam = strParam + "&strPassword=" + encodeURIComponent(document.getElementById("strPassword").value);
			strParam = strParam + "&strPasswordConfirm=" + encodeURIComponent(document.getElementById("strPasswordConfirm").value);
			strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&strCie=" + encodeURIComponent(document.getElementById("strCie").value);
			strParam = strParam + "&strTel=" + encodeURIComponent(document.getElementById("strTel").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			strParam = strParam + "&strCaptcha=" + encodeURIComponent(document.getElementById("strCaptcha").value);
			try
			{
				strParam = strParam + "&strProvinceID=" + document.getElementById("strProvinceID").value;
				strParam = strParam + "&sid=" + Math.random();
			}
			catch(e)
			{
				strParam = strParam + "&sid=" + Math.random();
			}
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./" + nRedirect + ".php?l=" + "<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddClient").style.visibility = "visible";
			  			document.getElementById("stpi_ClientAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "clientaddpublic.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affAddPublic($nStrRedirect)
	{
		print("<table width=\"100%\" >\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("courriel") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"200\" size=\"30\" id=\"strCourriel\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("password") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"password\" onkeyup=\"stpi_chkPasswordStrength(this.value)\" maxlength=\"50\" size=\"20\" id=\"strPassword\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_chkPasswordStrength\"></span>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("password2") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"password\" maxlength=\"50\" size=\"20\" id=\"strPasswordConfirm\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("nom") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");

		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("prenom") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");

		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("cie") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strCie\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("tel") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strTel\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("adresse") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strAdresse\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("ville") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("country") . " / " . $this->objTexte->stpi_getArrTxt("province") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		$this->objCountry->stpi_affSelectCountryShippable(NULL, NULL, 0, 0);
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("codepostal") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("captcha") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<img style=\"border: 2px solid black;\" src=\"./stpiadmin/captcha.php\" alt=\"Captcha\"/>\n");
		print("<br/>\n");
		print("<input type=\"text\" size=\"20\" id=\"strCaptcha\" name=\"strCaptcha\" value=\"\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td colspan=\"2\" style=\"text-align: right; vertical-align: top;\" >\n");
		print("<span id=\"stpi_ClientAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddClient\" type=\"button\" onclick=\"stpi_addClient('" . $nStrRedirect . "')\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("</table>\n");
	}
	
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			document.getElementById("strCourriel").disabled = false;
			document.getElementById("strNom").disabled = false;
			document.getElementById("strPrenom").disabled = false;
			document.getElementById("strCie").disabled = false;
			document.getElementById("strTel").disabled = false;
			document.getElementById("strAdresse").disabled = false;
			document.getElementById("strVille").disabled = false;
			document.getElementById("strProvinceID").disabled = false;
			document.getElementById("strCountryID").disabled = false;
			document.getElementById("strCodePostal").disabled = false;
			document.getElementById("strLangID").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editClient()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbClientID=" + encodeURIComponent(document.getElementById("nbClientID").value);
			strParam = strParam + "&strCourriel=" + encodeURIComponent(document.getElementById("strCourriel").value);
			strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&strCie=" + encodeURIComponent(document.getElementById("strCie").value);
			strParam = strParam + "&strTel=" + encodeURIComponent(document.getElementById("strTel").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			strParam = strParam + "&strLangID=" + encodeURIComponent(document.getElementById("strLangID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./client.php?l=" + "<?php print(LG); ?>" + "&nbClientID=";
		  				var nbClientIDIndex = xmlHttp.responseText.indexOf("nbClientID") + 11;
		  				var nbClientIDLen = xmlHttp.responseText.length - nbClientIDIndex;
		  				var nbClientID = xmlHttp.responseText.substr(nbClientIDIndex, nbClientIDLen);
		  				strUrlRedirect = strUrlRedirect + nbClientID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "clientedit.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsEditPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			document.getElementById("strCourriel").disabled = false;
			document.getElementById("strNom").disabled = false;
			document.getElementById("strPrenom").disabled = false;
			document.getElementById("strCie").disabled = false;
			document.getElementById("strTel").disabled = false;
			document.getElementById("strAdresse").disabled = false;
			document.getElementById("strVille").disabled = false;
			try
			{
				document.getElementById("strProvinceID").disabled = false;
			}
			catch(e)
			{
			}			
			document.getElementById("strCountryID").disabled = false;
			document.getElementById("strCodePostal").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editClient()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbClientID=" + encodeURIComponent(document.getElementById("nbClientID").value);
			strParam = strParam + "&strCourriel=" + encodeURIComponent(document.getElementById("strCourriel").value);
			strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&strCie=" + encodeURIComponent(document.getElementById("strCie").value);
			strParam = strParam + "&strTel=" + encodeURIComponent(document.getElementById("strTel").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			try
			{
				strParam = strParam + "&strProvinceID=" + document.getElementById("strProvinceID").value;
				strParam = strParam + "&sid=" + Math.random();
			}
			catch(e)
			{
				strParam = strParam + "&sid=" + Math.random();
			}
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./clientpublic.php?l=" + "<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
					else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "clienteditpublic.php", true);
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
		if ($this->nbID == 0)
		{
			return false;				
		}
		print("<input type=\"hidden\" id=\"nbClientID\" value=\"" . $this->nbID . "\" />\n");
				
		print("<p>\n");			
		print($this->objTexte->stpi_getArrTxt("lang") . "<br/>\n");
		print("<select disabled=\"disabled\" id=\"strLangID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrNbLangID = $this->objLang->stpi_selAll())
		{
			foreach($arrNbLangID as $nbLangID)
			{
				if ($this->objLang->stpi_setStrID($nbLangID))
				{
					print("<option");
					if ($this->strLangID == $nbLangID)
					{
						print(" selected=\"selected\"");
					}
					print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($nbLangID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objLang->stpi_getStrLang()) . "</option>\n");
				}
			}
		}		
		print("</select>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("courriel") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"200\" size=\"35\" id=\"strCourriel\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCourriel) . "\" />\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("nom") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strNom) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("prenom") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strPrenom) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("cie") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strCie\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCie) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("tel") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strTel\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strTel) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("adresse") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"55\" id=\"strAdresse\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strAdresse) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("ville") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strVille) . "\" /><br/>\n");
		print("</p>\n");

		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("province") . "<br/>\n");
		print("<select disabled=\"disabled\" id=\"strProvinceID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrStrIDs = $this->objCountry->stpi_getObjProvince()->stpi_selAll())
		{
			foreach($arrStrIDs as $strIDs)
			{
				list($strProvinceID, $strCountryID) = explode("-", $strIDs);
				if ($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setStrProvinceID($strProvinceID))
				{
					if ($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setStrCountryID($strCountryID))
					{
						if ($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_setNbID($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_selStrIDLG()))
						{
							print("<option");
							if ($this->strProvinceID == $strProvinceID AND $this->strCountryID == $strCountryID)
							{
								print(" selected=\"selected\"");
							}
							print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($strProvinceID) . "-" . $this->objBdd->stpi_trsBddToHTML($strCountryID) . "\">" . $strCountryID . " - " . $this->objBdd->stpi_trsBddToHTML($this->objCountry->stpi_getObjProvince()->stpi_getObjProvinceLg()->stpi_getStrName()) . "</option>\n");
						}
					}
				}
			}
		}	
		print("</select><br/>\n");

		print($this->objTexte->stpi_getArrTxt("country") . "<br/>\n");
		print("<select disabled=\"disabled\" id=\"strCountryID\">\n");
		print("<option value=\"\"></option>\n");
		if ($arrStrCountryID = $this->objCountry->stpi_selAll())
		{
			foreach($arrStrCountryID as $strCountryID)
			{
				if ($this->objCountry->stpi_getObjCountryLg()->stpi_setStrCountryID($strCountryID))
				{
					if ($this->objCountry->stpi_getObjCountryLg()->stpi_setNbID($this->objCountry->stpi_getObjCountryLg()->stpi_selStrCountryIDLG()))
					{
						print("<option");
						if ($this->strCountryID == $strCountryID)
						{
							print(" selected=\"selected\"");
						}
						print(" value=\"" . $this->objBdd->stpi_trsBddToHTML($strCountryID) . "\">" . $this->objBdd->stpi_trsBddToHTML($this->objCountry->stpi_getObjCountryLg()->stpi_getStrName()) . "</option>\n");
					}
				}
			}
		}
		print("</select>\n");
		print("</p>\n");
							
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("codepostal") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCodePostal) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editClient()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClientPassReset()\" value=\"" . $this->objTexte->stpi_getArrTxt("resetpassword") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClientDel()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affEditPublic()
	{
		if ($this->nbID == 0)
		{
			return false;				
		}
		
		print("<input type=\"hidden\" id=\"nbClientID\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" />\n");

		print("<table width=\"100%\" >\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("courriel") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"200\" size=\"30\" id=\"strCourriel\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCourriel) . "\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("password") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<a href=\"./clientchangepasspublic.php?l=" . LG . "\">" . $this->objTexte->stpi_getArrTxt("changepassword") . "</a>\n");
		print("</td>\n");
		print("</tr>\n");

		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("nom") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strNom) . "\" /><br/>\n");
		print("</td>\n");
		print("</tr>\n");
				
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("prenom") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strPrenom) . "\" /><br/>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("cie") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strCie\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCie) . "\" /><br/>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("tel") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strTel\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strTel) . "\" /><br/>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("adresse") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strAdresse\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strAdresse) . "\" /><br/>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("ville") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strVille) . "\" /><br/>\n");
		print("</td>\n");
		print("</tr>\n");

		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("country") . " / " . $this->objTexte->stpi_getArrTxt("province") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		$this->objCountry->stpi_affSelectCountryShippable($this->strCountryID, $this->strProvinceID, 0, 1);
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("codepostal") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCodePostal) . "\" /><br/>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td colspan=\"2\" style=\"text-align: right; vertical-align: top;\" >\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editClient()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("</table>\n");
	}
	
	
	public function stpi_affJsPassChange()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ClearPasswordChangeForm()
		{
		  	document.getElementById("strOldPassword").value = "";
		  	document.getElementById("strPassword").value = "";
		  	document.getElementById("strPasswordConfirm").value = "";
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsPassReset()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ClientPassReset()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "clientpassreset.php?nbClientID=" + document.getElementById("nbClientID").value;
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
		function stpi_ClientPassResetConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "clientpassreset.php?nbClientID=" + document.getElementById("nbClientID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
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
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affPassReset()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("confirmpassreset") . "</b><br/>\n");
		print("<input type=\"button\" onclick=\"stpi_ClientPassResetConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}	
	
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ClientDel()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "clientdel.php?nbClientID=" + document.getElementById("nbClientID").value;
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
		
		
		function stpi_ClientDelConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "clientdel.php?nbClientID=" + document.getElementById("nbClientID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./clients.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_ClientDelConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_selSearchClient($nstrX)
	{
		if (!$this->stpi_chkStrNom($nstrX))
		{
			return false;
		}
		
		$SQL = "SELECT nbClientID";
		$SQL .= " FROM stpi_client_Client";
		$SQL .= " WHERE strNom LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrX) . "%'";
		$SQL .= " OR strPrenom LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrX) . "%'";
		$SQL .= " ORDER BY strNom, strPrenom LIMIT 0,10";
		
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbClientID"];
			}
			
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
	
	
	public function stpi_selNbCommandeID()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$SQL = "SELECT nbCommandeID";
		$SQL .= " FROM stpi_commande_Commande";
		$SQL .= " WHERE nbClientID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbCommandeID"];
			}
			
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
	
	public function stpi_selNbRegistreID()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$SQL = "SELECT nbRegistreID";
		$SQL .= " FROM stpi_registre_Registre";
		$SQL .= " WHERE nbClientID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbRegistreID"];
			}
			
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
	
	public function stpi_selNbRegistreIDPublic()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		$SQL = "SELECT nbRegistreID";
		$SQL .= " FROM stpi_registre_Registre";
		$SQL .= " WHERE nbClientID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " AND boolActif=1";
		$SQL .= " AND dtFin >= NOW() - INTERVAL 30 DAY";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$nbID =  $row["nbRegistreID"];
			}
			else
			{
				mysql_free_result($result);
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