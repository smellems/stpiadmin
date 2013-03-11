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

		print("<div id=\"wb-foot\"><div id=\"wb-foot-in\"><footer><h2 id=\"wb-nav\">Pied de page</h2>");
		print("<!-- FooterStart -->");
		print("<nav role=\"navigation\"><div id=\"base-sft\"><h3>Pied de page du site</h3><div id=\"base-sft-in\">");
		print("<section><div class=\"span-2\"><h4 class=\"base-col-head\"><a href=\"#\">À propos de nous</a></h4>");
		print("<ul>");
		print("<li><a href=\"#\">Notre mandat</a></li>");
		print("<li><a href=\"#\">Notre histoire</a></li>");
		print("</ul>");
		print("</div></section>");
		print("<section><div class=\"span-2\"><h4 class=\"base-col-head\"><a href=\"#\">Nouvelles</a></h4>");
		print("<ul>");
		print("<li><a href=\"#\">Communiqués</a></li>");
		print("<li><a href=\"#\">Avix aux médias</a></li>");
		print("<li><a href=\"#\">Multimédia</a></li>");
		print("</ul>");
		print("</div></section>");

		print("</div></div></nav>");

		print("<section><div id=\"base-fullft\"><h3>Secteur de pied de page de plein-largeur</h3>");
		print("<p>&nbsp</p>");
		print("<div id=\"base-fullft-in\">");

		    $this->stpi_affPublicFooter();

		print("</div>");
		print("</div></section>");
		print("<!-- FooterEnd -->");
		print("</footer>");
		print("</div></div></div>");

		print("</body>");
		print("</html>");
	}
}

?>
