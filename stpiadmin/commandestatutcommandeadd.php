<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clsstatutcommande.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objStatutCommande = new clsstatutcommande();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjStatutCommandeLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjStatutCommandeLg[$strLg] = new clsstatutcommandelg();
		if (!$arrObjStatutCommandeLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjStatutCommandeLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbStatutCommandeID = $objStatutCommande->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjStatutCommandeLg[$strLg]->stpi_setNbStatutCommandeID($nbStatutCommandeID))
						{
							if (!$arrObjStatutCommandeLg[$strLg]->stpi_insert())
							{
								$ok = false;
							}
						}
						else
						{
							$ok = false;
						}
					}
				}
			}
			if ($ok)
			{
				if ($objBdd->stpi_commit())
				{
					print("redirect-nbStatutCommandeID=" . $objStatutCommande->stpi_getNbID());
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
?>