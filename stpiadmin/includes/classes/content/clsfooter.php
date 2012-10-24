<?php

class clsfooter
{
	private $objTexte;
	
	private $strNomEnt = STR_NOM_ENT;
	private $strLastUpdate;
	
	
	public function __construct()
	{
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcontent");
		
		$ndtDerniere = filemtime(basename($_SERVER["SCRIPT_NAME"]));
		$this->strLastUpdate = date("Y-m-d", $ndtDerniere);
	}		
	
	
	public function stpi_affSTPIAdminFooter()
	{
		$strDate = date("Y");	
		
		print("<ul>\n");
				
		print("<li>" . $this->objTexte->stpi_getArrTxt("css") . "</li>\n");
		print("<li>" . $this->objTexte->stpi_getArrTxt("xhtml") . "</li>\n");
		print("<li>" . $this->objTexte->stpi_getArrTxt("droit") . " GPLv3 &copy; " . $strDate . "</li>");
		print("<li>STPIAdmin " . STR_NUM_VER . "</li>\n");
		print("<li>" . $this->strLastUpdate . "</li>\n");
		
		print("</ul>\n");
	}
	
	
	public function stpi_affPublicFooter()
	{
		$strDate = date("Y");	
		
		print("<ul>\n");
				
		print("<li><a target=\"_blank\" href=\"http://jigsaw.w3.org/css-validator/check/referer\" >" . $this->objTexte->stpi_getArrTxt("css") . "</a></li>\n");
		print("<li><a target=\"_blank\" href=\"http://validator.w3.org/check?uri=referer\" >" . $this->objTexte->stpi_getArrTxt("xhtml") . "</a></li>\n");
		print("<li>" . $this->objTexte->stpi_getArrTxt("droit") . " GPLv3 &copy; " . $strDate . " " . STR_NOM_ENT . " " . STR_NUM_VER . "</li>\n");		
		print("</ul>\n");
	}
}

?>
