<?php

class clstexte
{
	private $arrTxt;
	private $arrErrTxt;
	
	public function __construct($nstrFile)
	{
		if (!file_exists($nstrFile . LG . ".php"))
		{
			print("Text doesn't exist.");
			exit;
		}
		
		//Le fichier existe alors on l'inclu
		include($nstrFile . LG . ".php");		
	}
	
	public function stpi_getArrTxt($nstrKey = "optional")
	{
		if (!isset($this->arrTxt))
		{
			return false;
		}
		else
		{
			if ($nstrKey == "optional")
			{
				return $this->arrTxt;
			}
			else
			{
				if (!array_key_exists($nstrKey, $this->arrTxt))
				{
					return false;
				}
				else
				{
					return $this->arrTxt[$nstrKey];
				}
			}			
		}				
	}
	
	
	public function stpi_getArrErrTxt($nstrKey = "optional")
	{
		if (!isset($this->arrErrTxt))
		{
			return false;
		}
		else
		{
			if ($nstrKey == "optional")
			{
				return $this->arrErrTxt;
			}
			else
			{
				if (!array_key_exists($nstrKey, $this->arrErrTxt))
				{
					return false;
				}
				else
				{
					return $this->arrErrTxt[$nstrKey];
				}
			}			
		}				
	}
}

?>