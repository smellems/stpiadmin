<?php
require_once(dirname(__FILE__) . "/clstypeadresse.php");
require_once(dirname(__FILE__) . "/../area/clscountry.php");
class clsadresse
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objTypeAdresse;
	private $objCountry;
	
	private $nbCommandeID;
	private $nbTypeAdresseID;
	private $strNom;
	private $strPrenom;
	private $strCie;
	private $strAdresse;
	private $strVille;
	private $strCountryID;
	private $strProvinceID;
	private $strCodePostal;
	
	public function __construct($nnbCommandeID = 0, $nnbTypeAdresseID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtadresse");
		$this->objLang = new clslang();
		$this->objTypeAdresse = new clstypeadresse();
		$this->objCountry = new clscountry();
		if ($nnbCommandeID == 0 OR $nnbTypeAdresseID == 0)
		{
			$this->nbCommandeID = 0;
			$this->nbTypeAdresseID = 0;
			$this->strNom = "";
			$this->strPrenom = "";
			$this->strCie = "";
			$this->strAdresse = "";
			$this->strVille = "";
			$this->strCountryID = "";
			$this->strProvinceID = "";
			$this->strCodePostal = "";
		}
		else
		{
			if(!$this->stpi_setNbID($nnbCommandeID, $nnbTypeAdresseID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkNbCommandeID($nnbCommandeID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbCommandeID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcommandeid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbCommandeID, "nbCommandeID", "stpi_commande_Commande"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcommandeid") . "&nbsp;(!chkExists)</span><br/>\n");
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
		if (!$this->objBdd->stpi_chkInputToBdd($nstrCie) && $nstrCie != "")
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcie") . "</span><br/>\n");
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
	
	public function stpi_setNbID($nnbCommandeID, $nnbTypeAdresseID)
	{
		if (!$this->stpi_setNbCommandeID($nnbCommandeID))
		{
			return false;				
		}
		if (!$this->stpi_setNbTypeAdresseID($nnbTypeAdresseID))
		{
			return false;				
		}

		$SQL = "SELECT strNom, strPrenom, strCie, strAdresse, strVille, strCountryID, strProvinceID, strCodePostal";
		$SQL .= " FROM stpi_commande_Adresse WHERE nbCommandeID=" . $this->nbCommandeID . " AND nbTypeAdresseID=" . $this->nbTypeAdresseID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->strNom = $row["strNom"];
				$this->strPrenom = $row["strPrenom"];
				$this->strCie = $row["strCie"];
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
	
	public function stpi_setNbCommandeID($nnbCommandeID)
	{
		if (!$this->stpi_chkNbCommandeID($nnbCommandeID))
		{
			return false;
		}
		$this->nbCommandeID = $nnbCommandeID;
		return true;
	}
	
	public function stpi_setNbTypeAdresseID($nnbTypeAdresseID)
	{
		if (!$this->objTypeAdresse->stpi_chkNbID($nnbTypeAdresseID))
		{
			return false;
		}
		$this->nbTypeAdresseID = $nnbTypeAdresseID;
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
		if ($nstrProvinceID != "" AND !$this->objCountry->stpi_getObjProvince()->stpi_chkStrProvinceID($nstrProvinceID))
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
	
	public function stpi_getNbCommandeID()
	{
		return $this->nbCommandeID;
	}
	
	public function stpi_getNbTypeAdresseID()
	{
		return $this->nbTypeAdresseID;
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
	
	public function stpi_getObjTypeAdresse()
	{
		return $this->objTypeAdresse;
	}
	
	public function stpi_getObjCountry()
	{
		return $this->objCountry;
	}
	
	
	public function stpi_insert()
	{
		if ($this->nbCommandeID == 0 OR $this->nbTypeAdresseID == 0)
		{
			return false;
		}		
		$SQL = "INSERT INTO stpi_commande_Adresse (nbCommandeID, nbTypeAdresseID, strNom, strPrenom, strCie, strAdresse, strVille, strProvinceID, strCountryID, strCodePostal)";
		$SQL .= " VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->nbCommandeID) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeAdresseID) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strPrenom) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strCie) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strAdresse) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strVille) . "'";
		if ($this->strProvinceID == "")
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
		if ($this->nbCommandeID == 0 OR $this->nbTypeAdresseID == 0)
		{
			return false;
		}
		$SQL = "UPDATE stpi_commande_Adresse";
		$SQL .= " SET strNom='" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "'";
		$SQL .= ", strPrenom='" . $this->objBdd->stpi_trsInputToBdd($this->strPrenom) . "'";
		$SQL .= ", strCie='" . $this->objBdd->stpi_trsInputToBdd($this->strCie) . "'";
		$SQL .= ", strAdresse='" . $this->objBdd->stpi_trsInputToBdd($this->strAdresse) . "'";
		$SQL .= ", strVille='" . $this->objBdd->stpi_trsInputToBdd($this->strVille) . "'";
		if ($this->strProvinceID == "")
		{
			$SQL .= ", strProvinceID=NULL";
		}
		else
		{
			$SQL .= ", strProvinceID='" . $this->objBdd->stpi_trsInputToBdd($this->strProvinceID) . "'";
		}
		$SQL .= ", strCountryID='" . $this->objBdd->stpi_trsInputToBdd($this->strCountryID) . "'";
		$SQL .= ", strCodePostal='" . $this->objBdd->stpi_trsInputToBdd($this->strCodePostal) . "'";
		$SQL .= " WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($this->nbCommandeID);
		$SQL .= " AND nbTypeAdresseID=" . $this->objBdd->stpi_trsInputToBdd($this->nbTypeAdresseID);
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
	
	public function stpi_deleteCommandeID($nnbCommandeID)
	{
		if (!$this->stpi_chkNbCommandeID($nnbCommandeID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_Adresse WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($nnbCommandeID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "&nbsp;(nbCommandeID)</span><br/>\n");
			return false;
		}
	}
	
	public function stpi_delete($nnbCommandeID, $nnbTypeAdresseID)
	{
		if (!$this->stpi_chkNbCommandeID($nnbCommandeID))
		{
			return false;				
		}
		if (!$this->objTypeAdresse->stpi_chkNbID($nnbTypeAdresseID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_Adresse WHERE nbCommandeID=" . $this->objBdd->stpi_trsInputToBdd($nnbCommandeID) . " AND nbTypeAdresseID=" . $this->objBdd->stpi_trsInputToBdd($nnbTypeAdresseID);
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
	
	
	public function stpi_affJsAdresseFacturationToCommande()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_AddAdresseFacturationToCommande()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_AddAdresseFacturationToCommande").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddAdresseFacturationToCommandeButton").style.visibility = "hidden";
			var strParam = "strCourriel=" + encodeURIComponent(document.getElementById("strCourriel").value);
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
				strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
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
		  				var strUrlRedirect = "./checkout3.php?l=" + "<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddAdresseFacturationToCommandeButton").style.visibility = "visible";
			  			document.getElementById("stpi_AddAdresseFacturationToCommande").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "commandeadressefacturationadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsAdresseFacturationToCommandeRegistre()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_AddAdresseFacturationToCommandeRegistre()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_AddAdresseFacturationToCommandeRegistre").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddAdresseFacturationToCommandeRegistreButton").style.visibility = "hidden";
			var strParam = "strCourriel=" + encodeURIComponent(document.getElementById("strCourriel").value);
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
				strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
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
		  				var strUrlRedirect = "./checkoutregistre3.php?l=" + "<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddAdresseFacturationToCommandeRegistreButton").style.visibility = "visible";
			  			document.getElementById("stpi_AddAdresseFacturationToCommandeRegistre").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "commanderegistreadressefacturationadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsAdresseLivraisonToCommande()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_AddAdresseLivraisonToCommande()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_AddAdresseLivraisonToCommande").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddAdresseLivraisonToCommandeButton").style.visibility = "hidden";
			
			var strParam = "strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			if (document.getElementById("stpi_InStorePickup").checked)
			{
				strParam = strParam + "&boolInStorePickup=1";
			}
			if (document.getElementById("stpi_DeliverTo").checked)
			{
				strParam = strParam + "&boolInStorePickup=0";
			}
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&strCie=" + encodeURIComponent(document.getElementById("strCie").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			try
			{
				strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
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
		  				var strUrlRedirect = "./checkout4.php?l=" + "<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddAdresseLivraisonToCommandeButton").style.visibility = "visible";
			  			document.getElementById("stpi_AddAdresseLivraisonToCommande").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "commandeadresselivraisonadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		
		
		function stpi_DeliverTo()
		{
			document.getElementById("strNom").disabled = false;
			document.getElementById("strPrenom").disabled = false;
			document.getElementById("strCie").disabled = false;
			document.getElementById("strAdresse").disabled = false;
			document.getElementById("strVille").disabled = false;
			document.getElementById("strCountryID").disabled = false;
			document.getElementById("strCodePostal").disabled = false;
			try
			{
				document.getElementById("strProvinceID").disabled = false;
			}
			catch(e)
			{
				return;
			}
		}
		
		
		function stpi_InStorePickup()
		{
			document.getElementById("strNom").disabled = true;
			document.getElementById("strPrenom").disabled = true;
			document.getElementById("strCie").disabled = true;
			document.getElementById("strAdresse").disabled = true;
			document.getElementById("strVille").disabled = true;
			document.getElementById("strCountryID").disabled = true;
			document.getElementById("strCodePostal").disabled = true;
			try
			{
				document.getElementById("strProvinceID").disabled = true;
			}
			catch(e)
			{
				return;
			}
		}
		-->
		<?php
		print("</script>\n");
	}
	
	
	public function stpi_affJsAdresseLivraisonToCommandeRegistre()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_AddAdresseLivraisonToCommandeRegistre()
		{
			xmlHttp = stpi_XmlHttpObject();
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_AddAdresseLivraisonToCommandeRegistre").innerHTML = strErrXmlHttpObject;
		  		return;
			}
			document.getElementById("stpi_AddAdresseLivraisonToCommandeRegistreButton").style.visibility = "hidden";
			
			var strParam = "strNom=" + encodeURIComponent(document.getElementById("strNom").value);
			if (document.getElementById("stpi_DeliverTo").checked)
			{
				strParam = strParam + "&nbDeliveryID=0";
			}
			if (document.getElementById("stpi_InStorePickup").checked)
			{
				strParam = strParam + "&nbDeliveryID=1";
			}
			if (document.getElementById("stpi_DeliverToGiftListOwner").checked)
			{
				strParam = strParam + "&nbDeliveryID=2";
			}
			strParam = strParam + "&strPrenom=" + encodeURIComponent(document.getElementById("strPrenom").value);
			strParam = strParam + "&strCie=" + encodeURIComponent(document.getElementById("strCie").value);
			strParam = strParam + "&strAdresse=" + encodeURIComponent(document.getElementById("strAdresse").value);
			strParam = strParam + "&strVille=" + encodeURIComponent(document.getElementById("strVille").value);
			strParam = strParam + "&strCountryID=" + encodeURIComponent(document.getElementById("strCountryID").value);
			strParam = strParam + "&strCodePostal=" + encodeURIComponent(document.getElementById("strCodePostal").value);
			try
			{
				strParam = strParam + "&strProvinceID=" + encodeURIComponent(document.getElementById("strProvinceID").value);
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
		  				var strUrlRedirect = "./checkoutregistre4.php?l=" + "<?php print(LG) ?>";
		  				window.location = strUrlRedirect;
		  			}
		  			else
		  			{
			  			document.getElementById("stpi_AddAdresseLivraisonToCommandeRegistreButton").style.visibility = "visible";
			  			document.getElementById("stpi_AddAdresseLivraisonToCommandeRegistre").innerHTML = xmlHttp.responseText;
			  		}
				}
			}
			xmlHttp.open("POST", "commanderegistreadresselivraisonadd.php", true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlHttp.setRequestHeader("Content-length", strParam.length);
		    xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(strParam);
		}
		
		
		function stpi_DeliverTo()
		{
			document.getElementById("strNom").disabled = false;
			document.getElementById("strPrenom").disabled = false;
			document.getElementById("strCie").disabled = false;
			document.getElementById("strAdresse").disabled = false;
			document.getElementById("strVille").disabled = false;
			document.getElementById("strCountryID").disabled = false;
			document.getElementById("strCodePostal").disabled = false;
			try
			{
				document.getElementById("strProvinceID").disabled = false;
			}
			catch(e)
			{
				return;
			}
		}
		
		
		function stpi_InStorePickup()
		{
			document.getElementById("strNom").disabled = true;
			document.getElementById("strPrenom").disabled = true;
			document.getElementById("strCie").disabled = true;
			document.getElementById("strAdresse").disabled = true;
			document.getElementById("strVille").disabled = true;
			document.getElementById("strCountryID").disabled = true;
			document.getElementById("strCodePostal").disabled = true;
			try
			{
				document.getElementById("strProvinceID").disabled = true;
			}
			catch(e)
			{
				return;
			}
		}
		
		
		function stpi_DeliverToGiftListOwner()
		{
			document.getElementById("strNom").disabled = true;
			document.getElementById("strPrenom").disabled = true;
			document.getElementById("strCie").disabled = true;
			document.getElementById("strAdresse").disabled = true;
			document.getElementById("strVille").disabled = true;
			document.getElementById("strCountryID").disabled = true;
			document.getElementById("strCodePostal").disabled = true;
			try
			{
				document.getElementById("strProvinceID").disabled = true;
			}
			catch(e)
			{
				return;
			}
		}
		-->
		<?php
		print("</script>\n");
	}
}
?>