<?php

class clsjavascript
{
	private $objLang;
	private $objTexte;
	
	private $arrLang;
	
	public function __construct()
	{
		$this->objLang = new clslang();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtjavascript");
		
		$this->objLang->stpi_setArrLang();
		
		$this->arrLang = $this->objLang->stpi_getArrLang();		
	}
	
	
	public function stpi_affArrLang()
	{
		if (isset($this->arrLang))
		{
			print("<script type=\"text/javascript\">\n");
			print("<!--\n");
			print("var strLg = [];\n");
			$i = 0;
			foreach ($this->arrLang as $v)
			{
				print("strLg[" . $i . "] = \"" . $v . "\";\n");
				$i++;
			}
			print("-->\n");
			print("</script>\n");	
		}
	}
	
	
	public function stpi_affNoAjax()
	{
		print("<script type=\"text/javascript\">\n");
		print("<!--\n");
		print("var xmlHttp = null;\n");
		print("var strErrXmlHttpObject = \"\";\n");
		print("strErrXmlHttpObject = \"" . $this->objTexte->stpi_getArrErrTxt("noajax") . "\";\n");
		print("-->\n");
		print("</script>\n");
	}
	
	
	public function stpi_affNoJavaScript()
	{
		print("<noscript>\n");
		print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("nojavascript") . "</span><br/>\n");
		print("</noscript>\n");
	}
	
	
	public function stpi_affCreateXmlHttp()
	{
		print("<script type=\"text/javascript\">\n");
		?>
		<!--
		function stpi_XmlHttpObject()
		{
			xmlHttp = null;

			try
		  	{
		  		xmlHttp = new XMLHttpRequest();
		  	}
			catch (e)
		  	{
		  		try
		    	{
		    		xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		    	}
				catch (e)
		    	{
		    		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		    	}
		 	}
		
			return xmlHttp;
		}
		-->
		<?php
		print("</script>\n");
	}
}

?>