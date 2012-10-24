<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/menu/clsmenuelement.php");
	require_once("./includes/classes/security/clslock.php");
	
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objMenuElement = new clsmenuelement();
	$objBdd = clsbdd::singleton();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	if ($_GET["nbConfirmed"] == 1)
	{
		$ok = true;
		if (!$objMenuElement->stpi_objMenuPublic()->stpi_setNbID($_GET["nbMenuID"]))
		{
			$ok = false;
		}
		if ($ok)
		{
			if (!$objMenuElement->stpi_objMenuPublic()->stpi_chkBoolDelete())
			{
				$ok = false;
			}
		}

		if ($ok)
		{
			if ($objMenuElement->stpi_setNbMenuID($_GET["nbMenuID"]))
			{
				if ($objMenuElement->stpi_selNbMenuID())
				{
					$objMenuElement->stpi_objMenuPublic()->stpi_affNoDelete();
					$ok = false;
				}
			}
		}
		if ($ok)
		{
			if ($objBdd->stpi_startTransaction())
			{
				if (!$objMenuElement->stpi_objMenuPublic()->stpi_getObjMenuLg()->stpi_deleteMenuId($_GET["nbMenuID"]))
				{
					$ok = false;
				}
				if ($ok)
				{
					if (!$objMenuElement->stpi_objMenuPublic()->stpi_delete($_GET["nbMenuID"]))
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
		$objMenuElement->stpi_objMenuPublic()->stpi_affDelete();
	}
?>
