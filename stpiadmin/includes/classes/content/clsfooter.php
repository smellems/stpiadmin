<?php

require_once(dirname(__FILE__) . "/../content/clsmenu.php");

class clsfooter
{
	private $objTexte;
	
	private $strNomEnt = STR_NOM_ENT;
	private $strLastUpdate;
	
	
	public function __construct()
	{
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcontent");
		$ndtDerniere = filemtime(basename($_SERVER["SCRIPT_NAME"]));
		$this->objMenu = new clsmenu(basename($_SERVER["SCRIPT_NAME"]));
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
		
		print("<p class=\"align-center\">\n");
		print("<a target=\"_blank\" title=\"" . $this->objTexte->stpi_getArrTxt("css") . "\" href=\"http://jigsaw.w3.org/css-validator/check/referer\" >" . $this->objTexte->stpi_getArrTxt("css") . "</a>\n");
		print(" - <a target=\"_blank\" title=\"" . $this->objTexte->stpi_getArrTxt("xhtml") . "\" href=\"http://validator.w3.org/check?uri=referer\" >" . $this->objTexte->stpi_getArrTxt("xhtml") . "</a>\n");
		print(" - " . $this->objTexte->stpi_getArrTxt("droit") . " GPLv3 &copy; " . $strDate . " " . STR_NOM_ENT . " " . STR_NUM_VER);
		print("</p>\n");
	}

	public function stpi_affFooter($closeMainContent = 1)
	{
		if($closeMainContent == 1)
		{
			print("<!-- MainContentEnd -->");
			print("</div></div>");
		}
		print("</div></div>");

		print("<div id=\"wb-foot\"><div id=\"wb-foot-in\"><footer><h2 id=\"wb-nav\">" . $this->objTexte->stpi_getArrTxt("footer") . "</h2>");
		print("<!-- FooterStart -->");
		print("<nav role=\"navigation\"><div id=\"base-sft\"><h3>" . $this->objTexte->stpi_getArrTxt("sitefooter") . "</h3><div id=\"base-sft-in\">");

		print("<div class=\"span-2\"></div>\n");
		$this->objMenu->stpi_affPublicFooterMenu();
		print("<div class=\"clear\"></div>\n");

		print("</div></div></nav>");

		print("<section><div id=\"base-fullft\"><h3>" . $this->objTexte->stpi_getArrTxt("footerfull") . "</h3>");
		print("<p>&nbsp;</p>");
		print("<div id=\"base-fullft-in\">");

		$this->stpi_affPublicFooter();

		print("</div>");
		print("</div></section>");
		print("<!-- FooterEnd -->");
		print("</footer>");
		print("</div></div></div>");

		print("<!-- ScriptsStart -->\n");
		print("<script src=\"stpiadmin/includes/wet-boew/js/settings.js\"></script>\n");
		print("<!--[if lte IE 8]>\n");
		print("<script src=\"stpiadmin/includes/wet-boew/theme-base/js/theme-ie-min.js\"></script>\n");
		print("<script src=\"stpiadmin/includes/wet-boew/js/pe-ap-ie-min.js\"></script>\n");
		print("<script src=\"stpiadmin/includes/wet-boew/js/jquerymobile/jquery.mobile-ie.min.js\"></script>\n");
		print("<![endif]-->\n");
		print("<!--[if gt IE 8]><!-->\n");
		print("<script src=\"stpiadmin/includes/wet-boew/theme-base/js/theme-min.js\"></script>\n");
		print("<script src=\"stpiadmin/includes/wet-boew/js/pe-ap-min.js\"></script>\n");
		print("<script src=\"stpiadmin/includes/wet-boew/js/jquerymobile/jquery.mobile.min.js\"></script>\n");
		print("<!--<![endif]-->\n");
		print("<!-- ScriptsEnd -->\n");

		print("<!-- CustomScriptsStart -->\n");
		print("<!-- CustomScriptsEnd -->\n");

		print("</body>\n");
		print("</html>\n");
	}
}

?>
