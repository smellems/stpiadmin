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
		if (!$objCommande->stpi_setNbID($_GET["nbCommandeID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				$nbInfoCarteID = $objCommande->stpi_getNbInfoCarteID();
				if ($objBdd->stpi_chkExists($nbInfoCarteID, "nbInfoCarteID", "stpi_commande_InfoCarte"))
				{
					if (!$objCommande->stpi_getObjInfoCarte()->stpi_delete($nbInfoCarteID))
					{
						$ok = false;
					}
				}
				if ($ok)
				{
					if ($objCommande->stpi_setNbInfoCarteID(0))
					{
						if (!$objCommande->stpi_update())
						{
							$ok = false;
						}
					}
				}
				if ($ok)
				{
					if ($objBdd->stpi_commit())
					{
						print("redirect-nbCommandeID=" . $_GET["nbCommandeID"]);
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
		$objCommande->stpi_affDeleteInfocarte();
	}
?>