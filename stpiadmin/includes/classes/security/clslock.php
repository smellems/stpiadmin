<?php

require_once(dirname(__FILE__) . "/../user/clsuser.php");

class clslock
{
	
	private $objBdd;
	private $objTexte;
	private $objUser;
	
	private $strPage;
	private $strRedirect;
	private $strPublicRegister = "register.php";
	private $strPublicMyAccount = "clientpublic.php";
	private $strPublicLogout = "logout.php";
	
	private $nbNiveauID;
	
	public function __construct($nstrPage, $nstrRedirect = "index.php")
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtsecurity");
		$this->objUser = new clsuser(); 
		$this->strPage = $nstrPage;
		$this->strRedirect = $nstrRedirect;
	}	
	
	public function stpi_setNbNiveauID()
	{
		$SQL = "SELECT strTable, strChamp";
		$SQL .= " FROM stpi_user_TypeUser";
		$SQL .= " WHERE nbTypeUserID = " . $this->objUser->stpi_getNbTypeUserID();
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$strTable = $row["strTable"];
				$strChamp = $row["strChamp"];
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
		
		if (isset($strTable) && isset($strChamp))
		{
			$SQL1 = "SELECT nbNiveauID";
			$SQL1 .= " FROM " . $strTable;
			$SQL1 .= " WHERE " . $strChamp . " = " . $this->objUser->stpi_getNbID();
			
			if ($result1 = $this->objBdd->stpi_select($SQL1))
			{
				if ($row1 = mysql_fetch_assoc($result1))
				{
					$this->nbNiveauID = $row1["nbNiveauID"];
				}
				else
				{
					return false;
				}		
				mysql_free_result($result1);
			}
			else
			{
				return false;	
			}
		}
		else
		{
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_setStrPage($nstrPage)
	{
		$this->strPage = $nstrPage;
	}
	
	public function stpi_setObjUserFromSession()
	{
		if (!$this->objUser = $this->objUser->stpi_getObjUserFromSession())
		{
			return false;
		}
		return true;
	}	
	
	public function stpi_getNbNiveauID()
	{
		return $this->nbNiveauID;
	}
	
	
	public function stpi_chkIP()
	{
		if ($this->objUser->stpi_getNbIP() != $_SERVER["REMOTE_ADDR"])
		{
			return false;			
		}
		
		return true;
	}
	
	
	public function stpi_chkPageEncrypted()
	{
		if ($this->strPage == "")
		{
			return false;
		}
		
		$SQL = "SELECT boolCrypted";
		$SQL .= " FROM stpi_niv_Page";
		$SQL .= " WHERE strName = '" . $this->strPage . "'";
		if ($result = $this->objBdd->stpi_Select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				if ($row["boolCrypted"])
				{
					$this->stpi_pageEncrypted();
				}
				else if (!$row["boolCrypted"])
				{
					$this->stpi_pageNotEncrypted();
				}
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
	
	
	public function stpi_pageEncrypted()
	{
		// if (!$_SERVER["HTTPS"])
		// {
		// 	header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]) ;	
		// 	exit;
		// }
	}
	
	
	public function stpi_pageNotEncrypted()
	{
		if ($_SERVER["HTTPS"])
		{
			header("Location: http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]) ;
			exit;
		}
	}
	
	
	public function stpi_chkPermission()
	{
		$SQL = "SELECT stpi_niv_Page.strName";
		$SQL .= " FROM stpi_niv_Niveau_Section,";
		$SQL .= " stpi_niv_Niveau_Section_TypePage,";
		$SQL .= " stpi_niv_Section,";
		$SQL .= " stpi_niv_Section_Page,";
		$SQL .= " stpi_niv_Page";
		$SQL .= " WHERE stpi_niv_Niveau_Section.nbNiveauID = '" . $this->nbNiveauID . "'";
		$SQL .= " AND stpi_niv_Niveau_Section.nbSectionID = stpi_niv_Section.nbSectionID";
		$SQL .= " AND stpi_niv_Section.boolActive = 1";
		$SQL .= " AND stpi_niv_Section.nbSectionID = stpi_niv_Section_Page.nbSectionID";
		$SQL .= " AND stpi_niv_Section_Page.nbPageID = stpi_niv_Page.nbPageID";
		$SQL .= " AND stpi_niv_Niveau_Section.nbNiveauSectionID = stpi_niv_Niveau_Section_TypePage.nbNiveauSectionID";
		$SQL .= " AND stpi_niv_Niveau_Section_TypePage.nbTypePageID = stpi_niv_Page.nbTypePageID";
		$SQL .= " AND stpi_niv_Page.strName = '" . $this->objBdd->stpi_trsInputToBdd($this->strPage) . "'";

		//Vérifier si il a accès à la page en question
		if (!$this->objBdd->stpi_select($SQL))
		{
			return false;			
		}
		
		return true;
	}
	
	
	public function stpi_run()
	{
		if (!$this->stpi_setObjUserFromSession())
		{
			print($this->objTexte->stpi_getArrErrTxt("nosession") . " <a href=\"./" . $this->strRedirect . "?l=" . LG . "\">" . $this->objTexte->stpi_getArrErrTxt("liens") . "</a>\n");
			print("<script type=\"text/javascript\">\n");
			print("window.location = \"./" . $this->strRedirect . "?l=" . LG . "\";\n");
			print("</script>\n");
			exit;
		}
		if (!$this->stpi_chkPageEncrypted())
		{
			exit;
		}
		if (!$this->stpi_chkIP())
		{
			print($this->objTexte->stpi_getArrErrTxt("nosession") . " <a href=\"./" . $this->strRedirect . "?l=" . LG . "\">" . $this->objTexte->stpi_getArrErrTxt("liens") . "</a>\n");
			print("<script type=\"text/javascript\">\n");
			print("window.location = \"./" . $this->strRedirect . "?l=" . LG . "\";\n");
			print("</script>\n");
			exit;
		}
		if (!$this->stpi_setNbNiveauID())
		{
			print($this->objTexte->stpi_getArrErrTxt("pasacces") . " <a href=\"./" . $this->strRedirect . "?l=" . LG . "\">" . $this->objTexte->stpi_getArrErrTxt("liens") . "</a>\n");
			print("<script type=\"text/javascript\">\n");
			print("window.location = \"./" . $this->strRedirect . "?l=" . LG . "\";\n");
			print("</script>\n");
			exit;
		}
		if (!$this->stpi_chkPermission())
		{
			print($this->objTexte->stpi_getArrErrTxt("pasacces") . " <a href=\"./" . $this->strRedirect . "?l=" . LG . "\">" . $this->objTexte->stpi_getArrErrTxt("liens") . "</a>\n");
			print("<script type=\"text/javascript\">\n");
			print("window.location = \"./" . $this->strRedirect . "?l=" . LG . "\";\n");
			print("</script>\n");
			exit;
		}		
	}
	
	
	public function stpi_affUrl()
	{
		if ($this->stpi_setObjUserFromSession())
		{
			if ($this->objUser->stpi_getNbTypeUserID() == 2)
			{
				$this->stpi_affMyAccountUrl();
			}
			else
			{
				$this->stpi_affLoginUrl();
			}
		}
		else
		{
			$this->stpi_affLoginUrl();
		}	
	}
	
	public function stpi_affMyAccountUrl()
	{
		print("<a href=\"./" . $this->strPublicMyAccount . "?l=" . LG . "\">");
		print($this->objBdd->stpi_trsBddToHTML($this->objTexte->stpi_getArrTxt("myaccount")));
		print("</a>");
		print(" | ");
		print("<a href=\"./" . $this->strPublicLogout . "?l=" . LG . "\" >");
		print($this->objBdd->stpi_trsBddToHTML($this->objTexte->stpi_getArrTxt("logout")));
		print("</a>\n");
	}
	
	
	public function stpi_affLoginUrl()
	{
		print("<a href=\"./" . $this->strRedirect . "?l=" . LG . "\">");
		print($this->objBdd->stpi_trsBddToHTML($this->objTexte->stpi_getArrTxt("login")));
		print("</a>");
		print(" | ");
		print("<a href=\"./" . $this->strPublicRegister . "?l=" . LG . "\" >");
		print($this->objBdd->stpi_trsBddToHTML($this->objTexte->stpi_getArrTxt("register")));
		print("</a>\n");
	}
}

?>