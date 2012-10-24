<?php

require_once(dirname(__FILE__) . "/../security/clslock.php");
require_once(dirname(__FILE__) . "/../niveau/clsniveau.php");

class clsrestrictedmenu
{
	private $objBdd;
	private $objLock;
	private $objNiveau;
	private $objTexte;
	
	private $strPage;
	
	public function __construct($nstrPage)
	{
		$this->objBdd = clsBdd::singleton();
		$this->objLock = new clslock($nstrPage);
		$this->objNiveau = new clsniveau();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcontent");
		
		$this->strPage = $nstrPage;
	}
	
	
	//Fonction qui affiche le menu du site web de STPIAdmin
	public function stpi_affSTPIAdminMenu()
	{
		$this->objLock->stpi_setObjUserFromSession();
		$this->objLock->stpi_setNbNiveauID();
		$nbNiveauID = $this->objLock->stpi_getNbNiveauID();
		
		if ($this->objNiveau->stpi_setNbID($nbNiveauID))
		{
			print("<ul>\n");
			foreach ($this->objNiveau->stpi_selNbSectionID() as $nbSectionID)
			{
				$objSection = $this->objNiveau->stpi_getObjSection();

				if ($objSection->stpi_setNbID($nbSectionID))
				{
					if ($objSection->stpi_setObjSectionLgFromBdd())
					{
						$objSectionLg = $objSection->stpi_getObjSectionLg();
						
						$this->objLock->stpi_setStrPage($objSection->stpi_getStrMainPage());
						
						if ($this->objLock->stpi_chkPermission())
						{
							if ($objSection->stpi_getStrMainPage() == "logout.php")
							{
								$strLogoutName = $this->objBdd->stpi_trsBddToHtml($objSectionLg->stpi_getStrName());
							}
							else
							{
								if ($objSection->stpi_getStrMainPage() ==  $this->strPage)
								{
									print("<li><a class=\"active\" href=\"./" . $this->objBdd->stpi_trsBddToHtml($objSection->stpi_getStrMainPage()));
									print("?l=" . LG . "\">" );
									print($this->objBdd->stpi_trsBddToHtml($objSectionLg->stpi_getStrName()) . "</a></li>\n");	
								}
								else
								{
									print("<li><a href=\"./" . $this->objBdd->stpi_trsBddToHtml($objSection->stpi_getStrMainPage()));
									print("?l=" . LG . "\">" );
									print($this->objBdd->stpi_trsBddToHtml($objSectionLg->stpi_getStrName()) . "</a></li>\n");
								}
								
							}
						}
					}
				}
			}
			
			if (isset($strLogoutName))
			{
				print("<li><a href=\"./logout.php");
				print("?l=" . LG . "\">" );
				print($strLogoutName . "</a></li>\n");	
			}
			print("<li><a href=\"./exit.php");
			print("?l=" . LG . "\">");
			print($this->objTexte->stpi_getArrTxt("exit") . "</a></li>\n");			
			print("</ul>\n");
		}
	}
}

?>