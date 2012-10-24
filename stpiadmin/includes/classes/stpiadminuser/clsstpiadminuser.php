<?php

require_once(dirname(__FILE__) . "/../niveau/clsniveau.php");
require_once(dirname(__FILE__) . "/../security/clscryption.php");
require_once(dirname(__FILE__) . "/../email/clsemail.php");

class clsstpiadminuser
{
	private $objBdd;
	private $objTexte;
	private $objNiveau;
	private $objCryption;
	private $objEmail;
	
	private $nbID;
	private $nbNiveauID;
	private $strUserName;
	private $strPassword;
	private $strNom;
	private $strPrenom;
	private $strEmail;
	private $dtEntryDate;
		
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtstpiadminuser");
		$this->objNiveau = new clsniveau();
		$this->objCryption = new clscryption();
		$this->objEmail = new clsemail();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbNiveauID = 0;
			$this->strUserName = "";
			$this->strPassword = "";
			$this->strNom = "";
			$this->strPrenom = "";
			$this->strEmail = "";
			$this->dtEntryDate = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbSTPIAdminUserID", "stpi_stpiadminuser_STPIAdminUser"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	
	public function stpi_chkNbNiveauID($nnbNiveauID)
	{
		if (!$this->objNiveau->stpi_chkNbID($nnbNiveauID))
		{
			return false;				
		}
		return true;
	}
	
	
	public function stpi_chkStrUserName($nstrUserName)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrUserName))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidusername") . "</span><br/>\n");
			return false;				
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
	
		
	public function stpi_setNbID($nnbID)
	{
		if (!$this->stpi_chkNbID($nnbID))
		{
			return false;				
		}
		$this->nbID = $nnbID;
		
		$SQL = "SELECT nbNiveauID, strUserName, strPassword, strNom, strPrenom, strEmail, dtEntryDate";
		$SQL .= " FROM stpi_stpiadminuser_STPIAdminUser";
		$SQL .= " WHERE nbSTPIAdminUserID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";

		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbNiveauID = $row["nbNiveauID"];
				$this->strUserName = $row["strUserName"];
				$this->strPassword = $row["strPassword"];
				$this->strNom = $row["strNom"];
				$this->strPrenom = $row["strPrenom"];
				$this->strEmail = $row["strEmail"];
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
	
	
	public function stpi_setNbNiveauID($nnbNiveauID)
	{
		if (!$this->stpi_chkNbNiveauID($nnbNiveauID))
		{
			return false;
		}
		$this->nbNiveauID = $nnbNiveauID;
		
		return true;
	}
	
	
	public function stpi_setStrUserName($nstrUserName)
	{
		if (!$this->stpi_chkStrUserName($nstrUserName))
		{
			return false;
		}
		$this->strUserName = $nstrUserName;
		
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
	
	
	public function stpi_setStrEmail($nstrEmail)
	{
		if (!$this->objEmail->stpi_chkStrEmail($nstrEmail))
		{
			return false;
		}
		$this->strEmail = $nstrEmail;
		
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
	
	
	public function stpi_getStrUserName()
	{
		return $this->strUserName;
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
	
	
	public function stpi_getStrEmail()
	{
		return $this->strEmail;
	}
	
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span>\n");
			return false;
		}
		
		if ($this->objBdd->stpi_chkExists($this->strUserName, "strUsername", "stpi_stpiadminuser_STPIAdminUser"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("usernameexists") . "</span><br/>\n");
			return false;
		}
		
		$SQL1 = "INSERT INTO stpi_stpiadminuser_STPIAdminUser";
		$SQL1 .= " (strUsername, strPassword, strNom, strPrenom, strEmail, nbNiveauID)";
		$SQL1 .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->strUserName) . "',";
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strPassword) . "',";
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "',";
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strPrenom) . "',";
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->strEmail) . "',";
		$SQL1 .= " '" . $this->objBdd->stpi_trsInputToBdd($this->nbNiveauID) . "')";
				
		if ($this->objBdd->stpi_insert($SQL1))
		{
			//Aller chercher le id du nouveau niveau
			$this->nbID = mysql_insert_id();
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("adding") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_update()
	{
		if ($this->nbID == 0)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		
		$SQL1 = "UPDATE stpi_stpiadminuser_STPIAdminUser";
		$SQL1 .= " SET strUsername = '" . $this->objBdd->stpi_trsInputToBdd($this->strUserName) . "',";
		$SQL1 .= " strNom = '" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "',";
		$SQL1 .= " strPrenom = '" . $this->objBdd->stpi_trsInputToBdd($this->strPrenom) . "',";
		$SQL1 .= " strEmail = '" . $this->objBdd->stpi_trsInputToBdd($this->strEmail) . "',";
		$SQL1 .= " nbNiveauID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbNiveauID) . "'";
		$SQL1 .= " WHERE nbSTPIAdminUserID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if (!$this->objBdd->stpi_update($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
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
		
		$SQL1 = "DELETE FROM stpi_stpiadminuser_STPIAdminUser";
		$SQL1 .= " WHERE nbSTPIadminUserID = '" .$this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
						
		if (!$this->objBdd->stpi_delete($SQL1))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		
		$this->nbID = 0;
		$this->nbNiveauID = 0;
		$this->strUserName = "";
		$this->strPassword = "";
		$this->strNom = "";
		$this->strPrenom = "";
		$this->strEmail = "";
		$this->dtEntryDate = "";
		
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
		
		$SQL1 = "UPDATE stpi_stpiadminuser_STPIAdminUser";
		$SQL1 .= " SET strPassword = '" . $this->objBdd->stpi_trsInputToBdd($this->objCryption->stpi_trsTextToCrypted($strPassword)) . "'";
		$SQL1 .= " WHERE nbSTPIAdminUserID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
		
		$SQL1 = "UPDATE stpi_stpiadminuser_STPIAdminUser";
		$SQL1 .= " SET strPassword = '" . $this->objBdd->stpi_trsInputToBdd($this->strPassword) . "'";
		$SQL1 .= " WHERE nbSTPIAdminUserID = '" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
		function stpi_SearchUsers(nstrUsername)
		{
			if (nstrUsername.length == 0)
			{ 
				document.getElementById("stpi_affUsers").innerHTML = "";
		  		return;
		  	}
		  	
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affUsers").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "stpiadminusersaff.php?strUsername=" + nstrUsername + "&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affUsers").innerHTML = xmlHttp.responseText;
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
		print("<h2>" . $this->objTexte->stpi_getArrTxt("searchtitle") . "</h2>\n");				
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("username") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchUsers(this.value)\" maxlength=\"50\" size=\"20\" id=\"strSearchUsername\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affUsers\"></span>\n");
		print("</p>\n");
	}
	
	
	public function stpi_selSearchUsername($nstrUsername)
	{
		if (!$this->stpi_chkStrUsername($nstrUsername))
		{
			return false;
		}
		
		$SQL = "SELECT nbSTPIAdminUserID";
		$SQL .= " FROM stpi_stpiadminuser_STPIAdminUser";
		$SQL .= " WHERE  strUsername LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrUsername) . "%'";
		$SQL .= " ORDER BY strUsername LIMIT 0,10";
		
		$arrID = array();
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSTPIAdminUserID"];
			}
			
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
	
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_userAdd()
		{
			xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_userAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_Adduser").style.visibility = "hidden";
		
			var strParam = "strUsername=" + encodeURIComponent(document.getElementById("strUsername").value);
			strParam = strParam + "&strPassword=" + encodeURIComponent(document.getElementById("strPassword").value);
			strParam = strParam + "&strPasswordConfirm=" + encodeURIComponent(document.getElementById("strPasswordConfirm").value);
			strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&nbNiveau=" + encodeURIComponent(document.getElementById("nbNiveau").value);
			strParam = strParam + "&strEmail=" + encodeURIComponent(document.getElementById("strEmail").value);
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./stpiadminuser.php?l=" + "<?php print(LG) ?>" + "&nbUserID=";
		  				var nbUserIDIndex = xmlHttp.responseText.indexOf("nbUserID") + 9;
		  				var nbUserIDLen = xmlHttp.responseText.length - nbUserIDIndex;
		  				var nbUserID = xmlHttp.responseText.substr(nbUserIDIndex, nbUserIDLen);
		  				strUrlRedirect = strUrlRedirect + nbUserID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Adduser").style.visibility = "visible";
			  			document.getElementById("stpi_userAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "stpiadminuseradd.php", true);
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
		print("<h2>" . $this->objTexte->stpi_getArrTxt("addtitle") . "</h2>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("username") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strUsername\" value=\"\" />\n");
		print("</p>\n");
		
		$this->objCryption->stpi_chkJsPasswordStrength();
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("password") . "<br/>\n");
		print("<input type=\"password\" onkeyup=\"stpi_chkPasswordStrength(this.value)\" maxlength=\"50\" size=\"20\" id=\"strPassword\" value=\"\" />\n");
		print("<span id=\"stpi_chkPasswordStrength\"></span><br/>\n");
		print($this->objTexte->stpi_getArrTxt("password2") . "<br/>\n");
		print("<input type=\"password\" maxlength=\"50\" size=\"20\" id=\"strPasswordConfirm\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("nom") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("prenom") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		$this->objNiveau->stpi_affJsSelectNiveauExternal();
		$this->objNiveau->stpi_affSelectNiveauExternal();	
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("email") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"255\" size=\"40\" id=\"strEmail\" value=\"\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_userAdd\"></span><br/>\n");
		print("<input id=\"stpi_Adduser\" type=\"button\" onclick=\"stpi_userAdd()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			document.getElementById("strUsername").disabled = false;
			document.getElementById("strNom").disabled = false;
			document.getElementById("strPrenom").disabled = false;
			document.getElementById("nbNiveau").disabled = false;
			document.getElementById("strEmail").disabled = false;
				
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		
		
		function stpi_userEdit()
		{
			xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			
			document.getElementById("stpi_Save").style.visibility = "hidden";
		
			var strParam = "nbUserID=" + encodeURIComponent(document.getElementById("nbUserID").value);
			strParam = strParam + "&strUsername=" + encodeURIComponent(document.getElementById("strUsername").value);
			strParam = strParam + "&strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&nbNiveau=" + encodeURIComponent(document.getElementById("nbNiveau").value);
			strParam = strParam + "&strEmail=" + encodeURIComponent(document.getElementById("strEmail").value);
			strParam = strParam + "&sid=" + Math.random();
		
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./stpiadminuser.php?l=" + "<?php print(LG); ?>" + "&nbUserID=";
		  				var nbUserIDIndex = xmlHttp.responseText.indexOf("nbUserID") + 9;
		  				var nbUserIDLen = xmlHttp.responseText.length - nbUserIDIndex;
		  				var nbUserID = xmlHttp.responseText.substr(nbUserIDIndex, nbUserIDLen);
		  				strUrlRedirect = strUrlRedirect + nbUserID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "stpiadminuseredit.php", true);
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
		
		print("<input type=\"hidden\" id=\"nbUserID\" value=\"" . $this->nbID . "\" />\n");
				
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("username") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strUsername\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strUserName) . "\" />\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("nom") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strNom) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("prenom") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strPrenom\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strPrenom) . "\" /><br/>\n");
		print("</p>\n");
		
		$this->objNiveau->stpi_affJsSelectNiveauExternal();
		$this->objNiveau->stpi_affSelectNiveauExternal(true, $this->nbNiveauID);
		
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("email") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"40\" id=\"strEmail\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strEmail) . "\" /><br/>\n");
		print("</p>\n");
		
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_userEdit()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_UserPassReset()\" value=\"" . $this->objTexte->stpi_getArrTxt("resetpassword") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_UserDel()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
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
		function stpi_UserPassReset()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "stpiadminuserpassreset.php?nbUserID=" + document.getElementById("nbUserID").value;
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
		
		
		function stpi_UserPassResetConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "stpiadminuserpassreset.php?nbUserID=" + document.getElementById("nbUserID").value;
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
		print("<input type=\"button\" onclick=\"stpi_UserPassResetConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}	
	
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_UserDel()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "stpiadminuserdel.php?nbUserID=" + document.getElementById("nbUserID").value;
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
		
		
		function stpi_UserDelConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "stpiadminuserdel.php?nbUserID=" + document.getElementById("nbUserID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./stpiadminusers.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_UserDelConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
}

?>