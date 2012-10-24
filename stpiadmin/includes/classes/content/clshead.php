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
		
		print("<title>" . $this->strPageTitle . "</title>\n");
		print("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $this->strCharEnc . "\" />\n");
		print("<meta name=\"description\" content=\"" . $this->strDescription . "\" />\n");
		print("<meta name=\"keywords\" content=\"" . $this->strKeyWords . "\" />\n");
		print("<meta name=\"copyright\" content=\"Copyright &copy; $strDate " . $this->strNomEnt . "\" />\n");
		print("<meta name=\"classification\" content=\"general\" />\n");
		print("<meta name=\"Robots\" content=\"All\" />\n");
		print("<link rel=\"shortcut icon\" href=\"favicon.ico\" />\n");
		print("<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\" />\n");
	}
	
}

?>
