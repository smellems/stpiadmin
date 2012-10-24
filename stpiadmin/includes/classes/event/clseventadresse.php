<?php
require_once(dirname(__FILE__) . "/../area/clscountry.php");
class clseventadresse
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objCountry;
	
	private $nbID;
	private $strEndroit;
	private $strAdresse;
	private $strVille;
	private $strCountryID;
	private $strProvinceID;
	private $strCodePostal;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txteventadresse");
		$this->objLang = new clslang();
		$this->objCountry = new clscountry();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->strEndroit = "";
			$this->strAdresse = "";
			$this->strVille = "";
			$this->strCountryID = "";
			$this->strProvinceID = "";
			$this->strCodePostal = "";
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
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbAdresseID", "stpi_event_Adresse"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "&nbsp;(!chkExists)</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrEndroit($nstrEndroit)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrEndroit))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidendroit") . "</span><br/>\n");
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
		if (!$this->objBdd->stpi_chkInputToBdd($nstrCodePostal))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcodepostal") . "</span><br/>\n");
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
		
		$SQL = "SELECT strEndroit, strAdresse, strVille, strCountryID, strProvinceID, strCodePostal";
		$SQL .= " FROM stpi_event_Adresse WHERE nbAdresseID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->strEndroit = $row["strEndroit"];
				$this->strAdresse = $row["strAdresse"];
				$this->strVille = $row["strVille"];
				$this->strCountryID = $row["strCountryID"];
				$this->strProvinceID = $row["strProvinceID"];
				$this->strCodePostal = $row["strCodePostal"];
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
	
	public function stpi_setStrEndroit($nstrEndroit)
	{
		if (!$this->stpi_chkStrEndroit($nstrEndroit))
		{
			return false;				
		}
		$this->strEndroit = $nstrEndroit;
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
		if ($nstrProvinceID != "isNULL" AND !$this->objCountry->stpi_getObjProvince()->stpi_chkStrProvinceID($nstrProvinceID))
		{
			return false;				
		}
		$this->strProvinceID = $nstrProvinceID;
		return true;
	}
	
	public function stpi_setStrCodePostal($nstrCodePostal)
	{
		$nstrCodePostal = $this->objBdd->stpi_trsCodePostalToBdd($nstrCodePostal);
		if (!$this->stpi_chkStrCodePostal($nstrCodePostal))
		{
			return false;				
		}
		$this->strCodePostal = $nstrCodePostal;
		return true;
	}	
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getStrEndroit()
	{
		return $this->strEndroit;
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
	
	public function stpi_getObjCountry()
	{
		return $this->objCountry;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}		
		$SQL = "INSERT INTO stpi_event_Adresse (strEndroit, strAdresse, strVille, strProvinceID, strCountryID, strCodePostal)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->strEndroit) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strAdresse) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strVille) . "'";
		if ($this->strProvinceID == "isNULL")
		{
			$SQL .= ", NULL";
		}
		else
		{
			$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
		}
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strCodePostal) . "')";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			return $this->nbID;
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
			return false;
		}
		$SQL = "UPDATE stpi_event_Adresse";
		$SQL .= " SET strEndroit='" . $this->objBdd->stpi_trsInputToBdd($this->strEndroit) . "'";
		$SQL .= ", strAdresse='" . $this->objBdd->stpi_trsInputToBdd($this->strAdresse) . "'";
		$SQL .= ", strVille='" . $this->objBdd->stpi_trsInputToBdd($this->strVille) . "'";
		if ($this->strProvinceID == "isNULL")
		{
			$SQL .= ", strProvinceID=NULL";
		}
		else
		{
			$SQL .= ", strProvinceID='" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
		}
		$SQL .= ", strCountryID='" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
		$SQL .= ", strCodePostal='" . $this->objBdd->stpi_trsInputToBdd($this->strCodePostal) . "'";
		$SQL .= " WHERE nbAdresseID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		if ($this->objBdd->stpi_update($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_delete($nnbID)
	{
		if (!$this->stpi_chkNbID($nnbID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_event_Adresse WHERE nbAdresseID=" . $this->objBdd->stpi_trsInputToBdd($nnbID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_affJsSearch()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_SearchAdresse(nstrText)
		{
			if (nstrText.length == 0)
			{ 
				document.getElementById("stpi_affAdresse").innerHTML = "";
		  		return;
		  	}
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_affAdresse").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventadressesaff.php?strAdresse=" + nstrText + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_affAdresse").innerHTML = xmlHttp.responseText;
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
		print($this->objTexte->stpi_getArrTxt("adresse") . "<br/>\n");
		print("<input type=\"text\" onkeyup=\"stpi_SearchAdresse(this.value)\" maxlength=\"50\" size=\"20\" value=\"\" /><br/>\n");
		print("<span id=\"stpi_affAdresse\"></span>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsAdd()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addAdresse()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_AdresseAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddAdresse").style.visibility = "hidden";
			var strParam = "strEndroit=" + encodeURIComponent(document.getElementById("strEndroit").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./eventadresse.php?l=" + "<?php print(LG) ?>" + "&nbAdresseID=";
		  				var nbAdresseIDIndex = xmlHttp.responseText.indexOf("nbAdresseID") + 12;
		  				var nbAdresseIDLen = xmlHttp.responseText.length - nbAdresseIDIndex;
		  				var nbAdresseID = xmlHttp.responseText.substr(nbAdresseIDIndex, nbAdresseIDLen);
		  				strUrlRedirect = strUrlRedirect + nbAdresseID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddAdresse").style.visibility = "visible";
			  			document.getElementById("stpi_AdresseAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "eventadresseadd.php", true);
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
		print("<p>\n");
		print($this->objTexte->stpi_getArrTxt("endroit") . "<br/>\n");
		print("<input type=\"text\" maxlength=\"255\" size=\"30\" id=\"strEndroit\" value=\"\" /><br/>\n");
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
		print("<span id=\"stpi_AdresseAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddAdresse\" type=\"button\" onclick=\"stpi_addAdresse()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsEdit()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable()
		{
			document.getElementById("strEndroit").disabled = false;
			document.getElementById("strAdresse").disabled = false;
			document.getElementById("strVille").disabled = false;
			document.getElementById("strProvinceID").disabled = false;
			document.getElementById("strCountryID").disabled = false;
			document.getElementById("strCodePostal").disabled = false;
		}
		function stpi_editAdresse()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject[stpi_chkLang()];
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbAdresseID=" + encodeURIComponent(document.getElementById("nbAdresseID").value);
			strParam = strParam + "&strEndroit=" + encodeURIComponent(document.getElementById("strEndroit").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./eventadresse.php?l=" + "<?php print(LG) ?>" + "&nbAdresseID=";
		  				var nbAdresseIDIndex = xmlHttp.responseText.indexOf("nbAdresseID") + 12;
		  				var nbAdresseIDLen = xmlHttp.responseText.length - nbAdresseIDIndex;
		  				var nbAdresseID = xmlHttp.responseText.substr(nbAdresseIDIndex, nbAdresseIDLen);
		  				strUrlRedirect = strUrlRedirect + nbAdresseID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "eventadresseedit.php", true);
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
		print("<input type=\"hidden\" id=\"nbAdresseID\" value=\"" . $this->nbID . "\" />\n");
		
		print("<p>\n");	
		print($this->objTexte->stpi_getArrTxt("endroit") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"30\" id=\"strEndroit\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strEndroit) . "\" /><br/>\n");

		print($this->objTexte->stpi_getArrTxt("adresse") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"255\" size=\"55\" id=\"strAdresse\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strAdresse) . "\" /><br/>\n");

		print($this->objTexte->stpi_getArrTxt("ville") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"50\" size=\"20\" id=\"strVille\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strVille) . "\" /><br/>\n");

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
		print("</select><br/>\n");

		print($this->objTexte->stpi_getArrTxt("codepostal") . "<br/>\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"7\" size=\"7\" id=\"strCodePostal\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->strCodePostal) . "\" /><br/>\n");
		print("</p>\n");

		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editAdresse()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delAdresse()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}
	
	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delAdresse()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventadressedel.php?nbAdresseID=" + document.getElementById("nbAdresseID").value;
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
		function stpi_delAdresseConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "eventadressedel.php?nbAdresseID=" + document.getElementById("nbAdresseID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./events.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delAdresseConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}
	
	public function stpi_affNoDelete()
	{
		print("<b>" . $this->objTexte->stpi_getArrTxt("nodelete") . "</b><br/>\n");
	}
	
	public function stpi_selSearchAdresse($nstrAdresse)
	{
		if (!$this->stpi_chkStrEndroit($nstrAdresse))
		{
			return false;
		}
		$SQL = "SELECT nbAdresseID";
		$SQL .= " FROM stpi_event_Adresse";
		$SQL .= " WHERE  strEndroit LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrAdresse) . "%'";
		$SQL .= " OR  strAdresse LIKE '%" . $this->objBdd->stpi_trsInputToBdd($nstrAdresse) . "%'";
		$SQL .= " ORDER BY strEndroit LIMIT 0,20";
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbAdresseID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}	
		return $arrID;
	}
	
	public function stpi_selAll()
	{
		$arrID = array();
		$SQL = "SELECT nbAdresseID FROM stpi_event_Adresse";
		$SQL .= " ORDER BY strEndroit, strVille";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbAdresseID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selNbEventID()
	{
		$arrID = array();
		$SQL = "SELECT nbEventID FROM stpi_event_Event";
		$SQL .= " WHERE nbAdresseID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbEventID"];
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