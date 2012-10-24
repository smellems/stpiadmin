<?php

require_once(dirname(__FILE__) . "/../content/clsbody.php");

class clsemail
{
	private $objTexte;
	
	private $strEmail;
	private $strFromEmail;
	private $strSubject;
	private $strMessage;
	
	
	public function __construct($strEmail = "", $strFromEmail = "", $strSubject = "", $strMessage = "")
	{
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtemail");
		$strEmail = "";
		$strFromEmail = "";
		$strSubject = "";
		$strMessage = "";
		
		if (!empty($strEmail))
		{
			$this->strEmail = $this->stpi_setStrEmail($strEmail);
		}
	}
	
	
	public function stpi_chkStrEmail($nstrEmail)
	{
		if (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $nstrEmail) || preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/", $nstrEmail))
		{
			return true;
    	}
    	else
    	{
    		print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidemail") . "</span><br/>\n");
    		return false;
    	}
	}
	
	
	public function stpi_chkStrFromEmail($nstrFromEmail)
	{
		if (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $nstrFromEmail) || preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/", $nstrFromEmail))
		{
			return true;
    	}
    	else
    	{
    		print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidemail") . "</span><br/>\n");
    		return false;
    	}
	}
	
	
	public function stpi_chkStrSubject($nstrSubject)
	{
		if (empty($nstrSubject))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidsubject") . "</span><br/>\n");
			return false;
		}
		
		return true;		
	}
	
	
	public function stpi_chkStrMessage($nstrMessage)
	{
		if (empty($nstrMessage))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("invalidmessage") . "</span><br/>\n");
			return false;
		}
		
		return true;		
	}
	
	
	public function stpi_setStrEmail($nstrEmail)
	{
		if (!$this->stpi_chkStrEmail($nstrEmail))
		{
			return false;
		}
		
		$this->strEmail = $nstrEmail;
		return true;
	}
	
	
	public function stpi_setStrFromEmail($nstrFromEmail)
	{
		if (!$this->stpi_chkStrFromEmail($nstrFromEmail))
		{
			return false;
		}
		
		$this->strFromEmail = $nstrFromEmail;
		return true;
	}
	
	
	public function stpi_setStrSubject($nstrSubject)
	{
		if (!$this->stpi_chkStrSubject($nstrSubject))
		{
			return false;
		}
		
		$this->strSubject = $nstrSubject;
		return true;
	}
	
	
	public function stpi_setStrMessage($nstrMessage)
	{
		if (!$this->stpi_chkStrMessage($nstrMessage))
		{
			return false;
		}
		
		$this->strMessage = $nstrMessage;
		return true;
	}
	
	
	public function stpi_getStrEmail()
	{
		return $this->strEmail;
	}
	
	
	public function stpi_getStrFromEmail()
	{
		return $this->strFromEmail;
	}
	
	
	public function stpi_getStrSubject()
	{
		return $this->strSubject;
	}
	
	
	public function stpi_getStrMessage()
	{
		return $this->strMessage;
	}
	
	
	public function stpi_Send()
	{
		if (empty($this->strEmail) || empty($this->strFromEmail) || empty($this->strSubject) || empty($this->strMessage))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("sending") . "</span><br/>\n");			
			return false;
		}
		
		
		$strHeader  = "MIME-Version: 1.0\r\n";
		$strHeader .= "Content-type: text/html; charset=" . STR_CHAR_ENC . "\r\n";
		$strHeader .= "From: " . $this->strFromEmail;
		
		$strMessage .= "<html>\n";	
		$strMessage .= "<head>\n";		
		$strMessage .= "<title>" . $this->strSubject . "</title>\n";
		
		$strMessage .= "</head>\n";
		
		$strMessage .= "<body>\n";
		
		$strMessage .= $this->strMessage;
		
		$strMessage .= "</body>\n";

		$strMessage .= "</html>\n";
		
		if (!mail(stripslashes($this->strEmail), stripslashes($this->strSubject), $strMessage, $strHeader))
		{
			return false;
		}
		
		return true;
	}
}

?>