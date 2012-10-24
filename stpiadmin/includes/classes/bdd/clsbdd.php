<?php

class clsbdd
{
	private static $objBdd;
	
	private $strAddress = "localhost";
	private $nbPort = 3306;
	private $strUser = "stpiadmindb";
	private $strPass = "uRQUznpTrtCBEsK7";
	private $strbdd = "stpiadmindb";
	private $strCharEnc = "utf8";
	private $strCollate = "utf8_unicode_ci";
	
	private $conn;
	
    private function __construct() 
    {		
    	$this->conn = mysql_connect($this->strAddress . ":" . $this->nbPort, $this->strUser, $this->strPass);
    	
    	if (!$this->conn)
		{
			print(mysql_error() . "\n");
    		exit;
		}
	
		//Selectionner la base de données
		if (!mysql_select_db($this->strbdd, $this->conn))
		{
	    	print(mysql_error() . "\n");
	    	exit;
		}
	
		//Fixer l'encodage qui sera utiliser
		if (!mysql_query("SET NAMES '" . $this->strCharEnc . "' COLLATE '" . $this->strCollate . "'", $this->conn))
		{
	   		print(mysql_error() . "\n");
	   	 	exit;
		}
    }

    public static function singleton() 
    {
        if (!isset(self::$objBdd))
        {
            $c = __CLASS__;
            self::$objBdd = new $c;
        }
        return self::$objBdd;
    }
    
    public function __clone()
    {
        exit;
    }
    
    
	public function stpi_setConn($nconn) 
    {		
    	$this->conn = $nconn;
    }
    
    
	public function stpi_getConn() 
    {		
    	return $this->conn;
    }
    
    
	// Fonction qui selection de l'information dans une base de données à partir d'une requête
	// $nSQL : La requête SQL qui sera executé
	// return : Retourne le résultat en ressource de base de données ou false s'il n'y a pas de résultat
	//Les resultats pourront être lue de la façon suivante : while($row = mysql_fetch_assoc($result))
	public function stpi_select($nSQL)
	{
		$result = mysql_query($nSQL, $this->conn);
		if (!$result)
		{
		  	print(mysql_error($this->conn) . "\n");
	   		return false;
		}
		
		if (mysql_num_rows($result) == 0)
		{
			return false;
		}	
		return $result;
	}
	
	
	// Fonction qui change de l'information dans une base de données à partir d'une requête
	// $nSQL : La requête SQL qui sera executé
	// return : Retourne le nombre de lignes affectées ou false s'il n'y a pas de lignes affectées
	public function stpi_update($nSQL)
	{
		//print("<p>$nSQL</p>");
		$result = mysql_query($nSQL, $this->conn);
		if (!$result)
		{
		  	print(mysql_error($this->conn) . "\n");
	   		return false;
		}
		return $result;
	}
	
	
	// Fonction qui inserre de l'information dans une base de données à partir d'une requête
	// $nSQL : La requête SQL qui sera executé
	// return : Retourne le nombre de lignes affectées ou false s'il n'y a pas de lignes affectées
	public function stpi_insert($nSQL)
	{
		//print("<p>$nSQL</p>");
		$result = mysql_query($nSQL, $this->conn);
		if (!$result)
		{
		  	print(mysql_error($this->conn) . "\n");
	   		return false;
		}
		if (mysql_affected_rows($this->conn) == 0)
		{
			return false;
		}
		return $result;
	}
	
	
	// Fonction qui efface de l'information dans une base de données à partir d'une requête
	// $nSQL : La requête SQL qui sera executé
	// return : Retourne le nombre de lignes affectées ou false s'il n'y a pas de lignes affectées
	public function stpi_delete($nSQL)
	{
		//print("<p>$nSQL</p>");
		$result = mysql_query($nSQL, $this->conn);
		if (!$result)
		{
		  	print(mysql_error($this->conn) . "\n");
	   		return false;
		}
		if (mysql_affected_rows($this->conn) == 0)
		{
			return false;
		}	
		return $result;
	}
	
	
	// Fonction qui commence une transaction
	// return : TRUE si c'est bon et FALSE sinon
	public function stpi_startTransaction()
	{
		$result = mysql_query("START TRANSACTION", $this->conn);
		if (!$result)
		{
			print(mysql_error($this->conn) . "\n");
	   		return false;
		}
		return true;
	}
	
	
	// Fonction qui fait une transaction à partir d'un array de requêtes
	// $nSQL : Les requêtes SQL qui seront executé
	// return : true si c'est bon et false sinon
	public function stpi_transaction($nSQL, $nn)
	{	
		for ($i = 1; $i <= $nn; $i++)
		{
			if (strpos($nSQL[$i], "UPDATE") !== false)
			{
				if (!stpi_bddUpdate($nSQL[$i]))
				{
					return false;
				}
			}
			elseif (strpos($nSQL[$i], "INSERT") !== false)
			{
				if (!stpi_bddInsert($nSQL[$i]))
				{
					return false;
				}
			}
			elseif (strpos($nSQL[$i], "DELETE") !== false)
			{
				if (!stpi_bddDelete($nSQL[$i]))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	
	// Fonction qui fait le COMMIT d'une transaction
	// return : true si c'est bon et false sinon
	public function stpi_commit()
	{
		$result = mysql_query("COMMIT", $this->conn);
		if (!$result)
		{
			print(mysql_error($this->conn) . "\n");
	   		return false;
		}
		return true;
	}
	
	
	// Fonction qui fait le ROLLBACK d'une transaction
	// return : true si c'est bon et false sinon
	public function stpi_rollback()
	{
		$result = mysql_query("ROLLBACK", $this->conn);
		if (!$result)
		{
			print(mysql_error($this->conn) . "\n");
	   		return false;
		}
		print("<span style=\"color:#FF0000;\">ROLLBACKED</span>\n");
		return true;
	}
	
	
	// Fonction qui retourne le dernier ID d'un inserte
	// return : le ID ou false
	public function stpi_getInsertID()
	{
		return mysql_insert_id($this->conn);
	}
	
	
	//Fonction qui vérifie si une texte peut être mis dans la base de données
	//$nstrTexte : Le texte à vérifier
	//return : Retourne true si le texte est valide ou faux pour le contraire
	public function stpi_chkInputToBdd($nstrTexte)
	{
		$nstrTexte = trim($nstrTexte);
		if (empty($nstrTexte))
		{
			return false;
		}

		if (!mysql_real_escape_string($nstrTexte, $this->conn))
		{
			return false;
		}
			
		return true;
	}
	
	//Fonction qui vérifie si une texte est une date valide ISO (2009-03-31)
	//$nstrTexte : Le texte à vérifier
	//return : Retourne true si le texte est valide ou faux pour le contraire
	public function stpi_chkDate($nstrTexte)
	{
		$nstrTexte = trim($nstrTexte);
		list($a, $m, $j) = explode("-", $nstrTexte);
		if (!stpi_chkAnnee($a))
		{
			return false;
		}
		
		if (!stpi_chkMois($m))
		{
			return false;
		}
		
		if (!is_numeric($j) OR $j < 1 OR $j > date('t', mktime(0, 0, 0, $m, 1, $a)))
		{
			return false;
		}
		return true;
	}
	
	public function stpi_chkAnnee($na)
	{
		if (!is_numeric($na) OR $na < 1)
		{
			return false;
		}
		return true;
	}
	
	public function stpi_chkMois($nm)
	{
		if (!is_numeric($m) OR $m < 1 OR $m > 12)
		{
			return false;
		}
		return true;
	}
	
	
	//Fonction qui transforme un texte pour qu'il soit insérer dans la base de données
	//$nstrTexte : Le texte à transformer
	//return : Retourne le text qui peut être insérer dans la base de données
	public function stpi_trsInputToBdd($nstrTexte)
	{
		$nstrTexte = trim($nstrTexte);
		
		$nstrTexte = mysql_real_escape_string($nstrTexte, $this->conn);
					
		return $nstrTexte;
	}
	
	
	//Fonction qui transforme de l'information de la base de données en information html affichable
	//$nstrTexte : Le texte à transformer
	//return : Retourne le text affichable en format html
	public function stpi_trsBddToHTML($nstrTexte)
	{
		return htmlentities(stripslashes($nstrTexte), ENT_QUOTES, STR_CHAR_ENC);
	}
	
	
	//Fonction qui transforme de l'information de la base de données en information html affichable
	//$nstrTexte : Le texte à transformer
	//return : Retourne le text affichable en format html
	public function stpi_trsBddToBBCodeHTML($nstrTexte)
	{
		$nstrTexte = htmlentities(stripslashes($nstrTexte), ENT_QUOTES, STR_CHAR_ENC);
		$nstrTexte = str_replace("\n\n\n", "<br />", $nstrTexte);
	
		while (preg_match('`\[(.+?)=?(.*?)\](.+?)\[/\1\]`', $nstrTexte, $arrmatches))
		{
		 	list($match, $tag, $param, $innertext) = array($arrmatches[0], $arrmatches[1], $arrmatches[2], $arrmatches[3]);
		 	switch ($tag)
	 		{
                case 'b': $replacement = "<b>" . $innertext . "</b>";
                break;

		case 'br': $replacement = "<br />";
                break;
                
                case 'i': $replacement = "<i>" . $innertext . "</i>";
                break;
                
                case 'u': $replacement = "<u>" . $innertext . "</u>";
                break;

		case 'li': $replacement = "<li>" . $innertext . "</li>";
                break;
                
                case 'ul': $replacement = "<ul>" . $innertext . "</ul>";
                break;

		case 'p': $replacement = "<p>" . $innertext . "</p>";
                break;

		case 'p-center': $replacement = "<p style=\"text-align: center;\">" . $innertext . "</p>";
                break;

		case 'h1': $replacement = "<h1>" . $innertext . "</h1>";
                break;

		case 'h1-center': $replacement = "<h1 style=\"text-align: center;\">" . $innertext . "</h1>";
                break;

		case 'h2': $replacement = "<h2>" . $innertext . "</h2>";
                break;

		case 'h2-center': $replacement = "<h2 style=\"text-align: center;\">" . $innertext . "</h2>";
                break;

		case 'h3': $replacement = "<h3>" . $innertext . "</h3>";
                break;

		case 'h3-center': $replacement = "<h3 style=\"text-align: center;\">" . $innertext . "</h3>";
                break;

		case 'h4': $replacement = "<h4>" . $innertext . "</h4>";
                break;

		case 'h5': $replacement = "<h5>" . $innertext . "</h5>";
                break;

                case 'size': $replacement = "<span style=\"font-size: " . $param . "px;\">" . $innertext . "</span>";
                break;
                
                case 'color': $replacement = "<span style=\"color: #" . $param . ";\">" . $innertext . "</span>";
                break;
                
                case 'url':
                {
                	$replacement = "<a href=\"";
                	if ($param)
                	{
                		$replacement .= $param;
                	}
                	else
                	{
                		$replacement .= $innertext;
                	}
                	$replacement .= "\">" . $innertext . "</a>";
                	
                }   
                break;
                     
                case 'img':
                {
                    list($width, $height) = preg_split('`[Xx]`', $param);
                    $replacement = "<img src=\"" . $innertext . "\" ";
                    $replacement .= "alt=\"" . $innertext . "\" ";
                    if (is_numeric($width))
                    {
                    	$replacement .= "width=\"" . $width . "\" ";
                    }
                    if (is_numeric($height))
                    {
                    	$replacement .= "height=\"" . $height . "\" ";
                    }
                    $replacement .= "/>";
                }
                break;
            }
            $nstrTexte = str_replace($match, $replacement, $nstrTexte);
    	}
        return $nstrTexte;		
	}

	//Fonction qui permet d'afficher du html provenant de la base de données
	//$nstrTexte : Le texte à transformer
	//return : Retourne le text affichable en format html
	public function stpi_trsBddHtmlToHTML($nstrTexte)
	{
		return stripslashes($nstrTexte);
	}
	
	
	//Fonction qui transforme un numéro de téléphone (00000-000-000-0000)
	//$nstrTel : numéro de téléphone (00000000000000)
	//return : num tel formaté ou $nstrTel si < 10
	public function stpi_trsBddToTel($nstrTel)
	{
		$newtel = "";
		$i = strlen($nstrTel);

		if (10 <= $i)
		{
			$t1 = substr($nstrTel, $i-4, 4);
			$t2 = substr($nstrTel, $i-7, 3);
			$t3 = substr($nstrTel, $i-10, 3);

			if (10 < $i)
			{
				$t4 = substr($nstrTel, 0, $i-10);
				$newtel = "$t4-";
			}

			$newtel .= "$t3-$t2-$t1";
			return $newtel;
		}
		else
		{
			return $nstrTel;
		}
	}
	
	
	// Fonction qui transforme un code postal (j8X e3M)
	// $nstrTel : code psotal tout mal formater
	// return code ou ""
	public function stpi_trsTelToBdd($nstrTel)
	{
		$nstrTel = trim($nstrTel);
		$i = strlen($nstrTel);
		$nnewtel = "";
		for ($x = 0; $x < $i; $x++)
		{
			if (preg_match("/[0-9\+]/", substr($nstrTel, $x ,1)))
			{
				$nnewtel .= substr($nstrTel, $x ,1);
			}
		}
		return $nnewtel;
	}
	
	
	//Fonction qui transforme un code postal
	//$nstrCP : code postal
	//return : code netoyé
	public function stpi_trsCodePostalToBdd($nstrCP)
	{
		$nstrCP = strtoupper(trim($nstrCP));
		$i = strlen($nstrCP);
		$nnewCP = "";
		for ($x = 0; $x < $i; $x++)
		{
			if (preg_match("/[0-9A-Z]/", substr($nstrCP, $x ,1)))
			{
				$nnewCP .= substr($nstrCP, $x ,1);
			}
		}
		return $nnewCP;	
	}
	
	
	//Fonction qui vérifie dans un table de base de données si une valeur dans un champ existe
	//$nstrValeur : La valeur à chercher dans le champ
	//$nstrChamp : Le champ de la table dans la quel chercher
	//$nstrTable : La table dans la quel chercher
	//return : Retourne true si la valeur a été trouver dans le cham de la table ou faux pour le contraire
	public function stpi_chkExists($nstrValeur, $nstrChamp, $nstrTable)
	{
		//Création de la requête
		$SQL = "SELECT " . $this->stpi_trsInputToBdd($nstrChamp);
		$SQL .= " FROM " . $this->stpi_trsInputToBdd($nstrTable);
		$SQL .= " WHERE " . $this->stpi_trsInputToBdd($nstrChamp) . " = '" . $this->stpi_trsInputToBdd($nstrValeur) . "'";
		
		if ($results = $this->stpi_select($SQL))
		{
			return true;
			
			mysql_free_result($results);
		}
		
		return false;
	}
	
	
	public function stpi_chkArrExists($narrValeur, $narrChamp, $nstrTable)
	{
		if (count($narrValeur) == 0)
		{
			return false;
		}
		
		if (count($narrChamp) == 0)
		{
			return false;
		}
		
		if (count($narrValeur) != count($narrChamp))
		{
			return false;
		}
		
		$SQL = "SELECT " . $this->stpi_trsInputToBdd($narrChamp[0]);
		for($i = 1; $i < count($narrChamp); $i++)
		{
			$SQL .= ", " . $narrChamp[$i];
		}
		$SQL .= " FROM " . $this->stpi_trsInputToBdd($nstrTable);
		$SQL .= " WHERE " . $this->stpi_trsInputToBdd($narrChamp[0]) . " = '" .  $this->stpi_trsInputToBdd($narrValeur[0]) . "'";
		for($i = 1; $i < count($narrValeur); $i++)
		{
			$SQL .= " AND " . $this->stpi_trsInputToBdd($narrChamp[$i]) . " = '" .  $this->stpi_trsInputToBdd($narrValeur[$i]) . "'";
		}
		if ($results = $this->stpi_select($SQL))
		{
			return true;
			
			mysql_free_result($results);
		}
		
		return false;
	}
	
	
	//Fonction qui vérifie dans un table de base de données si un champ d'un entrée est null
	//$nnbID : Le numéro d'identification de l'entrée
	//$nstrChampID : Le champ dans lequel le numéro d'identification sera chercher
	//$nstrChamp : Le champ à vérifier si il est null
	//$nstrTable : La table dans la quel chercher
	//return : Retourne true si le champ est null ou faux pour le contraire
	public function stpi_chkNull($nnbID, $nstrChampID, $nstrChamp, $nstrTable)
	{
		//Création de la requête
		$SQL = "SELECT " . $this->stpi_trsInputToBdd($nstrChampID);
		$SQL .= " FROM " . $this->stpi_trsInputToBdd($nstrTable);
		$SQL .= " WHERE " . $this->stpi_trsInputToBdd($nstrChampID) . " = '" . $this->stpi_trsInputToBdd($nnbID) . "'";
		$SQL .= " AND " . $this->stpi_trsInputToBdd($nstrChamp) . " IS NULL";
		
		if ($results = $this->stpi_select($SQL))
		{
			return true;
			
			mysql_free_result($results);
		}
		
		return false;
	}
	
	
	//Fonction qui vérifie dans un table de base de données si un champ bool est true ou false
	//$nnbID : Le numéro d'identification de l'entrée
	//$nstrChampID : Le champ dans lequel le numéro d'identification sera chercher
	//$nstrChamp : Le champ bool à  vérifier
	//$nstrTable : La table dans la quel chercher
	//return : Retourne true si c'est le bool est true ou faux pour le contraire
	public function stpi_chkBool($nnbID, $nstrChampID, $nstrChamp, $nstrTable)
	{
		//Création de la requète
		$SQL = "SELECT " . $this->stpi_trsInputToBdd($nstrChampID);
		$SQL .= " FROM " . $this->stpi_trsInputToBdd($nstrTable);
		$SQL .= " WHERE " . $this->stpi_trsInputToBdd($nstrChampID) . " = '" . $this->stpi_trsInputToBdd($nnbID) . "'";
		$SQL .= " AND " . $this->stpi_trsInputToBdd($nstrChamp) . " = '1'";
		
		if ($results = $this->stpi_select($SQL))
		{
			return true;
			
			mysql_free_result($results);
		}
		
		return false;
	}
	
}

?>
