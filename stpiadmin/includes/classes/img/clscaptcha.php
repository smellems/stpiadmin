<?php

require_once(dirname(__FILE__) . "/../security/clscryption.php");

class clscaptcha
{
	private $objCryption;
	
	//Grosseur des Captcha
	private $nbCaptchaX = 140;
	private $nbCaptchaY = 70;

	//Charactères utilisées dans les Captcha
	private $strCaptchaChars = "123456789";
	//Nombre de charactères des Captcha	
	private $nbCaptchaChars = "5";
	//Le font des Captcha, ne pas oublier de mettre le fichier de font dans le meme répertoire
	private $strCaptchaFont = "matejo";
	
	private $strCaptcha;
	
	private $captcha;
	
	
	public function __construct()
	{
		$this->objCryption = new clscryption();
		putenv("GDFONTPATH=" . dirname(__FILE__));	
	}
	
	
	public function stpi_setCaptcha()
	{
		$this->strCaptcha = "";

		for( $i = 0; $i < $this->nbCaptchaChars; $i++ )
		{
			$nb = rand( 0, strlen( $this->strCaptchaChars ) - 1 );
			$this->strCaptcha .= substr( $this->strCaptchaChars, $nb, 1 );
		}
	
		$this->captcha = imagecreatetruecolor( $this->nbCaptchaX , $this->nbCaptchaY );
	
		$nbBgrR = rand( 0, 255 );
		$nbBgrG = rand( 0, 255 );
		$nbBgrB = rand( 0, 255 );
		$nbTxtR = 255 - $nbBgrB;
		$nbTxtG = 255 - $nbBgrR;
		$nbTxtB = 255 - $nbBgrG;
	
		$clrBgrColour = imagecolorallocate( $this->captcha, $nbBgrR, $nbBgrG, $nbBgrB );
		$clrTxtColour = imagecolorallocate( $this->captcha, $nbTxtR, $nbTxtG, $nbTxtB );
	
		imagefill( $this->captcha, 0, 0, $clrBgrColour );
	
		
		$nbAngle = rand( -5, 5 );
	
		imagettftext( $this->captcha, 35, $nbAngle, 10, 45, $nbTxtColour, $this->strCaptchaFont, $this->strCaptcha );
	}
	
	
	public function stpi_getCaptcha()
	{
		return $this->captcha;
	}
	
	
	public function stpi_CaptchaToBrowser()
	{
		header("Content-type: image/jpeg");
		
		//Envoyer le captcha au browser 
		imagejpeg( $this->captcha, NULL, 100 );
	}
	
	
	public function stpi_setCaptchaCookie()
	{
		//Sauvegarder le texte du captcha encrypter dans un cookie pour 20 minutes
		setcookie("stpiCaptcha", $this->objCryption->stpi_trsTextToCrypted($this->strCaptcha), time() + 60 * 20 , "/");
	}
	
	
	//Fonction qui vérifie si le captcha est valide avec celui dans le cookie
	//$nstrCaptcha : Le texte du captcha à vérifier
	//return : Retourne true si le captcha est valide ou faux pour le contraire
	public function stpi_chkCaptcha($nstrCaptcha)
	{
		if (isset($_COOKIE["stpiCaptcha"]))
		{
			if ($_COOKIE["stpiCaptcha"] == $this->objCryption->stpi_trsTextToCrypted($nstrCaptcha))
			{
				return true;
			}
		}
		
		return false;
	}
	
	
}

?>