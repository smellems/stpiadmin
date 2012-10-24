<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objBdd = clsBdd::singleton();
	$objTexte = new clstexte("./texte/registre");	
	$objLock = new clslock($strPage);
	
	$objLock->stpi_run();
	
	$objUser = new clsuser();
	$objRegistre = new clsregistre();
	$objRegistreSousItem =& $objRegistre->stpi_getObjRegistreSousItem();
	$objClient =& $objRegistre->stpi_getObjClient();
	
	if (!isset($_GET["nbSousItemID"]) OR !isset($_GET["nbRegistreID"]))
	{
		exit;
	}
	
	$objUser = $objUser->stpi_getObjUserFromSession();
	$ok = true;
	if ($objUser->stpi_getNbTypeUserID() == 2)
	{
		if ($objClient->stpi_chkNbID($objUser->stpi_getNbID()))
		{
			if ($objRegistre->stpi_setNbID($_GET["nbRegistreID"]))
			{
				if ($objUser->stpi_getNbID() == $objRegistre->stpi_getNbClientID())
				{
					if ($objRegistre->stpi_chkIfNotExpired())
					{
						if ($objRegistreSousItem->stpi_setNbID($_GET["nbRegistreID"], $_GET["nbSousItemID"]))
						{
							if ($objRegistreSousItem->stpi_getNbQteRecu() > 0)
							{
								$ok = false;
								print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("dejavendu") . "</span><br/>\n");
							}
							else
							{
								if ($objRegistreSousItem->stpi_delete($_GET["nbRegistreID"], $_GET["nbSousItemID"]))
								{
									print("redirect");
								}
							}
						}
					}
					else
					{
						$ok = false;
					}
				}
				else
				{
					$ok = false;
					print("<span style=\"color:#FF0000;\">" . $objTexte->stpi_getArrErrTxt("notyours") . "</span><br/>\n");
				}
			}
		}
	}
	

?>