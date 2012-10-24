<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/commande/clscommande.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objCommande = new clscommande();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objCommande->stpi_getObjStatutCommande()->stpi_setNbID($_GET["nbStatutCommandeID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objCommande->stpi_getObjStatutCommande()->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}
		if ($ok)
		{
			if ($arrNbCommandeID = $objCommande->stpi_getObjStatutCommande()->stpi_selNbCommandeID())
			{
				$ok = false;
				$objCommande->stpi_getObjStatutCommande()->stpi_affNoDelete();
				foreach($arrNbCommandeID as $nbCommandeID)
				{
					if ($objCommande->stpi_setNbID($nbCommandeID))
					{
						print("<a href=\"./commande.php?l=" . LG);
						print("&amp;nbCommandeID=" . $objBdd->stpi_trsBddToHTML($nbCommandeID) . "\">");
						print($objBdd->stpi_trsBddToHTML($objCommande->stpi_getNbID()) . " - " . $objBdd->stpi_trsBddToHTML($objCommande->stpi_getDtEntryDate()) . "</a><br/>\n");
					}
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if ($ok)
				{
					if (!$objCommande->stpi_getObjStatutCommande()->stpi_getObjStatutCommandeLg()->stpi_deleteStatutCommandeId($_GET["nbStatutCommandeID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if (!$objCommande->stpi_getObjStatutCommande()->stpi_delete($_GET["nbStatutCommandeID"]))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-");
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
	else
	{
		$objCommande->stpi_getObjStatutCommande()->stpi_affDelete();
	}
?>