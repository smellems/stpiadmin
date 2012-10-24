<?php
	require_once("./includes/includes.php");
	require_once("./includes/classes/item/clsitem.php");
	require_once("./includes/classes/security/clslock.php");
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objItem = new clsitem();
	$objBdd = clsbdd::singleton();
	$objLang = new clslang();
	$objLock = new clslock($strPage);
	$objLock->stpi_run();
	
	$nbSousItemID = "";
	$strItemCode = "";
	$nbQte = 1;
	$nbPrix = 0.00;
	$strSousItemDesc = "";
	
	if ($objItem->stpi_getObjSousItem()->stpi_setNbID($_GET["nbSousItemID"]))
	{
		$nbSousItemID = $objItem->stpi_getObjSousItem()->stpi_getNbID();
		$strItemCode = $objItem->stpi_getObjSousItem()->stpi_getStrItemCode();
		if ($objItem->stpi_getObjSousItem()->stpi_getObjPrix()->stpi_setNbID($_GET["nbSousItemID"], 1))
		{
			$nbPrix = $objItem->stpi_getObjSousItem()->stpi_getObjPrix()->stpi_getNbPrix();
		}
		if ($objItem->stpi_setNbID($objItem->stpi_getObjSousItem()->stpi_getNbItemID()))
		{
			$strSousItemDesc = $objItem->stpi_getStrSousItemDesc();
		}
	}
		
	print($objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("sousitem") . "ID<br/>\n");
	print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"" . $objBdd->stpi_trsBddToHTML($nbSousItemID) . "\" id=\"nbSousItemID\"/><br/>\n");

	print($objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("codeitem") . "<br/>\n");
	print("<input type=\"text\" maxlength=\"50\" size=\"20\" value=\"" . $objBdd->stpi_trsBddToHTML($strItemCode) . "\" id=\"strItemCode\"/><br/>\n");

	print($objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("qte") . "<br/>\n");
	print("<input type=\"text\" maxlength=\"9\" size=\"10\" value=\"" . $objBdd->stpi_trsBddToHTML($nbQte) . "\" id=\"nbQte\"/><br/>\n");

	print($objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("prix") . "<br/>\n");
	print("<input type=\"text\" maxlength=\"12\" size=\"13\" value=\"" . $objBdd->stpi_trsBddToHTML($nbPrix) . "\" id=\"nbPrix\"/><br/>\n");

	print($objItem->stpi_getObjSousItem()->stpi_getObjTexte()->stpi_getArrTxt("desc") . "<br/>\n");
	print("<input type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $objBdd->stpi_trsBddToHTML($strSousItemDesc) . "\" id=\"strSousItemDesc\"/><br/>\n");
	
?>