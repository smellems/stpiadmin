<?php

require_once(dirname(__FILE__) . "/../commande/clscommandesession.php");

class clsbody
{
	private $objBdd;
	private $objTexte;
	private $objCommande;
	
	private $strCheckoutUrl = "checkout1.php";
	
	public function __construct()
	{
		$this->objBdd = clsBdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcontent");		
		$this->objCommandeSession = new clscommandesession();
	}

	public function stpi_affCartUrl()
	{
		$nbQte = 0;
		$nbTotal = 0;
		
		if (isset($_SESSION["stpiObjCommandeSession"]))
		{
			$this->objCommandeSession = $this->objCommandeSession->stpi_getObjCommandeSessionFromSession();
			$nbTotal += $this->objCommandeSession->stpi_getNbSousTotal();
			$nbTotal -= $this->objCommandeSession->stpi_getNbPrixRabais();
			$nbTotal += $this->objCommandeSession->stpi_getNbPrixShipping();
			$nbTotal += $this->objCommandeSession->stpi_getNbPrixTaxes();
			$nbQte += $this->objCommandeSession->stpi_getNbSousItemQte();
		}
		if (isset($_SESSION["stpiObjCommandeRegistreSession"]))
		{
			$this->objCommandeSession = $this->objCommandeSession->stpi_getObjCommandeRegistreSessionFromSession();
			$nbTotal += $this->objCommandeSession->stpi_getNbSousTotal();
			$nbTotal -= $this->objCommandeSession->stpi_getNbPrixRabais();
			$nbTotal += $this->objCommandeSession->stpi_getNbPrixShipping();
			$nbTotal += $this->objCommandeSession->stpi_getNbPrixTaxes();
			$nbQte += $this->objCommandeSession->stpi_getNbSousItemQte();
		}
				
		if ($nbQte != 0)
		{
			print($this->objBdd->stpi_trsBddToHTML($nbQte) . " " . $this->objTexte->stpi_getArrTxt("carturl") . "<br/>");
			print($this->objBdd->stpi_trsBddToHTML($this->stpi_trsNbToPrix($nbTotal)) . " $ - ");
			print("<a href=\"./" . $this->strCheckoutUrl . "?l=" . LG . "\" >" . $this->objTexte->stpi_getArrTxt("cartpayer") . "</a>\n");
		}
	}	
	
	public function stpi_trsInputToHTML($nstrTexte)
	{
		return htmlentities(stripslashes($nstrTexte), ENT_QUOTES, STR_CHAR_ENC);
	}
	
	public function stpi_trsNbToPrix($nNb)
	{
		return number_format($nNb, 2);
	}
}

?>