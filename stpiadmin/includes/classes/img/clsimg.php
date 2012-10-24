<?php

class clsimg
{
	private $objBdd;
	private $objTexte;
	
	private $nbID;
	private $strTable;
	
	private $img;
	private $tmpImg;
	
	private $nbWidth;
	private $nbHeight;
	
	public function __construct($strTable, $nnbID = 0)
	{
		$this->objBdd = clsbdd::singleton();
		$this->objTexte = new clstexte(dirname(__FILE__) . "/txtimg");
		$this->strTable = $strTable;
		$this->nbWidth = 0;
		$this->nbHeight = 0;
		if ($nnbID == 0)
		{
			$this->nbID = $nnbID;
		}
		else
		{
			if (!$this->stpi_setNbID($nnbID))
			{
				return false;
			}
		}
		return true;
	}
	
	public function stpi_chkNbID($nnbID)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nnbID))
		{
			print("<span style=\"color:#FF0000;\">--$nnbID--" . $this->objTexte->stpi_getArrErrTxt("invalid") . "</span><br/>\n");
			return false;				
		}
		if (!$this->objBdd->stpi_chkExists($nnbID, "nbImageID", $this->strTable))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imgnoexists") . "</span><br/>\n");
			return false;
		}
		return true;		
	}
	
	
	public function stpi_chkImg($nImg)
	{
		if (!$this->objBdd->stpi_chkInputToBdd($nImg))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imageinvalid") . "</span><br/>\n");
			return false;				
		}
		return true;		
	}
	
	
	public function stpi_chkTmpImg($ntmpImg)
	{
		if (empty($ntmpImg))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imageinvalid") . "</span><br/>\n");
			return false;				
		}
		if (!is_uploaded_file($ntmpImg))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imageinvalid") . "</span><br/>\n");
			return false;
		}
		if (!getimagesize($_FILES["blobImage"]["tmp_name"]))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imageinvalid") . "</span><br/>\n");
			return false;
		}
		return true;		
	}
	

	public function stpi_setNbID($nnbID)
	{
		if (!$this->stpi_chkNbID($nnbID))
		{
			return false;
		}
		$this->nbID = $nnbID;
		
		$SQL = "SELECT blobImage FROM " . $this->objBdd->stpi_trsInputToBdd($this->strTable) . " WHERE nbImageID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		if ($result = $this->objBdd->stpi_select($SQL))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				if (!empty($row["blobImage"]))
				{
					$this->img = $row[blobImage];
				}
				else
				{
					print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imgnoexists") . "</span><br/>\n");
					return false;
				}
			}
			else
			{
				print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imgnoexists") . "</span><br/>\n");
				return false;
			}
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imgnoexists") . "</span><br/>\n");
			return false;
		}
		
		if (!$this->stpi_setNbWidthHeightFromImg())
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imgnoexists") . "</span><br/>\n");
			return false;
		}
		
		return true;
	}
	
	
	public function stpi_setImg($nimg)
	{
		if (!$this->stpi_chkImg($nimg))
		{
			return false;
		}
		$this->img = $nimg;
		return true;
	}	
	
	
	public function stpi_setTmpImg($ntmpImg)
	{
		if (!$this->stpi_chkImg($ntmpImg))
		{
			return false;
		}
		$this->tmpImg = $ntmpImg;
		return true;
	}
	
	
	public function stpi_setNbWidthHeightFromImg()
	{
		if (!isset($this->img))
		{
			return false;
		}
		
		$imgTmp = imagecreatefromstring($this->img);
		
		$this->nbWidth = imagesx($imgTmp);
		$this->nbHeight = imagesy($imgTmp);
		
		return true;
	}
	
	
	public function stpi_getNbID()
	{
		
		return $this->nbID;
	}
	
	
	public function stpi_getImg()
	{
		return $this->img;
	}
	
	
	public function stpi_getTmpImg()
	{
		return $this->tmpImg;
	}
	
	
	public function stpi_getNbWidth()
	{
		return $this->nbWidth;
	}
	
	
	public function stpi_getNbHeight()
	{
		return $this->nbHeight;
	}
	
	
	public function stpi_insert()
	{
		$SQL = "INSERT INTO " . $this->objBdd->stpi_trsInputToBdd($this->strTable) . " (blobImage) VALUES ('" . $this->objBdd->stpi_trsInputToBdd($this->img) . "')";
		if ($this->objBdd->stpi_insert($SQL))
		{
			$this->nbID = $this->objBdd->stpi_getInsertID();
			return $this->nbID;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $b["adding"] . "</span><br/>\n");
      		return false;
		}
	}
	
	public function stpi_update()
	{
		$SQL = "UPDATE " . $this->objBdd->stpi_trsInputToBdd($this->strTable);
      	$SQL .= " SET blobImage='" . $this->objBdd->stpi_trsInputToBdd($this->img) . "'";
		$SQL .= " WHERE nbImageID='" . $this->objBdd->stpi_trsInputToBdd($this->nbID) . "'";
		
		if (!$this->objBdd->stpi_update($SQL))
		{
			print("<span style=\"color:#FF0000;\">" . $b["editing"] . "</span><br/>\n");
      		return false;
		}
		return true;
	}
	
	
	public function stpi_delete($nnbImageID)
	{
		if (!$this->stpi_chkNbID($nnbImageID))
		{
			return false;				
		}
		$SQL = "DELETE FROM " . $this->objBdd->stpi_trsInputToBdd($this->strTable) . " WHERE nbImageID=" . $this->objBdd->stpi_trsInputToBdd($nnbImageID);
		if ($this->objBdd->stpi_delete($SQL))
		{
			return true;
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("deleting") . "</span><br/>\n");
			return false;
		}
	}
	
	
	public function stpi_affImg()
	{
		if (!isset($this->img))
		{
			return false;
		}
		header("Content-Type: image/jpeg");
		print($this->img);
		return true;
	}
	
	
	function stpi_trsTmpImgToImg()
	{
		if (!isset($this->tmpImg))
		{
			return false;
		}
		
		//Ouvrir le fichier de l'image
		$flImg = fopen($this->tmpImg, "r");
    
      	//Lire le contenue du fichier image
      	if (!$this->stpi_setImg(fread($flImg, filesize($this->tmpImg))))
      	{
      		print("<span style=\"color:#FF0000;\">" . $b["erreurimageinvalid"] . "</span><br/>\n");
      		return false;
      	}
      	return true;
	}
	
	
	function stpi_trsImgResize($nnbWidthMax, $nnbHeightMax)
	{
		if (!isset($this->tmpImg))
		{
			return false;
		}
		
		if (!$arrSize = getimagesize($this->tmpImg))
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imgnoexists") . "</span><br/>\n");
			return false;
		}

		//Si c'est un gif
		if ($arrSize[2] == 1)
		{
			$img = imagecreatefromgif($this->tmpImg);
		}
		//Si c'est un jpeg
		else if ($arrSize[2] == 2)
		{
			$img = imagecreatefromjpeg($this->tmpImg);
		}
		//Si c'est un png
		else if ($arrSize[2] == 3)
		{
			$img = imagecreatefrompng($this->tmpImg);
		}
		//Si c'est un bmp
		else if ($arrSize[2] == 6)
		{
			$img = imagecreatefrombmp($this->tmpImg);
		}
		else
		{
			print("<span style=\"color:#FF0000;\">" . $this->objTexte->stpi_getArrErrTxt("imageunsupported") . "</span><br/>\n");
			return false;
		}
		
		//Largeur
		$x = $arrSize[0];
		//Hauteur
		$y = $arrSize[1];

		if ($x == 0 || $y == 0)
		{
			return false;
		}
		
		if ($arrSize[2] != 2 || $x != $nnbWidthMax || $y != $nnbHeightMax)
		{
			if ($x > $nnbWidthMax || $y > $nnbHeightMax)
			{
				$nbFactor = min($nnbWidthMax / $x, $nnbHeightMax / $y);  
				
				$x = round($x * $nbFactor);
		        $y = round($y * $nbFactor);
			} 
			
	        $imgResized = imagecreatetruecolor($x, $y);
	        
	        imagecopyresampled($imgResized, $img, 0, 0, 0, 0, $x, $y, $arrSize[0], $arrSize[1]);
			        
			imagejpeg($imgResized, $this->tmpImg, 100);	
		}
        
        return true;
	}
}


?>