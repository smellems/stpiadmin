<?php

class clscryption
{
	private $objBdd;
	private $objTexte;
	
	public function __construct()
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtsecurity");
	}
	
	
	public function stpi_getObjTexte()
	{
		return $this->objTexte;
	}
	
	
	//Fonction qui transforme un texte pour qu'il soit encrypté
	//$nstrTexte : Le texte à encrypter
	//return : Retourne le text encrypté
	public function stpi_trsTextToCrypted($nstrTexte)
	{
		return md5($nstrTexte . "IfYourReadingThisYourProbablyALoser");
	}
		
	
	//Fonction qui transforme un texte pour qu'il soit encrypté
	//$nstrTexte : Le texte à encrypter
	//return : Retourne le text encrypté ou false en cas d'erreur
	public function stpi_trsTextToEnCrypted($nstrTexte)
	{
		$SQL = "SELECT AES_ENCRYPT('" . $this->objBdd->stpi_trsInputToBdd($nstrTexte) . "IfYourReadingThisYourProbablyALoser', 'FU=3xE_a4ef*') AS CryptedShit";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$strCryptedShit = $row["CryptedShit"];
			}			
			mysql_free_result($result);			
		}
		else
		{
			return false;
		} 
		return $strCryptedShit;
	}
	
	
	//Fonction qui transforme un texte encrypté en texte
	//$nstrTexte : Le texte à encrypter
	//return : Retourne le text ou false en cas d'erreur
	public function stpi_trsEnCryptedToText($nstrTexteEncrypt)
	{
		$SQL = "SELECT AES_DECRYPT('" . $this->objBdd->stpi_trsInputToBdd($nstrTexteEncrypt) . "', 'FU=3xE_a4ef*') AS DecryptedShit";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				$strTexte = $row["DecryptedShit"];
			}			
			mysql_free_result($result);			
		}
		else
		{
			return false;
		}
		
		$strTexte = str_replace("IfYourReadingThisYourProbablyALoser", "", $strTexte);
		
		return $strTexte;
	}
	
	///// fonction devrait être dans classe stpiadminuser avec le nom: stpi_affJsChkPasswordStrength()
	public function stpi_chkJsPasswordStrength()
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
			
			//Si le browser est invalide afficher l'erreur
			if (xmlHttp == null)
		  	{
		  		document.getElementById("stpi_chkPasswordStrength").innerHTML = strErrXmlHttpObject;
		  		return;
			} 
		
			var strUrl = "stpiadminuserchkpassstrength.php?strPassword=" + nstrPassword + "&sid=" + Math.random();
			
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
	
	
	public function stpi_chkPasswordStrength($nstrPassword)
	{
		//Analyse du mot de passe
		$strPasswordLen = strlen($nstrPassword);
		$nbSymbols = 0;
		$nbLetters = 0;
		$nbDigits = 0;
		$i = 0;
		while ($i < $strPasswordLen)
		{
			$nbChar = ord($nstrPassword{$i});
			
			if ($nbChar >= 48 && $nbChar <= 57)
			{
				$nbDigits++;
			}
			else if (($nbChar >= 65 && $nbChar <= 90) || ($nbChar >= 97 && $nbChar <= 122))
			{
				$nbLetters++;
			}
			else if (($nbChar >= 32 && $nbChar <= 47) ||
					 ($nbChar >= 58 && $nbChar <= 64) ||
					 ($nbChar >= 91 && $nbChar <= 96) ||
					 ($nbChar >= 123 && $nbChar <= 126))
			{
				$nbSymbols++;
			}
			
			$i++;
		}
		
		//La force du password
		$nbScore = $nbDigits * 8;
		$nbScore += $nbLetters * 9;
		$nbScore += $nbSymbols * 14;
		
		if ($nbScore < 70)
		{
			return false;
		}
		else if ($nbScore >= 70)
		{
			return $nbScore;
		}
	}
	
	
	public function stpi_selPasswordGenerator()
	{
		$strPassword = "";	
	
		//Liste des charactère utilisé pour créer le mot de passe
		$strChars = "0123456789";
		$strChars .= "abcdefghijklmnopqrstuvwxyz";
		$strChars .= strtoupper("abcdefghijklmnopqrstuvwxyz");
		$strChars .= "!/$%?&*#";
		
		//Longueur du mot de passe
		$nbPassLen = 10;
		
		for ($i=0; $i < $nbPassLen; $i++)
		{
			$strPassword .= $strChars[rand(0, strlen($strChars))]; 
		}
		
		return $strPassword;
	}
}

?>