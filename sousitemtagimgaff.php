<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/item/clsitem.php");
	
	$objBdd = clsbdd::singleton();
	$objItem = new clsitem();
	$objSousItem =& $objItem->stpi_getObjSousItem();
	$objAttribut =& $objItem->stpi_getObjSousItem()->stpi_getObjAttribut();
	
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
	
	
	$ok = false;
	if ($arrAttributID != array())
	{
		$SQL = "SELECT stpi_item_SousItem.nbSousItemID, COUNT(*) AS nbMatch";
		$SQL .= " FROM stpi_item_SousItem, stpi_item_SousItem_Attribut";
		$SQL .= " WHERE stpi_item_SousItem.nbItemID = '" . $objBdd->stpi_trsInputToBdd($_POST["nbItemID"]) . "'";
		$SQL .= " AND stpi_item_SousItem.nbSousItemID = stpi_item_SousItem_Attribut.nbSousItemID";
		$SQL .= " AND stpi_item_SousItem.nbQte > 0";
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
		$SQL .= " HAVING nbMatch = '" . $objBdd->stpi_trsInputToBdd(count($arrAttributID)) . "'";
		$ok = true;
		if ($result = $objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				if ($objSousItem->stpi_setNbID($row["nbSousItemID"]))
				{
					if ($objItem->stpi_setNbID($objSousItem->stpi_getNbItemID()))
					{
						$objItem->stpi_setObjItemLgFromBdd();
						if ($arrAttributID = $objSousItem->stpi_selNbAttributID())
						
						if (!$arrNbImageID = $objSousItem->stpi_selNbImageID())
						{
							$arrNbImageID = array();
						}
						
						if (isset($arrNbImageID[1]))
						{
							print("<img style=\"cursor: pointer;\" onclick=\"window.open ('sousitemimgaff.php?l=" . LG . "&amp;nbImageID=" . $objBdd->stpi_trsBddToHTML($arrNbImageID[2]) . "', '', config='height=550, width=550, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')\" alt=\"" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getObjItemLg()->stpi_getStrName()) . "\" src=\"./sousitemimgaff.php?nbImageID=" . $objBdd->stpi_trsBddToHTML($arrNbImageID[1]) . "\" />\n");				
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
		}
		else
		{
			$ok = false;
		}
	}
	
	if (!$ok)
	{
		if ($objItem->stpi_setNbID($_POST["nbItemID"]))
		{
			$objItem->stpi_setObjItemLgFromBdd();
			if ($objItem->stpi_getNbImageID() != 0)
			{
				print("<img alt=\"" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getObjItemLg()->stpi_getStrName()) . "\" src=\"./itemimgaff.php?nbImageID=" . $objBdd->stpi_trsBddToHTML($objItem->stpi_getNbImageID()) . "\" />\n");
			}
		}
	}
?>