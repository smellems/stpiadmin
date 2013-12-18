<?php
require_once(dirname(__FILE__) . "/../menu/clsmenuelement.php");
class clsmenu
{
	private $objBdd;
	private $objTexte;
	
	private $strPage;

	private $arrLang;
	private $arrSalut;
	
	public function __construct($nstrPage)
	{
		$this->objBdd = clsbdd::singleton();
		$this->strPage = $nstrPage;
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtcontent");
	}
	
	public function stpi_setArrLang()
	{
		$SQL = "SELECT strLangID,";
		$SQL .= " strLang";
		$SQL .= " FROM stpi_lang";
		$SQL .= " ORDER BY strLang";

		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$this->arrLang = array();
			while ($row = mysql_fetch_assoc($result))
			{
				$this->arrLang[$row["strLangID"]] = $row["strLang"];
			}
			mysql_free_result($result);
		}
	}
	
	public function stpi_setArrSalut()
	{
		$SQL = "SELECT strLangID,";
		$SQL .= " strSalut";
		$SQL .= " FROM stpi_lang";
		$SQL .= " ORDER BY strSalut";

		if ($result = $this->objBdd->stpi_select($SQL))
		{
			$this->arrSalut = array();
			while ($row = mysql_fetch_assoc($result))
			{
				$this->arrSalut[$row["strLangID"]] = $row["strSalut"];
			}
			mysql_free_result($result);
		}
	}
	
	public function stpi_affSTPIAdminMenuLang()
	{
		$this->stpi_setArrLang();
		
		if (isset($this->arrLang))
		{
			print("<ul>\n");
			foreach ($this->arrLang as $k => $v)
			{
				if ($k == "fr" || $k == "en")
				{
					print("<li><a ");
					if ($k == LG)
					{
						print("class=\"active\" ");
					}
					print("href=\"./" . $this->strPage);
					print("?l=" . $k);
					foreach ($_GET as $k1 => $v1)
					{
						if ($k1 != "l")
						{
							print("&amp;" . $k1 . "=" . $v1);	
						}
					}
					print("\">");
					print($v . "</a></li>\n");
				}
			}
			print("</ul>\n");
		}		
	}
	
	
	public function stpi_affPublicMenuLang()
	{
		$this->stpi_setArrLang();
		if (isset($this->arrLang))
		{
            $i = 0;
			print("<ul>\n");
			foreach ($this->arrLang as $k => $v)
			{
			    if ($k == LG)
                {
                    print("<li id=\"base-fullhd-lang");
                    if ($i > 0)
                    {
                        print("-" . $i);
                    }
                    print("\"><a lang=\"" . $k . "\" href=\"./" . $this->strPage);
				    print("?l=" . $k);
				    foreach ($_GET as $k1 => $v1)
				    {
					    if ($k1 != "l")
					    {
						    print("&amp;" . $k1 . "=" . $v1);	
					    }
				    }
				    print("\" title=\"" . $v . "\">");
				    print($v . "</a></li>\n");
                    $i++;
                }
			}
			print("</ul>\n");
		}		
	}
	
	
	public function stpi_affPublicMenu()
	{
		$objMenuElement = new clsmenuelement();
		if ($objMenuElement->stpi_setNbMenuID(1))
		{
			if ($arrNbMenuElementID = $objMenuElement->stpi_selNbMenuID())
			{
				print("<ul class=\"mb-menu\">");
				foreach ($arrNbMenuElementID as $nbMenuElementID)
				{
					if ($objMenuElement->stpi_setNbID($nbMenuElementID))
					{
						if ($objMenuElement->stpi_setObjMenuElementLgFromBdd())
						{
							print("<li><div><a title=\"" . $objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText() . "\" href=\"");
							print($this->objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrLien()) . "\">");
							print($this->objBdd->stpi_trsBddToHTML($objMenuElement->stpi_getObjMenuElementLg()->stpi_getStrText()) . "</a></div></li>\n");
						}
					}
				}
				print("</ul>");
			}
		}
	}
}

?>
