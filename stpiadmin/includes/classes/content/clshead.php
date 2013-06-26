<?php

class clshead
{
	private $strNomEnt = STR_NOM_ENT;
	private $strCharEnc = STR_CHAR_ENC;
	private $strPageTitle;
	private $strKeyWords;
	private $strDescription;
	
	public function __construct($nstrPageTitle, $nstrKeyWords, $nstrDescription)
	{
		$this->strPageTitle = $nstrPageTitle;
		$this->strKeyWords = $nstrKeyWords;
		$this->strDescription = $nstrDescription;
	}
	
	//Fonction qui affiche le head de STPIAdmin
	public function stpi_affSTPIAdminHead()
	{	
		//L'année présente
		$strDate = date("Y");
		
		print("<head>\n");
		print("<title>" . $this->strPageTitle . "</title>\n");
		print("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $this->strCharEnc . "\" />\n");
		print("<meta name=\"description\" content=\"" . $this->strDescription . "\" />\n");
		print("<meta name=\"keywords\" content=\"" . $this->strKeyWords . "\" />\n");
		print("<meta name=\"copyright\" content=\"Copyright &copy; $strDate " . "STPIAdmin\" />\n");
		print("<meta name=\"classification\" content=\"general\" />\n");
		print("<meta name=\"Robots\" content=\"All\" />\n");
		print("<link rel=\"shortcut icon\" href=\"favicon.ico\" />\n");
		print("<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\" />\n");
		print("<script type=\"text/javascript\" src=\"includes/ckeditor/ckeditor.js\"></script>\n");
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		CKEDITOR.config.language = "<?php print(LG); ?>"; 
		-->
		<?php
		print("</script>\n");
		print("</head>\n");	 
	}
	
	
	//Fonction qui affiche le head du site web
	public function stpi_affPublicHead()
	{
		//L'année présente
		$strDate = date("Y");

		print("<!DOCTYPE html>\n");
		print("<!--[if IE 7]><html lang=\"fr\" class=\"no-js ie7\"><![endif]-->\n");
		print("<!--[if IE 8]><html lang=\"fr\" class=\"no-js ie8\"><![endif]-->\n");
		print("<!--[if gt IE 8]><!-->\n");
		print("<html lang=\"fr\" class=\"no-js\">\n");
		print("<!--<![endif]-->\n");
		print("<head>\n");
		print("<meta charset=\"" . $this->strCharEnc . "\" />\n");
		print("<title>" . $this->strPageTitle . "</title>\n");

		print("<link rel=\"shortcut icon\" href=\"stpiadmin/includes/wet-boew/theme-base/images/favicon.ico\" />\n");
		print("<meta name=\"description\" content=\"" . $this->strDescription . "\" />\n");
		print("<meta name=\"keywords\" content=\"" . $this->strKeyWords . "\" />\n");
		print("<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />\n");
		print("<meta name=\"copyright\" content=\"Copyright &copy; $strDate " . $this->strNomEnt . "\" />\n");

		print("<script src=\"stpiadmin/includes/wet-boew/js/jquery.min.js\"></script>\n");
		print("<!--[if lte IE 8]>\n");
		print("<script src=\"stpiadmin/includes/wet-boew/js/jquery-ie.min.js\"></script>\n");
		print("<script src=\"stpiadmin/includes/wet-boew/js/polyfills/html5shiv-min.js\"></script>\n");
		print("<link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/grids/css/util-ie-min.css\" />\n");
		print("<link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/js/css/pe-ap-ie-min.css\" />\n");
		print("<link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/theme-base/css/theme-ie-min.css\" />\n");
		print("<noscript><link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/theme-base/css/theme-ns-ie-min.css\" /></noscript>\n");
		print("<![endif]-->\n");
		print("<!--[if gt IE 8]><!-->\n");
		print("<link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/grids/css/util-min.css\" />\n");
		print("<link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/js/css/pe-ap-min.css\" />\n");
		print("<link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/theme-base/css/theme-min.css\" />\n");
		print("<!--<![endif]-->\n");
		print("<noscript><link rel=\"stylesheet\" href=\"stpiadmin/includes/wet-boew/theme-base/css/theme-ns-min.css\" /></noscript>\n");

		print("<!-- CustomScriptsCSSStart -->\n");
		print("<!-- CustomScriptsCSSEnd -->\n");
		print("</head>\n");
	}
	
}

?>
