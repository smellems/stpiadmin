<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/item/clsitem.php");
	require_once("./stpiadmin/includes/classes/user/clsuser.php");
	require_once("./stpiadmin/includes/classes/registre/clsregistre.php");
	
	$objBdd = clsbdd::singleton();
	$objTexte = new clstexte("./texte/sousitemprixaff");
	
	$objUser = new clsuser();
	$objItem = new clsitem();
	$objRegistre = new clsregistre();
	$objClient =& $objRegistre->stpi_getObjClient();
	$objSousItem = $objItem->stpi_getObjSousItem();
	$objAttribut = $objItem->stpi_getObjSousItem()->stpi_getObjAttribut();
	
	if (!$objItem->stpi_chkNbID($_POST["nbItemID"]))
	{
		exit;
	}
	
	$i = 0;
	$arrAttributID = array();
	
	while(true)
	{
		if (!isset($_POST["nbAttributID" . $i]))
		{
			break;
		}
		
		if (!$objAttribut->stpi_chkNbID($_POST["nbAttributID" . $i]))
		{
			exit;
		}
		
		$arrAttributID[] = $_POST["nbAttributID" . $i];
		
		$i++;
	}
	
	if (empty($arrAttributID))
	{
		exit;
	}
	
	$boolRegistre = 0;
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
							$boolRegistre = 1;
						}
					}
				}
			}
		}
	}
	
	$SQL = "SELECT stpi_item_SousItem.nbSousItemID, COUNT(*) AS nbMatch";
	$SQL .= " FROM stpi_item_SousItem, stpi_item_SousItem_Attribut";
	$SQL .= " WHERE stpi_item_SousItem.nbItemID = '" . $objBdd->stpi_trsInputToBdd($_POST["nbItemID"]) . "'";
	$SQL .= " AND stpi_item_SousItem.nbSousItemID = stpi_item_SousItem_Attribut.nbSousItemID";
	/*
	$SQL .= " AND stpi_item_SousItem.nbItemID=stpi_item_Item_DispItem.nbItemID";
	if ($boolRegistre)
	{
		$SQL .= " AND ((stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0) OR stpi_item_Item_DispItem.nbDispItemID=2)";
	}
	else
	{		
		$SQL .= " AND stpi_item_Item_DispItem.nbDispItemID=1 AND stpi_item_SousItem.nbQte > 0";
	}
	*/
	$SQL .= " AND (";
	foreach ($arrAttributID as $k => $nbAttributID)
	{
		if ($k != 0)
		{
			$SQL .= " OR stpi_item_SousItem_Attribut.nbAttributID = '" . $objBdd->stpi_trsInputToBdd($nbAttributID) . "'";
		}
		else
		{
			$SQL .= " stpi_item_SousItem_Attribut.nbAttributID = '" . $objBdd->stpi_trsInputToBdd($nbAttributID) . "'";
		}	
	}
	$SQL .= " )";
	$SQL .= " GROUP BY stpi_item_SousItem.nbSousItemID";
	$SQL .= " HAVING nbMatch = " . $objBdd->stpi_trsInputToBdd(count($arrAttributID));
	
	$ok = true;
	
	if ($result = $objBdd->stpi_select($SQL))
	{
		if ($row = mysql_fetch_assoc($result))
		{
			if ($objSousItem->stpi_setNbID($row["nbSousItemID"]))
			{
				$boolAddToCart = 0;
				if ($objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
				{
					if ($objItem->stpi_chkDisponibilite(1))
					{
						if ($objSousItem->stpi_getNbQte() > 0)
						{
							$boolAddToCart = 1;
						}
					}
					if ($boolRegistre)
					{
						if (!$objItem->stpi_chkDisponibilite(2))
						{
							$boolRegistre = 0;
						}
					}
				}
				if ($boolAddToCart OR $boolRegistre)
				{
					$objSousItem->stpi_affPrixPublic(1, $boolAddToCart, $boolRegistre);
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
		else
		{
			$ok = false;
		}
	}
	else
	{
		$ok = false;
	}
	
	if ($ok == false)
	{
		print($objTexte->stpi_getArrTxt("noavailable"));
	}
?>