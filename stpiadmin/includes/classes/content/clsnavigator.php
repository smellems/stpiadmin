<?php

class clsnavigator
{
	private $nbRecordsPerPage = 6;
	private $nbMaxPagesShown = 7;
	
	private $nbCurrentStartPage;
	private $nbCurrentEndPage;
	private $nbCurrentPage;
	private $nbTotalPages;
	private $nbTotalRecords;
	
	private $arrAllNbID;
	private $arrNbID;
	
	public function __construct($narrAllNbID = 0, $nnbCurrentPage = 1, $nnbRecordsPerPage = 0) 
	{
		$this->arrAllNbID = array();
		$this->arrNbID = array();
		
		$this->nbCurrentStartPage = 0;
		$this->nbCurrentEndPage = 0;
		$this->nbTotalPages = 0;
		$this->nbTotalRecords = 0;
		
		$this->nbCurrentPage = $nnbCurrentPage;

		if ($narrAllNbID != 0)
		{
			$this->arrAllNbID = $narrAllNbID;
		}
		if ($nnbRecordsPerPage != 0)
		{
			$this->nbRecordsPerPage = $nnbRecordsPerPage;
		}
	}
	
	
	public function stpi_setArrAllNbID($narrAllNbID)
	{
		$this->arrAllNbID = $narrAllNbID;
	}
	
	
	public function stpi_setNbCurrentPage($nnbCurrentPage)
	{
		$this->nbCurrentPage = $nnbCurrentPage;
	}
	
	
	public function stpi_getArrAllNbID()
	{
		return $this->arrAllNbID;
	}
	
	
	public function stpi_getArrNbID()
	{
		return $this->arrNbID;
	}
	
	
	public function stpi_setAllVariables()
	{
		if (empty($this->arrAllNbID))
		{
			return false;
		}
		
		$this->nbTotalRecords = count($this->arrAllNbID);
		$this->nbTotalPages = intval($this->nbTotalRecords / $this->nbRecordsPerPage);
		
		if ($this->nbTotalRecords % $this->nbRecordsPerPage != 0)
		{
			$this->nbTotalPages++;
		}
		
		if ($this->nbCurrentPage < 1 || $this->nbCurrentPage > $this->nbTotalPages)
		{
			$this->nbCurrentPage = 1;
		}
		
		if ($this->nbMaxPagesShown > $this->nbTotalPages)
	    {
	    	$this->nbMaxPagesShown = $this->nbTotalPages;
	    }
	    
	    $nbPageGauche = 0;
	    $nbPageDroite = 0;
	    
	    if ($this->nbMaxPagesShown % 2 == 0)
	    {
	    	$nbPageGauche = $this->nbMaxPagesShown / 2 - 1;
	    	$nbPageDroite = $this->nbMaxPagesShown / 2;
	    }
	    else
	    {
	    	$nbPageGauche = intval($this->nbMaxPagesShown / 2);
	    	$nbPageDroite = intval($this->nbMaxPagesShown / 2);
	    }
	    
	   	$this->nbCurrentStartPage = $this->nbCurrentPage - $nbPageGauche;
	   	$this->nbCurrentEndPage = $this->nbCurrentPage + $nbPageDroite;
  		
	   	if ($this->nbCurrentStartPage < 1)
		{
			while ($this->nbCurrentEndPage < $this->nbTotalPages && $this->nbCurrentStartPage < 1)
			{
				$this->nbCurrentStartPage++;
				$this->nbCurrentEndPage++;
			}
			
			if ($this->nbCurrentStartPage < 1)
			{
				$this->nbCurrentStartPage == 1;
			}
		}
		
		if ($this->nbCurrentEndPage > $this->nbTotalPages)
		{
			while ($this->nbCurrentEndPage > $this->nbTotalPages && $this->nbCurrentStartPage > 1)
			{
				$this->nbCurrentStartPage--;
				$this->nbCurrentEndPage--;
			}
			
			if ($this->nbCurrentEndPage > $this->nbTotalPages)
			{
				$this->nbCurrentEndPage == $this->nbTotalPages;
			}
		}
		
		$i = 0;
		foreach ($this->arrAllNbID as $k => $v)
		{
			$nbMin = ($this->nbCurrentPage - 1) * $this->nbRecordsPerPage;
			$nbMax = $nbMin + $this->nbRecordsPerPage;
			if ($i >= $nbMin && $i < $nbMax)
			{
				$this->arrNbID[$k] = $this->arrAllNbID[$k];
			}
			$i++;
		}
	}
	

	public function stpi_aff()
	{
		if ($this->nbTotalPages < 2)
		{
			return false;
		}
		
		$strPage = basename($_SERVER["SCRIPT_NAME"]);
		$strURL = "./" . $strPage . "?";
		foreach ($_GET as $k => $v)
		{
			if ($k != "nbPage")
			{
				$strURL .= $k . "=" . $v . "&amp;";	
			}
		}
		
		print("<div class=\"navigator\">");
		
		if ($this->nbCurrentPage != 1)
		{
			print("<span class=\"navigatorbox\"><a href=\"" . $strURL . "nbPage=1\">|&lt;</a></span>");
			print("&nbsp;<span class=\"navigatorbox\"><a href=\"" . $strURL . "nbPage=" . ($this->nbCurrentPage - 1) . "\">&lt;</a></span>");
		}
		
		$nb = $this->nbCurrentStartPage;
		
		while ($nb <= $this->nbCurrentEndPage)
		{
			if ($nb == $this->nbCurrentPage)
			{
				print("&nbsp;<span>" . $this->nbCurrentPage . "</span>");
			}
			else
			{
				print("&nbsp;<a href=\"" . $strURL . "nbPage=" . $nb . "\">" . $nb . "</a>");
			}
			$nb++;
		}
		
		if ($this->nbCurrentPage != $this->nbTotalPages)
		{
			print("&nbsp;<span class=\"navigatorbox\"><a href=\"" . $strURL . "nbPage=" . ($this->nbCurrentPage + 1) . "\">&gt;</a></span>");
			print("&nbsp;<span class=\"navigatorbox\"><a href=\"" . $strURL . "nbPage=" . $this->nbTotalPages . "\">&gt;|</a></span>");
		}
		
		print("&nbsp;&nbsp;&nbsp;Page " . $this->nbCurrentPage . " / " . $this->nbTotalPages);
		
		print("</div>\n");
	}
}

?>