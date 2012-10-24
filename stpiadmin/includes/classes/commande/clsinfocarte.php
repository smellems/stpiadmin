<?php
require_once(dirname(__FILE__) . "/../date/clsdate.php");
require_once(dirname(__FILE__) . "/../security/clscryption.php");

class clsinfocarte
{
	private $objBdd;
	private $objTexte;
	private $objLang;
	private $objDate;
	private $objCryption;
	
	private $nbID;
	private $strNom;
	private $strNum;
	private $dtDateExpir;
	private $strCodeSecur;
	private $dtEntryDate;
	
	public function __construct($nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtinfocarte");
		$this->objLang = new clslang();
		$this->objDate = new clsdate();
		$this->objCryption = new clscryption();
		if ($nnbID == 0)
		{
			$this->nbID = 0;
			$this->strNom = "";
			$this->strNum = "";
			$this->dtDateExpir = "";
			$this->strCodeSecur = "";
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
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbInfoCarteID", "stpi_commande_InfoCarte"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
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
	
	public function stpi_chkStrNum($nstrNum)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrNum))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidnum") . "</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkdtDateExpir($ndtDateExpir)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($ndtDateExpir))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddateexpir") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objDate->stpi_chkDateISO($ndtDateExpir))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invaliddateexpir") . "&nbsp;(!chkDate)</span><br/>\n");
			return false;				
		}
		return true;
	}
	
	public function stpi_chkStrCodeSecur($nstrCodeSecur)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrCodeSecur) && !empty($nstrCodeSecur))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidcodesecur") . "</span><br/>\n");
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

		$SQL = "SELECT strNom, strNum, dtDateExpir, strCodeSecur, dtEntryDate FROM stpi_commande_InfoCarte WHERE nbInfoCarteID=" . $this->nbID;
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->strNom = $row["strNom"];
				$this->strNum = $row["strNum"];
				$this->dtDateExpir = $row["dtDateExpir"];
				$this->strCodeSecur = $row["strCodeSecur"];
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
	
	public function stpi_setStrNom($nstrNom)
	{
		if (!$this->stpi_chkStrNom($nstrNom))
		{
			return false;				
		}
		$this->strNom = $nstrNom;
		return true;
	}
	
	public function stpi_setStrNum($nstrNum)
	{
		$nstrNum = trim($nstrNum);
		$i = strlen($nstrNum);
		$nstrNewNum = "";
		for ($x = 0; $x < $i; $x++)
		{
			if (preg_match("/[0-9]/", substr($nstrNum, $x ,1)))
			{
				$nstrNewNum .= substr($nstrNum, $x ,1);
			}
		}
		if (!$this->stpi_chkStrNum($nstrNewNum))
		{
			return false;		
		}
		$this->strNum = $this->objCryption->stpi_trsTextToEnCrypted($nstrNewNum);
		return true;
	}
	
	public function stpi_setDtDateExpir($ndtDateExpir)
	{
		if (!$this->stpi_chkDtDateExpir($ndtDateExpir))
		{
			return false;				
		}
		$this->dtDateExpir = $ndtDateExpir;
		return true;
	}
	
	public function stpi_setStrCodeSecur($nstrCodeSecur)
	{
		if (!$this->stpi_chkStrCodeSecur($nstrCodeSecur))
		{
			return false;				
		}
		$this->strCodeSecur = $nstrCodeSecur;
		return true;
	}
	
	public function stpi_getNbID()
	{
		return $this->nbID;
	}
	
	public function stpi_getStrNom()
	{
		return $this->strNom;
	}
	
	public function stpi_getStrNum()
	{
		return $this->objCryption->stpi_trsEnCryptedToText($this->strNum);
	}
	
	public function stpi_getDtDateExpir()
	{
		return $this->dtDateExpir;
	}
	
	public function stpi_getStrCodeSecur()
	{
		return $this->strCodeSecur;
	}
	
	public function stpi_getDtEntryDate()
	{
		return $this->dtEntryDate;
	}
	
	public function stpi_insert()
	{
		if ($this->nbID != 0)
		{
			return false;
		}
		
		$SQL = "INSERT INTO stpi_commande_InfoCarte (nbInfoCarteID, strNom, strNum, dtDateExpir, strCodeSecur, dtEntryDate) VALUES (NULL";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strNum) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->dtDateExpir) . "'";
		$SQL .= ", '" . $this->objBdd->stpi_trsInputToBdd($this->strCodeSecur) . "'";
		$SQL .= ", NOW())";
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
		
		$SQL = "UPDATE stpi_commande_InfoCarte";
		$SQL .= " SET strNom='" . $this->objBdd->stpi_trsInputToBdd($this->strNom) . "'";
		$SQL .= ", strNum='" . $this->objBdd->stpi_trsInputToBdd($this->strNum) . "'";
		$SQL .= ", dtDateExpir='" . $this->objBdd->stpi_trsInputToBdd($this->dtDateExpir) . "'";
		$SQL .= ", strCodeSecur='" . $this->objBdd->stpi_trsInputToBdd($this->strCodeSecur) . "'";
		$SQL .= " WHERE nbInfoCarteID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
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
	
	public function stpi_delete($nnbInfoCarteID)
	{
		if (!$this->stpi_chkNbID($nnbInfoCarteID))
		{
			return false;				
		}
		$SQL = "DELETE FROM stpi_commande_InfoCarte WHERE nbInfoCarteID=" . $this->objBdd->stpi_trsInputToBdd($nnbInfoCarteID);
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
	
	
	public function stpi_affAddPublic()
	{
		print("<h4 style=\"padding: 10px;\" >" . $this->objTexte->stpi_getArrTxt("infocarte") . "</h4>\n");
		
		print("<table>\n");
		
		print("<tr>");		
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("nom") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"50\" size=\"20\" id=\"strNom\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>");		
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("num") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"20\" size=\"15\" id=\"strNum\" />\n");
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>");		
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("dateexpir") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<input type=\"text\" maxlength=\"2\" size=\"1\" id=\"dtDateExpir1\" />");
		print(" - ");
		print("<input type=\"text\" maxlength=\"4\" size=\"3\" id=\"dtDateExpir2\" />");
		print(" " . $this->objTexte->stpi_getArrTxt("dateexpirmasque"));
		print("</td>\n");
		print("</tr>\n");
		
		print("<tr>");		
		print("<td style=\"text-align: right; vertical-align: top;\" >\n");
		print($this->objTexte->stpi_getArrTxt("codesecur") . " :\n");
		print("</td>\n");
		print("<td style=\"text-align: left; vertical-align: top;\" >\n");
		print("<img alt=\"securitycode\" src=\"./images/securitycode.jpg\" /><br/>\n");
		print("<input type=\"text\" maxlength=\"3\" size=\"4\" id=\"strCodeSecur\" />\n");
		print("</td>\n");
		print("</tr>\n");
	}
}
?>