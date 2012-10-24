<?php
require_once(dirname(__FILE__) . "/clsmoislg.php");
class clsdate
{
	private $objBdd;
	private $objTexte;
	private $objMoisLg;
	
	private $strAnnee;
	private $strMois;
	private $strJour;
	
	public function __construct($nstrAnnee = "", $nstrMois = "", $nstrJour = "")
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtdate");
		$this->objMoisLg = new clsmoislg();
		if ($nstrAnnee == "" OR $nstrMois == "" OR $nstrJour == "")
		{
			$this->strAnnee = "";
			$this->strMois = "";
			$this->strJour = "";
		}
		else
		{
			if(!$this->stpi_setStrAnnee($nstrAnnee))
			{
				return false;
			}
			if(!$this->stpi_setStrMois($nstrMois))
			{
				return false;
			}
			if(!$this->stpi_setStrJour($nstrJour))
			{
				return false;
			}
			
		}
	}
	
	
	public function stpi_chkStrAnnee($nstrAnnee)
	{
		if (!is_numeric($nstrAnnee) OR $nstrAnnee < 1 OR $nstrAnnee > 9999)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidannee") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrMois($nstrMois)
	{
		if (!is_numeric($nstrMois) OR $nstrMois < 1 OR $nstrMois > 12)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmois") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkStrJour($nstrJour, $nstrMois = 0, $nstrAnnee = 0)
	{
		if (!$this->stpi_chkStrAnnee($nstrAnnee))
		{
			return false;
		}
		if (!$this->stpi_chkStrMois($nstrMois))
		{
			return false;
		}
		if (!is_numeric($nstrJour) OR $nstrJour < 1 OR $nstrJour > date('t', mktime(0, 0, 0, $nstrMois, 1, $nstrAnnee)))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidjour") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_chkHeureISO($nstrHeure)
	{
		list($h, $m, $s) = explode(":", $nstrHeure);
		
		if (!is_numeric($h) OR $h < 0 OR $h > 23)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidheure") . "</span><br/>\n");
			return false;
		}
		if (!is_numeric($m) OR $m < 0 OR $m > 59)
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidminute") . "</span><br/>\n");
			return false;
		}
		if (!is_numeric($s) OR $s < 0 OR $s > 59)
		{
			$s = "00";
		}
		return true;
	}
	
	
	public function stpi_chkDateISO($nstrDate)
	{
		list($a, $m, $j) = explode("-", $nstrDate);
		if (!$this->stpi_chkStrJour($j, $m, $a))
		{
			return false;
		}
		return true;
	}
	
	
	public function stpi_setStrAnnee($nstrAnnee)
	{
		if (!$this->stpi_chkStrAnnee($nstrAnnee))
		{
			return false;
		}
		$this->strAnnee = $nstrAnnee;
		return true;
	}
	
	public function stpi_setStrMois($nstrMois)
	{
		if (!$this->stpi_chkStrMois($nstrMois))
		{
			return false;
		}
		$this->strMois = $nstrMois;
		return true;
	}
	
	public function stpi_setStrJour($nstrJour)
	{
		if (!$this->stpi_chkStrJour($nstrJour, $this->strMois, $this->strAnnee))
		{
			return false;
		}
		$this->strJour = $nstrJour;
		return true;
	}
	
	public function stpi_setObjMoisLgFromBdd()
	{
		if (!$nbMoisLgId = $this->objMoisLg->stpi_selNbMoisIDLG())
		{
			return false;
		}
		if (!$this->objMoisLg->stpi_setNbID($nbMoisLgId))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_getStrAnnee()
	{
		return $this->strAnnee;
	}
	
	public function stpi_getStrMois()
	{
		/*if ($this->strMois < 10)
		{
			return "0" . $this->strMois;
		}*/
		return $this->strMois;
	}
	
	public function stpi_getStrJour()
	{
		return $this->strJour;
	}
	
	public function stpi_getStrDateISO()
	{
		return $this->stpi_getStrAnnee() . "-" . $this->stpi_getStrMois() . "-" . $this->stpi_getStrJour();
	}
	
	public function stpi_getObjMoisLg()
	{
		return $this->objMoisLg;
	}
	
	public function stpi_trsDateISOtoTexte($nstrDate)
	{
		if (!$this->stpi_chkDateISO($nstrDate))
		{
			return false;
		}
		list($a, $m, $j) = explode("-", $nstrDate);
		if ($this->stpi_getObjMoisLg()->stpi_setNbMoisID($m))
		{
			if ($nbMoisLgID = $this->stpi_getObjMoisLg()->stpi_selNbMoisIDLG())
			{
				if ($this->stpi_getObjMoisLg()->stpi_setNbID($nbMoisLgID))
				{
					return $j . " " . $this->stpi_getObjMoisLg()->stpi_getStrName() . " " . $a;
				}
			}
		}
		return false;
	}
	
	public function stpi_selAllMois()
	{
		$arrID = array();
		$SQL = "SELECT DISTINCT nbMoisID FROM stpi_date_Mois_Lg ORDER BY nbMoisID";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["nbMoisID"];
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