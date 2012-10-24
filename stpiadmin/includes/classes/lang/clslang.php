<?php

class clslang
{
	private $objBdd;
	private $arrLang;
	private $strLg;
	private $strDefaultLg;
	
	private $strID;
	private $strLang;
	private $strSalut;
	private $boolDefault;
	
	
	public function __construct($nstrID = "")
	{		
		$this->objBdd = clsbdd::singleton();
		if ($nstrID == "")
		{
			$this->strID = "";
			$this->strLang = "";
			$this->strSalut = "";
			$this->boolDefault = 0;
		}
		else
		{
			if(!$this->stpi_setStrID($nstrID))
			{
				return false;
			}
		}
	}
	
	public function stpi_chkStrID($nstrID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nstrID))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nstrID, "strLangID", "stpi_lang"))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("noexists") . "</span><br/>\n");
			return false;
		}
		return true;
	}
	
	public function stpi_setStrID($nstrID)
	{
		if (!$this->stpi_chkStrID($nstrID))
		{
			return false;				
		}
		$this->strID = $nstrID;
		
		$SQL = "SELECT strLang, strSalut, boolDefault";
		$SQL .= " FROM stpi_lang";
		$SQL .= " WHERE strLangID = '" . $this->objBdd->stpi_trsInputToBdd($this->strID) . "'";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if($row = mysql_fetch_assoc($result))
			{
				$this->strLang = $row["strLang"];
				$this->strSalut = $row["strSalut"];
				$this->boolDefault = $row["boolDefault"];
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
	
	public function stpi_getStrID()
	{
		return $this->strID;
	}
	
	public function stpi_getStrLang()
	{
		return $this->strLang;
	}
	
	public function stpi_getStrSalut()
	{
		return $this->strSalut;
	}
	
	
	public function stpi_setArrLang()
	{
		$SQL = "SELECT strLangID, boolDefault";
		$SQL .= " FROM stpi_lang";
		
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$this->arrLang = array();
			while($row = mysql_fetch_assoc($result))
			{
				$this->arrLang[] = $row["strLangID"];
				if ($row["boolDefault"] == 1)
				{
					$this->strDefaultLg = $row["strLangID"];
				}
			}
		}
	}

	public function stpi_setStrLg()
	{
		if (in_array($_GET["l"], $this->arrLang))
		{
			$this->strLg = $_GET["l"];
			setcookie("stpiLang", $_GET["l"], time() + 60 * 60 * 24 * 30 * 12, "/");
		}
		elseif (in_array($_COOKIE["stpiLang"], $this->arrLang))
		{
			$this->strLg = $_COOKIE["stpiLang"];
			$_GET["l"] = $_COOKIE["stpiLang"];
		}
		else
		{
			setcookie("stpiLang", $this->strDefaultLg, time() + 60 * 60 * 24 * 30 * 12, "/");
			$_GET["l"] = $this->strDefaultLg;
			$this->strLg = $this->strDefaultLg;
		}
	}
	
	public function stpi_run()
	{
		$this->stpi_setArrLang();
		$this->stpi_setStrLg();
	}
	
	public function stpi_getArrLang()
	{
		return $this->arrLang;
	}
	
	public function stpi_getStrDefaultLg()
	{
		return $this->strDefaultLg;
	}

	public function stpi_getStrLg()
	{
		return $this->strLg;
	}
	
	
	
	
	
	
	
	
	
	public function stpi_selAll()
	{
		$SQL = "SELECT strLangID";
		$SQL .= " FROM stpi_lang";
		$SQL .= " ORDER BY strLang";
		
		$arrID = array();
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$arrID[] = $row["strLangID"];
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