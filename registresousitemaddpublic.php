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
	$objItem =& $objRegistreSousItem->stpi_getObjItem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	if (!isset($_GET["nbSousItemID"]) OR !isset($_GET["nbQte"]))
	{
		exit;
	}
	
	if ($objUser = $objUser->stpi_getObjUserFromSession())
	{
		if ($objUser->stpi_getNbTypeUserID() == 2)
		{
			if ($objClient->stpi_setNbID($objUser->stpi_getNbID()))
			{
				if ($nbRegistreID = $objClient->stpi_selNbRegistreIDPublic())
				{
					if ($objRegistre->stpi_setNbID($nbRegistreID))
					{
						if ($objUser->stpi_getNbID() == $objRegistre->stpi_getNbClientID())
						{
							if ($objSousItem->stpi_setNbID($_GET["nbSousItemID"]))
							{
								$ok = true;
								if ($objBdd->stpi_chkArrExists(array($nbRegistreID, $_GET["nbSousItemID"]), array("nbRegistreID", "nbSousItemID"), "stpi_registre_Registre_SousItem"))
								{
									$boolArrExists = 1;
									if(!$objRegistreSousItem->stpi_setNbID($nbRegistreID, $_GET["nbSousItemID"]))
									{
										$ok = false;
									}
								}
								else
								{
									$boolArrExists = 0;
									if (!$objRegistreSousItem->stpi_setNbSousItemID($_GET["nbSousItemID"]))
									{
										$ok = false;
									}
									if (!$objRegistreSousItem->stpi_setNbRegistreID($nbRegistreID))
									{
										$ok = false;
									}
									if (!$objRegistreSousItem->stpi_setStrItemCode($objSousItem->stpi_getStrItemCode()))
									{
										$ok = false;
									}
									if ($objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
									{
										if ($strSousItemDesc = $objItem->stpi_getStrSousItemDesc())
										{
											if (!$objRegistreSousItem->stpi_setStrSousItemDesc($strSousItemDesc))
											{
												$ok = false;
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
									}
								}
								if(!$objRegistreSousItem->stpi_setNbQteVoulu($objRegistreSousItem->stpi_getNbQteVoulu() + $_GET["nbQte"]))
								{
									$ok = false;
								}
								
								if ($ok)
								{
									if ($objBdd->stpi_startTransaction())
									{
										if ($boolArrExists)
										{
											if (!$objRegistreSousItem->stpi_update())
											{
												$ok = false;
											}
										}
										else
										{
											if (!$objRegistreSousItem->stpi_insert())
											{
												$ok = false;
											}
										}
										if ($ok)
										{
											if ($objBdd->stpi_commit())
											{
												print($objTexte->stpi_getArrTxt("itemadded") . " <a href=\"./registrepublic.php?l=" . LG . "&nbRegistreID=" . $nbRegistreID . "\">" . $objTexte->stpi_getArrTxt("registre") . "</a></b><p>\n");
											}
											else
											{
												$objBdd->stpi_rollback();
											}
										}
										else
										{
											$objBdd->stpi_rollback();
										}
									}
								}
								
							}
						}
					}
				}
			}
		}
	}

	

?>