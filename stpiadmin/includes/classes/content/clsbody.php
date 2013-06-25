<?php

require_once(dirname(__FILE__) . "/../commande/clscommandesession.php");
require_once(dirname(__FILE__) . "/../content/clsmenu.php");
require_once(dirname(__FILE__) . "/../security/clslock.php");

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
		$this->objMenu = new clsmenu(basename($_SERVER["SCRIPT_NAME"]));
		$this->objLock = new clslock(basename($_SERVER["SCRIPT_NAME"]), "login.php");
	}

	public function stpi_affSearch()
	{
		print("<section role=\"search\"><div id=\"base-srchbx\"><h2>Recherche</h2>\n");
		print("<form action=\"#\" method=\"post\"><div id=\"base-srchbx-in\">\n");
		print("<label for=\"base-srch\">Recherchez le site Web</label><input id=\"base-srch\" name=\"base-srch\" type=\"search\" value=\"\" size=\"27\" maxlength=\"150\" />\n");
		print("<input id=\"base-srch-submit\" name=\"base-srch-submit\" type=\"submit\" value=\"Recherche\" data-icon=\"search\" />\n");
		print("</div></form>\n");
		print("</div></section>\n");
	}

	public function stpi_affBodyHeader($strWelcome, $strBodyId = "")
	{
		print("<body>\n");
		print("<div id=\"wb-body" . $strBodyId . "\">\n");
		print("<div id=\"wb-skip\">\n");
		print("<ul id=\"wb-tphp\">\n");
		print("<li id=\"wb-skip1\"><a title=\"Passer au contenu principal\" href=\"#wb-cont\">Passer au contenu principal</a></li>\n");
		print("<li id=\"wb-skip2\"><a title=\"Passer au pied de page\" href=\"#wb-nav\">Passer au pied de page</a></li>\n");
		print("</ul>\n");
		print("</div>\n");
		print("<div id=\"wb-head\"><div id=\"wb-head-in\"><header>\n");
		print("<!-- HeaderStart -->\n");
		print("<section><div id=\"base-fullhd\"><h2>Secteur d'en-tête de plein-largeur</h2>\n");
		print("<p class=\"mobile-hide\">id=\"base-fullhd\"</p>\n");
		print("<div id=\"base-fullhd-in\">\n");
		print("<p class=\"mobile-hide\">id=\"base-fullhd-in\"</p>\n");

		$this->objLock->stpi_affUrl();
		$this->stpi_affCartUrl();
		$this->objMenu->stpi_affPublicMenuLang();
		print("</div>\n");

		print("</div></section>\n");

		// Welcome
		print("<div id=\"base-bnr\" role=\"banner\"><div id=\"base-bnr-in\">\n");
		print("<div id=\"base-title\"><p id=\"base-title-in\"><a title=\"" . $strWelcome . "\" href=\"./\">\n");
		print($strWelcome);
		print("</a></p></div>\n");
		$this->stpi_affSearch();
		print("</div></div>\n");

		// Main Menu
		print("<nav role=\"navigation\">\n");
		print("<div id=\"base-psnb\"><h2>Menu<span> du site</span></h2><div id=\"base-psnb-in\"><div class=\"wet-boew-menubar mb-mega\"><div>\n");
		$this->objMenu->stpi_affPublicMenu();
		print("</div></div></div></div>\n");

		// Fil d'Ariane
		print("<div id=\"base-bc\"><h2>Fil d'Ariane</h2><div id=\"base-bc-in\">\n");
		print("<ol>\n");
		print("<li><a title=\"Accueil\" href=\"./home.php\">Accueil</a></li>\n");
		print("<li>$strWelcome</li>\n");
		print("</ol>\n");
		print("</div></div>\n");
		print("</nav>\n");
		print("<!-- HeaderEnd -->\n");

		print("</header></div></div>\n");

		print("<div id=\"wb-core\"><div id=\"wb-core-in\" class=\"equalize\">\n");
		print("<div id=\"wb-main\" role=\"main\"><div id=\"wb-main-in\">\n");
		print("<!-- MainContentStart -->");
	}

	public function stpi_affCartUrl()
	{
		$nbQte = 0;
		$nbTotal = 0;
		print("<div id=\"cart\" class=\"margin-top-large\">");
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
			print($this->objBdd->stpi_trsBddToHTML($nbQte) . " " . $this->objTexte->stpi_getArrTxt("carturl") . ": ");
			print($this->objBdd->stpi_trsBddToHTML($this->stpi_trsNbToPrix($nbTotal)) . "$ ");
			print("<a title=\"" . $this->objTexte->stpi_getArrTxt("cartpayer") . "\" href=\"./" . $this->strCheckoutUrl . "?l=" . LG . "\" >" . $this->objTexte->stpi_getArrTxt("cartpayer") . "</a>\n");
		}
		print("</div>");
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
