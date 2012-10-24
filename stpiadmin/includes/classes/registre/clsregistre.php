<?php
require_once(dirname(__FILE__) . "/../client/clsclient.php");
require_once(dirname(__FILE__) . "/../date/clsdate.php");
require_once(dirname(__FILE__) . "/clsregistresousitem.php");
class clsregistre
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objClient;
	private $objDate;
	private $objEmail;
	private $objRegistreSousItem;
	//private $objRegistreSession;
	
	private $nbID;
	private $nbClientID;
	private $strMessage;
	private $strLangID;
	private $strRegistreCode;
	private $dtEntryDate;
	private $dtFin;
	private $boolActif;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtregistre");
		$this->objLang = new clslang();
		$this->objClient = new clsclient();
		$this->objDate = new clsdate();
		$this->objRegistreSousItem = new clsregistresousitem();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->nbClientID = 0;
			$this->strMessage = "";
			$this->strLangID = "";
			$this->strRegistreCode = "";
			$this->dtEntryDate = "";
			$this->dtfin = "";
			$this->boolActif = 0;
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbRegistreID", "stpi_registre_Registre"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrMessage($nstrMessage)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrMessage))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmessage") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	
	
	public function stpi_chkStrRegistreCode($nstrRegistreCode)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrRegistreCode))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidregistrecode") . "</span><br/>\n");
			return false;				
		}
		if ($this->objBdd->stpi_chkExists($nstrRegistreCode, "strRegistreCode", "stpi_registre_Registre"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidregistrecode") . "&nbsp;(chkExists())</span><br/>\n");
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
	
	public function stpi_chkdtFin($ndtFin)
	{
		if (!$this->objDate->stpi_chkDateISO($ndtFin))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddatefin") . "</span><br/>\n");
			return false;				
		}
		
		if ($ndtFin < date("Y-m-d", time()-(30 * 24 * 60 * 60)))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("expired") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkBoolActif($nboolActif)
	{
		if ($nboolActif != 0 AND $nboolActif != 1)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidboolactif") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	

	public function stpi_chkIfActif()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
		
		if (!$this->boolActif)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("pasactif") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	

	public function stpi_chkIfNotExpired()
	{
		if ($this->nbID == 0)
		{
			return false;
		}
				
		if ($this->dtFin < date("Y-m-d", time()-(30 * 24 * 60 * 60)))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("expired") . "</span><br/>\n");
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
		
		$SQL = "SELECT nbClientID, strRegistreCode, strMessage, strLg, dtEntryDate, dtFin, boolActif";
		$SQL .= " FROM stpi_registre_Registre WHERE nbRegistreID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->nbClientID = $row["nbClientID"];
				$this->strRegistreCode = $row["strRegistreCode"];
				$this->strMessage = $row["strMessage"];
				$this->strLangID = $row["strLg"];
				$this->dtFin = $row["dtFin"];
				$this->dtEntryDate = $row["dtEntryDate"];
				
				if ($this->dtFin < date("Y-m-d", time()-(30 * 24 * 60 * 60)) AND $row["boolActif"] == 1)
				{
					$this->boolActif = 0;
					$this->stpi_update();
				}
				else
				{
					$this->boolActif = $row["boolActif"];
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
	
	public function stpi_setNbClientID($nnbClientID)
	{
		if ($nnbClientID != 0 AND !$this->objClient->stpi_chkNbID($nnbClientID))
		{
			return false;				
		}
		$this->nbClientID = $nnbClientID;
		return true;
	}
	
	public function stpi_setStrRegistreCode($nstrRegistreCode)
	{
		if (!$this->stpi_chkStrRegistreCode($nstrRegistreCode))
		{
			return false;				
		}
		$this->strRegistreCode = $nstrRegistreCode;
		return true;
	}
	
	public function stpi_setStrMessage($nstrMessage)
	{
		if (!$this->stpi_chkStrMessage($nstrMessage))
		{
			return false;				
		}
		$this->strMessage = $nstrMessage;
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
	
	public function stpi_setDtFin($ndtFin)
	{
		if (!$this->stpi_chkDtFin($ndtFin))
		{
			return false;				
		}
		$this->dtFin = $ndtFin;
		return true;
	}
	
	public function stpi_setBoolActif($nboolactif)
	{
		if (!$this->stpi_chkBoolActif($nboolactif))
		{
			return false;				
		}
		$this->boolActif = $nboolactif;
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getNbClientID()
	{
		return $this->nbClientID;
	}
	public function stpi_getStrMessage()
	{
		return $this->strMessage;
	}
	public function stpi_getStrLangID()
	{
		return $this->strLangID;
	}
	public function stpi_getStrRegistreCode()
	{
		return $this->strRegistreCode;
	}
	public function stpi_getDtEntryDate()
	{
		return $this->dtEntryDate;
	}
	public function stpi_getDtFin()
	{
		return $this->dtFin;
	}
	public function stpi_getBoolActif()
	{
		return $this->boolActif;
	}
	
	public function stpi_getRandomRegistreCode()
	{
		$strChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$strChars = str_shuffle($strChars);
   		$strRegistreCode = substr(str_shuffle($strChars), 0, 10);
   		
   		if ($this->objBdd->stpi_chkExists($strRegistreCode, "strRegistreCode", "stpi_registre_Registre"))
   		{
   			return stpi_getRandomRegistreCode();
   		}
   		return $strRegistreCode;
	}
	
	public function stpi_getObjClient()
	{
		return $this->objClient;
	}
	public function stpi_getObjDate()
	{
		return $this->objDate;
	}

	public function stpi_getObjRegistreSousItem()
	{
		return $this->objRegistreSousItem;
	}
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		if (!$this->stpi_setStrRegistreCode($this->stpi_getRandomRegistreCode()))
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_registre_Registre (nbClientID, strRegistreCode, strMessage, strLg, dtEntryDate, dtFin, boolActif)";
		$SQL .= " VALUES (" . $this->objBdd->stpi_trsInputToBdd($this->nbClientID);
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strRegistreCode) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strMessage) . "'";
		$SQL .= ", '" . LG . "'";
		$SQL .= ", NOW()";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->dtFin) . "'";
		$SQL .= ", " . $this->objBdd->stpi_trsInputToBdd($this->boolActif) . ")";
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

		$SQL = "UPDATE stpi_registre_Registre";
		$SQL .= " SET nbClientID=" . $this->objBdd->stpi_trsInputToBdd($this->nbClientID);
		$SQL .= ", strMessage='" . $this->objBdd->stpi_trsInputToBdd($this->strMessage) . "'";
		$SQL .= ", strRegistreCode='" . $this->objBdd->stpi_trsInputToBdd($this->strRegistreCode) . "'";
		$SQL .= ", strLg='" . $this->objBdd->stpi_trsInputToBdd($this->strLangID) . "'";
		$SQL .= ", dtFin='" . $this->objBdd->stpi_trsInputToBdd($this->dtFin) . "'";
		$SQL .= ", boolActif='" . $this->objBdd->stpi_trsInputToBdd($this->boolActif) . "'";
		$SQL .= " WHERE nbRegistreID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		if (!$this->objBdd->stpi_update($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("editing") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_delete($nnbRegistreID)
	{
		if (!$this->stpi_chkNbID($nnbRegistreID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_registre_Registre WHERE nbRegistreID=" . $this->objBdd->stpi_trsInputToBdd($nnbRegistreID);
		if (!$this->objBdd->stpi_delete($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_affJsSendRegistreInvitationPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_sendRegistreInvitation()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_SendRegistreInvitation").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_buttonSendRegistreInvitation").style.visibility = "hidden";
			var strParam = "strEmails=" + encodeURIComponent(document.getElementById("strEmails").value);
			strParam = strParam + "&nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value);
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			document.getElementById("stpi_buttonSendRegistreInvitation").style.visibility = "visible";
			  		document.getElementById("stpi_SendRegistreInvitation").innerHTML = xmlHttp.responseText;
			  	}
			}
			xmlHttp.open("POST", "registresendinvitationpublic.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affSendRegistreInvitationPublic()
	{
		print("<h2>" . $this->objTexte->stpi_getArrTxt("invitefriend") . "</h2>\n");
		
		print("<table width=\"100%\" >\n");
		
		print("<tr>\n");
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("emails") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<textarea rows=\"5\" cols=\"21\" id=\"strEmails\"></textarea><br/>\n");
		print("</td>\n");
		print("</td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<p>" . $this->objTexte->stpi_getArrTxt("emailsinfo") . ".</p>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td colspan=\"3\" style=\"text-align: right; vertical-align: top;\" >\n");
		print("<span id=\"stpi_SendRegistreInvitation\"></span><br/>\n");
		print("<input id=\"stpi_buttonSendRegistreInvitation\" type=\"button\" onclick=\"stpi_sendRegistreInvitation()\" value=\"" . $this->objTexte->stpi_getArrTxt("send") . "\"/><br/><br/>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("</table>\n");
	}	
	
	public function stpi_affJsAddPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_addRegistre()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_RegistreAdd").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddRegistre").style.visibility = "hidden";
			var strParam = "strMessage=" + encodeURIComponent(document.getElementById("strMessage").value);
			strParam = strParam + "&dtFinJour=" + encodeURIComponent(document.getElementById("dtFinJour").value);
			strParam = strParam + "&dtFinMois=" + encodeURIComponent(document.getElementById("dtFinMois").value);
			strParam = strParam + "&dtFinAnnee=" + encodeURIComponent(document.getElementById("dtFinAnnee").value);
			if (document.getElementById("boolActif").checked)
			{
				var strParam = strParam + "&boolActif=1";
			}
			else
			{
				var strParam = strParam + "&boolActif=0";
			}
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./registrepublic.php?l=" + "<?php print(LG) ?>" + "&nbRegistreID=";
		  				var nbRegistreIDIndex = xmlHttp.responseText.indexOf("nbRegistreID") + 13;
		  				var nbRegistreIDLen = xmlHttp.responseText.length - nbRegistreIDIndex;
		  				var nbRegistreID = xmlHttp.responseText.substr(nbRegistreIDIndex, nbRegistreIDLen);
		  				strUrlRedirect = strUrlRedirect + nbRegistreID;
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddRegistre").style.visibility = "visible";
			  			document.getElementById("stpi_RegistreAdd").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "registreaddpublic.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	public function stpi_affAddPublic()
	{
		print("<table width=\"100%\" >\n");
		
		print("<tr>\n");
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("message") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<textarea rows=\"10\" cols=\"21\" id=\"strMessage\"></textarea><br/>\n");
		print("</td>\n");
		print("</td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<p>" . $this->objTexte->stpi_getArrTxt("messageinfo") . ".</p>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>");		
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("datefin") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"2\" size=\"1\" id=\"dtFinJour\" value=\"" . date("d") . "\" />");
		print(" - ");
		print("<select id=\"dtFinMois\">\n");
		if ($arrNbMoisID = $this->objDate->stpi_selAllMois())
		{
			foreach($arrNbMoisID as $nbMoisID)
			{
				if ($this->objDate->stpi_getObjMoisLg()->stpi_setNbID($nbMoisID))
				{
					if ($this->objDate->stpi_setObjMoisLgFromBdd())
					{
						print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbMoisID) . "\"");
						if ($nbMoisID == date("m"))
						{
							print(" selected=\"selected\"");
						}
						print(">");
						print($this->objBdd->stpi_trsBddToHTML($this->objDate->stpi_getObjMoisLg()->stpi_getStrName()));
						print("</option>\n");
					}
				}
			}
		}
		print("</select>\n");
		print(" - ");
		print("<input type=\"text\" maxlength=\"4\" size=\"2\" id=\"dtFinAnnee\" value=\"" . date("Y") . "\" />\n");
		print("</td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<p>" . $this->objTexte->stpi_getArrTxt("datefininfo") . ".</p>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("actif") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"checkbox\" id=\"boolActif\" value=\"\" /><br/>\n");
		print("</td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<p>" . $this->objTexte->stpi_getArrTxt("actifinfo") . ".</p>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td colspan=\"3\" style=\"text-align: right; vertical-align: top;\" >\n");
		print("<span id=\"stpi_RegistreAdd\"></span><br/>\n");
		print("<input id=\"stpi_AddRegistre\" type=\"button\" onclick=\"stpi_addRegistre()\" value=\"" . $this->objTexte->stpi_getArrTxt("add") . "\"/><br/><br/>\n");
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
			document.getElementById("strMessage").disabled = false;
			document.getElementById("strLangID").disabled = false;
			document.getElementById("dtFinJour").disabled = false;
			document.getElementById("dtFinMois").disabled = false;
			document.getElementById("dtFinAnnee").disabled = false;
			document.getElementById("boolActif").disabled = false;
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editRegistre()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value);
			strParam = strParam + "&strMessage=" + encodeURIComponent(document.getElementById("strMessage").value);
			strParam = strParam + "&strLangID=" + encodeURIComponent(document.getElementById("strLangID").value);
			strParam = strParam + "&dtFinJour=" + encodeURIComponent(document.getElementById("dtFinJour").value);
			strParam = strParam + "&dtFinMois=" + encodeURIComponent(document.getElementById("dtFinMois").value);
			strParam = strParam + "&dtFinAnnee=" + encodeURIComponent(document.getElementById("dtFinAnnee").value);
			if (document.getElementById("boolActif").checked)
			{
				var strParam = strParam + "&boolActif=1";
			}
			else
			{
				var strParam = strParam + "&boolActif=0";
			}
			
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./registre.php?l=" + "<?php print(LG) ?>" + "&nbRegistreID=";
		  				var nbRegistreIDIndex = xmlHttp.responseText.indexOf("nbRegistreID") + 13;
		  				var nbRegistreIDLen = xmlHttp.responseText.length - nbRegistreIDIndex;
		  				var nbRegistreID = xmlHttp.responseText.substr(nbRegistreIDIndex, nbRegistreIDLen);
		  				strUrlRedirect = strUrlRedirect + nbRegistreID;
		  				window.location = strUrlRedirect;
		  			}
					else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "registreedit.php", true);
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
		
		print("<input type=\"hidden\" id=\"nbRegistreID\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" />\n");

		print("<p>" . $this->objTexte->stpi_getArrTxt("registre") . " : <b>" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "</b><br/>" . $this->objBdd->stpi_trsBddToHTML($this->dtEntryDate) . "<br/>");
		print($this->objTexte->stpi_getArrTxt("registrecode") . " : " . $this->objBdd->stpi_trsBddToHTML($this->stpi_getStrRegistreCode()) . "<br/>");
		if ($this->objClient->stpi_setNbID($this->nbClientID))
		{
			print($this->objTexte->stpi_getArrTxt("client") . ": <a href=\"./client.php?l=" . LG . "&amp;nbClientID=" . $this->objBdd->stpi_trsBddToHTML($this->nbClientID) . "\">");
			print($this->objBdd->stpi_trsBddToHTML($this->nbClientID) . "</a>");
		}
		print("</p>\n");

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
		
		print("<p>");
		print($this->objTexte->stpi_getArrTxt("message") . " :<br />");
		print("<textarea disabled=\"disabled\" rows=\"4\" cols=\"40\" id=\"strMessage\">" . $this->objBdd->stpi_trsBddToHTML($this->strMessage) . "</textarea>");
		print("</p>\n");
		
		list($a, $m, $j) = explode("-", $this->dtFin);
		print("<p>");		
		print($this->objTexte->stpi_getArrTxt("datefin") . " : <input disabled=\"disabled\" type=\"text\" maxlength=\"2\" size=\"1\" id=\"dtFinJour\" value=\"" . $this->objBdd->stpi_trsBddToHTML($j) . "\"/>");
		print(" - ");
		print("<select disabled=\"disabled\" id=\"dtFinMois\">\n");
		if ($arrNbMoisID = $this->objDate->stpi_selAllMois())
		{
			foreach($arrNbMoisID as $nbMoisID)
			{
				if ($this->objDate->stpi_getObjMoisLg()->stpi_setNbID($nbMoisID))
				{
					if ($this->objDate->stpi_setObjMoisLgFromBdd())
					{
						print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbMoisID) . "\"");
						if ($nbMoisID == $m)
						{
							print(" selected=\"selected\"");
						}
						print(">");
						print($this->objBdd->stpi_trsBddToHTML($this->objDate->stpi_getObjMoisLg()->stpi_getStrName()));
						print("</option>\n");
					}
				}
			}
		}
		print("</select>\n");
		print(" - ");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"4\" size=\"3\" id=\"dtFinAnnee\" value=\"" . $this->objBdd->stpi_trsBddToHTML($a) . "\"/>\n");
		print("</p>\n");
		
		print("<p>" . $this->objTexte->stpi_getArrTxt("actif") . " : <input disabled=\"disabled\" type=\"checkbox\" id=\"boolActif\" value=\"\"");
		if ($this->boolActif)
		{
			print(" checked=\"checked\"");
		}
		print("/></p>\n");
						
		print("<p>\n");
		print("<span id=\"stpi_messages\"></span><br/>\n");
		print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable()\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
		print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editRegistre()\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_delRegistre()\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" /><br/>\n");
		print("</p>\n");
	}

	public function stpi_affJsDelete()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_delRegistre()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "registredel.php?nbRegistreID=" + document.getElementById("nbRegistreID").value;
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
		function stpi_delRegistreConfirmed()
		{
		  	xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
			var strUrl = "registredel.php?nbRegistreID=" + document.getElementById("nbRegistreID").value;
			strUrl = strUrl + "&nbConfirmed=1&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				window.location = "./registresaff.php?l=" + "<?php print(LG); ?>";
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
		print("<input type=\"button\" onclick=\"stpi_delRegistreConfirmed()\" value=\"" . $this->objTexte->stpi_getArrTxt("yes") . "\" />&nbsp;&nbsp;\n");
		print("<input type=\"button\" onclick=\"stpi_ClearMessage()\" value=\"" . $this->objTexte->stpi_getArrTxt("no") . "\" /><br/>\n");
	}

	
	public function stpi_affJsEditPublic()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_ChangeToEditable(narrSousItem)
		{
			document.getElementById("strMessage").disabled = false;
			document.getElementById("dtFinJour").disabled = false;
			document.getElementById("dtFinMois").disabled = false;
			document.getElementById("dtFinAnnee").disabled = false;
			document.getElementById("boolActif").disabled = false;
			for (i in narrSousItem)
			{
				if (narrSousItem[i] != 0)
				{
					document.getElementById("nbQteVoulu" + narrSousItem[i]).disabled = false;
					document.getElementById("stpi_delSousItemFromRegistre" + narrSousItem[i]).disabled = false;
				}
			}
			document.getElementById("stpi_Edit").style.display = "none";
			document.getElementById("stpi_Save").style.display = "";
		}
		function stpi_editRegistre(narrSousItem)
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_messages").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_Save").style.visibility = "hidden";
			var strParam = "nbRegistreID=" + encodeURIComponent(document.getElementById("nbRegistreID").value);
			strParam = strParam + "&strMessage=" + encodeURIComponent(document.getElementById("strMessage").value);
			strParam = strParam + "&dtFinJour=" + encodeURIComponent(document.getElementById("dtFinJour").value);
			strParam = strParam + "&dtFinMois=" + encodeURIComponent(document.getElementById("dtFinMois").value);
			strParam = strParam + "&dtFinAnnee=" + encodeURIComponent(document.getElementById("dtFinAnnee").value);
			if (document.getElementById("boolActif").checked)
			{
				var strParam = strParam + "&boolActif=1";
			}
			else
			{
				var strParam = strParam + "&boolActif=0";
			}
			
			for (i in narrSousItem)
			{
				if (narrSousItem[i] != 0)
				{
					strParam = strParam + "&nbQteVoulu" + narrSousItem[i] + "=" + document.getElementById("nbQteVoulu" + narrSousItem[i]).value;
				}
			}
			
			strParam = strParam + "&sid=" + Math.random();
			xmlHttp.onreadystatechange=function()
			{
				if (xmlHttp.readyState == 4)
		  		{
		  			if (xmlHttp.responseText.indexOf("redirect") != -1)
		  			{
		  				var strUrlRedirect = "./registrepublic.php?l=" + "<?php print(LG) ?>" + "&nbRegistreID=";
		  				var nbRegistreIDIndex = xmlHttp.responseText.indexOf("nbRegistreID") + 13;
		  				var nbRegistreIDLen = xmlHttp.responseText.length - nbRegistreIDIndex;
		  				var nbRegistreID = xmlHttp.responseText.substr(nbRegistreIDIndex, nbRegistreIDLen);
		  				strUrlRedirect = strUrlRedirect + nbRegistreID;
		  				window.location = strUrlRedirect;
		  			}
					else
		  			{
			  			document.getElementById("stpi_Save").style.visibility = "visible";
			  			document.getElementById("stpi_messages").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			
			xmlHttp.open("POST", "registreeditpublic.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}	
	
	public function stpi_affEditPublic()
	{
		if ($this->nbID == 0)
		{
			return false;				
		}
		
		print("<input type=\"hidden\" id=\"nbRegistreID\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . "\" />\n");
		
		print("<table width=\"100%\" >\n");
		print("<tr>\n");
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("registrecode") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" ><b>" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getStrRegistreCode()) . "</b></td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("&nbsp;\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("message") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<textarea disabled=\"disabled\" rows=\"10\" cols=\"21\" id=\"strMessage\">" . $this->objBdd->stpi_trsBddToHTML($this->strMessage) . "</textarea><br/>\n");
		print("</td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<p>" . $this->objTexte->stpi_getArrTxt("messageinfo") . ".</p>\n");
		print("</td>\n");
		print("</tr>\n");
		
		list($a, $m, $j) = explode("-", $this->dtFin);
		print("<tr>");		
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("datefin") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"2\" size=\"1\" id=\"dtFinJour\" value=\"" . $this->objBdd->stpi_trsBddToHTML($j) . "\"/>");
		print(" - ");
		print("<select disabled=\"disabled\" id=\"dtFinMois\">\n");
		if ($arrNbMoisID = $this->objDate->stpi_selAllMois())
		{
			foreach($arrNbMoisID as $nbMoisID)
			{
				if ($this->objDate->stpi_getObjMoisLg()->stpi_setNbID($nbMoisID))
				{
					if ($this->objDate->stpi_setObjMoisLgFromBdd())
					{
						print("<option value=\"" . $this->objBdd->stpi_trsBddToHTML($nbMoisID) . "\"");
						if ($nbMoisID == $m)
						{
							print(" selected=\"selected\"");
						}
						print(">");
						print($this->objBdd->stpi_trsBddToHTML($this->objDate->stpi_getObjMoisLg()->stpi_getStrName()));
						print("</option>\n");
					}
				}
			}
		}
		print("</select>\n");
		print(" - ");
		print("<input disabled=\"disabled\" type=\"text\" maxlength=\"4\" size=\"2\" id=\"dtFinAnnee\" value=\"" . $this->objBdd->stpi_trsBddToHTML($a) . "\"/>\n");
		print("</td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<p>" . $this->objTexte->stpi_getArrTxt("datefininfo") . ".</p>\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>\n");
		print("<td width=\"20%\" style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("actif") . " :\n");
		print("</td>\n");
		print("<td width=\"30%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input disabled=\"disabled\" type=\"checkbox\" id=\"boolActif\" value=\"\"");
		if ($this->boolActif)
		{
			print(" checked=\"checked\"");
		}
		print("/><br/>\n");
		print("</td>\n");
		print("<td width=\"40%\" style=\"text-align: left; vertical-align: top;\" >\n");
		print("<p>" . $this->objTexte->stpi_getArrTxt("actifinfo") . ".</p>\n");
		print("</td>\n");
		print("</tr>\n");
		print("</table>\n");
		
		
		print("<h2>\n");
		print($this->objTexte->stpi_getArrTxt("registreitems"));
		print("</h2>\n");
		print("<p><b>" . $this->objTexte->stpi_getArrTxt("additem") . " <a href=\"./shop.php?l=" . LG . "\">" . $this->objTexte->stpi_getArrTxt("pageshop") . "</a></b></p>\n");
		if ($arrNbSousItemID = $this->stpi_selNbSousItemID())
		{
			print("<table width=\"100%\" style=\"padding: 10px;\" >\n");
			print("<tr>\n");
			print("<td style=\"text-align: left;\" >\n");
			print("<h3 style=\"padding: 0px;\" >\n");
			print($this->objTexte->stpi_getArrTxt("desc"));
			print("</h3>\n");
			print("</td>\n");
			print("<td style=\"text-align: left;\" >\n");
			print("<h3 style=\"padding: 0px;\" >\n");
			print($this->stpi_getObjRegistreSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qtevoulu"));
			print("</h3>\n");
			print("</td>\n");
			print("<td style=\"text-align: left;\" >\n");
			print("<h3 style=\"padding: 0px;\" >\n");
			print($this->stpi_getObjRegistreSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qterecu"));
			print("</h3>\n");
			print("</td>\n");
			print("<td>\n");
			print("&nbsp;");
			print("</td>\n");
			print("</tr>\n");
			$ajsSousItem .= "";
			foreach ($arrNbSousItemID as $nbSousItemID)
			{
				if ($this->stpi_getObjRegistreSousItem()->stpi_setNbID($this->nbID, $nbSousItemID))
				{
					if ($this->stpi_getObjRegistreSousItem()->stpi_getObjItem()->stpi_getObjSousItem()->stpi_setNbID($nbSousItemID))
					{
						$ajsSousItem  .= "," . $this->objBdd->stpi_trsBddToHTML($nbSousItemID);
						print("<tr><td style=\"text-align: left;\" >\n");
						$strItemCode = $this->stpi_getObjRegistreSousItem()->stpi_getStrItemCode();
						if ($strItemCode != "")
						{
							print($this->objBdd->stpi_trsBddToHTML($strItemCode) . " - ");
							
						}
						print($this->objBdd->stpi_trsBddToHTML($this->stpi_getObjRegistreSousItem()->stpi_getStrSousItemDesc()));
						print("</td>\n");
						print("<td><input disabled=\"disabled\" type=\"text\" maxlength=\"4\" size=\"3\" id=\"nbQteVoulu" . $this->objBdd->stpi_trsBddToHTML($nbSousItemID) . "\" value=\"" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getObjRegistreSousItem()->stpi_getNbQteVoulu()) . "\" /></td>");
						print("<td>" . $this->objBdd->stpi_trsBddToHTML($this->stpi_getObjRegistreSousItem()->stpi_getNbQteRecu()) . "</td>\n");
						print("<td style=\"text-align: right;\" >\n");
						print("<input disabled=\"disabled\" type=\"button\" id=\"stpi_delSousItemFromRegistre" . $this->objBdd->stpi_trsBddToHTML($nbSousItemID). "\" onclick=\"stpi_delSousItemFromRegistre(" . $this->objBdd->stpi_trsBddToHTML($this->nbID) . ", " . $this->objBdd->stpi_trsBddToHTML($nbSousItemID) . ")\" value=\"" . $this->objTexte->stpi_getArrTxt("delete") . "\" />\n");
						print("</td>\n");
						print("</tr>\n");
					}
				}										
			}
			print("<tr>\n");
			print("<td colspan=\"4\" style=\"text-align: right; vertical-align: top;\" >\n");
			if ($this->stpi_chkIfNotExpired())
			{
				
				print("<span id=\"stpi_messages\"></span><br/>\n");
				print("<input type=\"button\" id=\"stpi_Edit\" onclick=\"stpi_ChangeToEditable(Array(0" . $ajsSousItem . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("edit") . "\" />&nbsp;&nbsp;\n");
				print("<input style=\"display: none;\" type=\"button\" id=\"stpi_Save\" onclick=\"stpi_editRegistre(Array(0" . $ajsSousItem . "))\" value=\"" . $this->objTexte->stpi_getArrTxt("save") . "\" /><br/><br/>\n");
			}
			print("</td>\n");
			print("</tr>\n");
			print("</table>\n");
		}
	}
	
	
	
	public function stpi_selAll($nnbLimit = 0, $nstrAnneeD = 0, $nstrMoisD = 0, $nstrJourD = 0, $nstrAnneeF = 0, $nstrMoisF = 0, $nstrJourF = 0)
	{
		$arrID = array();
		$SQL = "SELECT nbRegistreID FROM stpi_registre_Registre WHERE 1=1";
		if ($nstrJourD != 0)
		{
			$SQL .= " AND DAYOFMONTH(dtEntryDate)>=" . $nstrJourD;
		}
		if ($nstrJourF != 0)
		{
			$SQL .= " AND DAYOFMONTH(dtEntryDate)<=" . $nstrJourF;
		}
		if ($nstrMoisD != 0)
		{
			$SQL .= " AND MONTH(dtEntryDate)>=" . $nstrMoisD;
		}
		if ($nstrMoisF != 0)
		{
			$SQL .= " AND MONTH(dtEntryDate)<=" . $nstrMoisF;
		}
		if ($nstrAnneeD != 0)
		{
			$SQL .= " AND YEAR(dtEntryDate)>=" . $nstrAnneeD;
		}
		if ($nstrAnneeF != 0)
		{
			$SQL .= " AND YEAR(dtEntryDate)<=" . $nstrAnneeF;
		}
		if ($nnbLimit != 0)
		{
			 $SQL .= " LIMIT 0, " . $nnbLimit;
		}
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbRegistreID"];
			}
			mysql_free_result($result);
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("none") . "</span><br/>\n");
			return false;
		}
		return $arrID;
	}
	
	public function stpi_selAllMois()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT MONTH(dtEntryDate) as MoisID, YEAR(dtEntryDate) as Annee";
		$SQL .= " FROM stpi_registre_Registre";
		$SQL .= " ORDER BY Annee, MoisID";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["MoisID"] . "-" . $row["Annee"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		
		return $arrID;
	}
	
	public function stpi_selNbSousItemID()
	{
		$arrID = array();
		$SQL = "SELECT nbSousItemID FROM stpi_registre_Registre_SousItem WHERE nbRegistreID=" . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSousItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selNbSousItemIDPublic()
	{
		$arrID = array();
		
		$SQL = "SELECT stpi_registre_Registre_SousItem.nbSousItemID";
		$SQL .= " FROM stpi_registre_Registre_SousItem, stpi_item_SousItem, stpi_item_Item_DispItem";
		$SQL .= " WHERE stpi_registre_Registre_SousItem.nbRegistreID = " . $this->objBdd->stpi_trsInputToBdd($this->nbID);
		$SQL .= " AND stpi_registre_Registre_SousItem.nbQteRecu < stpi_registre_Registre_SousItem.nbQteVoulu";
		$SQL .= " AND stpi_registre_Registre_SousItem.nbSousItemID = stpi_item_SousItem.nbSousItemID";
		$SQL .= " AND stpi_item_SousItem.nbQte > 0";
		$SQL .= " AND stpi_item_SousItem.nbItemID = stpi_item_Item_DispItem.nbItemID";
		$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID = 2";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbSousItemID"];
			}
			mysql_free_result($result);
		}
		else
		{
			return false;
		}
		return $arrID;
	}
	
	
	public function stpi_selNbIDFromStrRegistreCode($nstrRegistreCode = "")
	{
		$nbID = 0;
		
		if ($nstrRegistreCode == "")
		{
			return false;
		}
		
		$SQL = "SELECT nbRegistreID FROM stpi_registre_Registre WHERE strRegistreCode = '" . $this->objBdd->stpi_trsInputToBdd($nstrRegistreCode) . "'";
				
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$nbID = $row["nbRegistreID"];
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidregistrecode") . "</span><br/>\n");
				return false;
			}
			mysql_free_result($result);
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidregistrecode") . "</span><br/>\n");
			return false;
		}
		
		return $nbID;
	}		
}
?>
