<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clstypecommande.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTypeCommande = new clstypecommande();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$ok = true;
	$objLang->stpi_setArrLang();
	$arrLang = $objLang->stpi_getArrLang();
	$arrObjTypeCommandeLg = array();
	foreach ($arrLang as $strLg)	
	{
		$arrObjTypeCommandeLg[$strLg] = new clstypecommandelg();
		if (!$arrObjTypeCommandeLg[$strLg]->stpi_setStrLg($strLg))
		{
			$ok = false;
		}
		if (!$arrObjTypeCommandeLg[$strLg]->stpi_setStrName($_POST["strName" . $strLg]))
		{
			$ok = false;
		}
	}
	if ($ok)
	{
		if ($objBdd->stpi_startTransaction())
		{
			if (!$nbTypeCommandeID = $objTypeCommande->stpi_insert())
			{
				$ok = false;
			}
			if ($ok)
			{
				foreach ($arrLang as $strLg)	
				{
					if ($ok)
					{
						if ($arrObjTypeCommandeLg[$strLg]->stpi_setNbTypeCommandeID($nbTypeCommandeID))
						{
							if (!$arrObjTypeCommandeLg[$strLg]->stpi_insert())
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
					print("redirect-nbTypeCommandeID=" . $objTypeCommande->stpi_getNbID());
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