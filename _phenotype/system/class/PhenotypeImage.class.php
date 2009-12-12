<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Krämer.
//
// Open Source since 11/2006, I8ln since 11/2008
// -------------------------------------------------------
// Thanks for your support: 
// Markus Griesbach, Alexander Wehrum, Sebastian Heise,
// Dominique Boes, Florian Gehringer, Jens Bissinger
// -------------------------------------------------------
// www.phenotype-cms.com - offical homepage
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------


/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeImageStandard extends PhenotypeMediaObject
{
	/**
	 * object type
	 * 
	 * currently MB_IMAGE or MB_DOCUMENT
	 *
	 * @var integer
	 */
	public $type = MB_IMAGE;
	
	/**
	 * width
	 *
	 * @var integer
	 */
	public $x;
	
	/**
	 * height
	 *
	 * @var integer
	 */
	public $y;
	public $align = "";
	public $class = "";
	public $style = "";
	public $thumburl;

	/**
	 * Name/Title of the object
	 *
	 * @var string
	 */
	public $bez;
	
	/**
	 * original file name (before importing/uploading into the mediabase)
	 *
	 * @var string
	 */
	public $bez_original;
	
	function __construct($img_id)
	{
		global $myDB;

		$img_id = (int) $img_id;

		$sql = "SELECT * FROM media WHERE med_id = ".$img_id." AND med_type = ".MB_IMAGE;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs) != 0)
		{
			$row = mysql_fetch_array($rs);
			$this->id = $row["med_id"];
			$this->grp_id = $row["grp_id"];
			$this->versioncount = $row["med_versioncount"];
			$this->x = $row["med_x"];
			$this->y = $row["med_y"];
			$this->thumb = $row["med_thumb"];
			$this->alt = $row["med_alt"];
			$this->bez = $row["med_bez"];
			$this->bez_original = $row["med_bez_original"];
			$this->keywords = $row["med_keywords"];
			$this->comment = $row["med_comment"];
			$this->usr_id = $row["usr_id"];
			$this->cdate = $row["med_date"];
			$this->usr_id_creator = $row["usr_id_creator"];
			$this->creationdate = $row["med_creationdate"];
			$this->physical_folder = $this->grp_id."/".$row["med_physical_folder"];
			$this->logical_folder1 = $row["med_logical_folder1"];
			$this->logical_folder2 = $row["med_logical_folder2"];
			$this->logical_folder3 = $row["med_logical_folder3"];
			$this->filename = sprintf("%06.0f", $this->id).".".$row["med_subtype"];
			$this->suffix = $row["med_subtype"];
			$this->mimetype = $row["med_mimetype"];

			if ($this->thumb == 1)
			{
				$this->filename_thumb = sprintf("%06.0f", $this->id)."_t.".$row["med_subtype"];
			} else
			{
				$this->filename_thumb = $this->filename;
			}

			$this->url = MEDIABASEURL.$this->physical_folder."/".$this->filename;
			$this->thumburl = MEDIABASEURL.$this->physical_folder."/".$this->filename_thumb;
			$this->file = MEDIABASEPATH . $this->physical_folder . "/" . $this->filename;
			
			$this->loaded=1;
		}
	}
	/*
	function getUrl ()
	{
		return (MEDIABASEURL . $this->physical_folder . "/" . $this->filename);
	}
	*/
	function setClass ($class)
	{
		$this->class = $class;
	}

	function setAlign ($align)
	{
		$this->align = $align;
	}
	
	function setName ($name)
	{
		$this->name = $name;
	}	

	function setId ($id)
	{
		$this->id = $id;
	}	
	
	function render($alt = Null, $version = NULL)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display($alt, $version);
		$html = $myPT->stopbuffer();
		return $html;
	}

	function display_thumb($alt = NULL)
	{
		$this->display_thumbX(90, $alt);
	}

	function render_thumb($alt = NULL)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display_thumb($alt);
		return $myPT->stopbuffer();
	}

	function render_thumbX($fixX, $alt = NULL)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display_thumbX($fixX, $alt);
		return $myPT->stopbuffer();
	}

	function render_fixX($fixX, $alt = NULL)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display_fixX($fixX, $alt = NULL);
		return $myPT->stopbuffer();
	}

	function render_maxX($maxX, $alt = NULL)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display_maxX($maxX, $alt = NULL);
		return $myPT->stopbuffer();
	}

	function render_XY($x, $y, $alt = NULL)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display_XY($x, $y, $alt);
		return $myPT->stopbuffer();
	}

	function display($alt = NULL, $version = NULL)
	{
		global $myDB;
		if ($alt == NULL)
		{
			$alt = $this->alt;
		}
		if ($version >= 1) {
			$sql = "SELECT * FROM mediaversion WHERE med_id = ".$this->id." AND ver_id = ".(int)$version." ORDER BY ver_bez, ver_id DESC";
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$this->initVersion($row);
		}
		?><img src="<?php echo MEDIABASEURL . $this->physical_folder . "/" . $this->filename ?>" width="<?php echo $this->x ?>" height="<?php echo $this->y ?>" alt="<?php echo $alt ?>" title="<?php echo $alt ?>" border="0"<?php if ($this->align!=""){ ?> align="<?php echo $this->align ?>"<?php } ?><?php if ($this->class!=""){ ?> class="<?php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php echo $this->style ?>"<?php } ?> /><?php
	}

	function display_maxX($maxX, $alt = NULL)
	{
		if ($this->x > $maxX)
		{

			$y = round($this->y * ($maxX / $this->x));
			$x = $maxX;

		} else
		{
			$x = $this->x;
			$y = $this->y;
		}
		if ($alt == NULL)
		{
			$alt = $this->alt;
		}
?><img src="<?php echo MEDIABASEURL . $this->physical_folder . "/" . $this->filename ?>" width="<?php echo $x ?>" height="<?php echo $y ?>" alt="<?php echo $alt ?>" title="<?php echo $alt ?>" border="0" <?php if ($this->class!=""){ ?> class="<?php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php echo $this->style ?>"<?php } ?> /><?php



	}

	// zeigt Thumbnail mit definierter X-Größe an
	function display_thumbX($fixX, $alt = NULL)
	{
		if ($this->id == 0)
		{
			$sx = $fixX;
			$sy = 1;
			$alt = "";
			$filename = "";
		} else
		{
			$sx = $this->x;
			$sy = $this->y;

			if ($alt == NULL)
			{
				$alt = $this->alt;
			}

			if ($sx < $fixX AND $sy < $fixX)
			{
				// Keine Veraenderung der Groesse
				$tx = $sx;
				$ty = $sy;
				$filename = $this->filename;
			} else
			{
				if ($sx > $sy)
				{
					$tx = $fixX;
					$ty = round($fixX * ($sy / $sx));
				} else
				{
					$tx = round($fixX * ($sx / $sy));
					$ty = $fixX;
				}
				$filename = $this->filename_thumb;
			}
		}
?><img src="<?php echo MEDIABASEURL . $this->physical_folder . "/" . $filename ?>" width="<?php echo $tx ?>" height="<?php echo $ty ?>" alt="<?php echo $alt ?>" title="<?php echo $alt ?>" border="0" <?php



		if ($this->fname != "")
		{
?>
    name="<?php echo $this->fname ?>"
   <?php
   } if ($this->class!=""){?> class="<?php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php echo $this->style ?>"<?php } ?> /><?php



		}

		function display_XY($x, $y, $alt = NULL)
		{
?><img src="<?php echo MEDIABASEURL . $this->physical_folder . "/" . $this->filename ?>" width="<?php echo $x ?>" height="<?php echo $y ?>" alt="<?php echo $alt ?>" title="<?php echo $alt ?>" border="0" <?php



			if ($this->fname != "")
			{
?>
    name="<?php echo $this->fname ?>"
   <?php
   } if ($this->class!=""){?> class="<?php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php echo $this->style ?>"<?php } ?> /><?php



			}

			function display_fixX($fixX, $alt = "NULL")
			{
				if ($this->id == 0)
				{
					$sx = $fixX;
					$sy = 1;
					$alt = "";
					$filename = "";
				} else
				{
					$sx = $this->x;
					$sy = $this->y;

					if ($alt == NULL)
					{
						$alt = $this->alt;
					}

					if ($sx > $sy)
					{
						$tx = $fixX;
						$ty = round($fixX * ($sy / $sx));
					} else
					{
						$tx = round($fixX * ($sx / $sy));
						$ty = $fixX;
					}
					$filename = $this->filename;
				}
?><img src="<?php echo MEDIABASEURL . $this->physical_folder . "/" . $filename ?>" width="<?php echo $tx ?>" height="<?php echo $ty ?>" alt="<?php echo $alt ?>" title="<?php echo $alt ?>" border="0" <?php



				if ($this->fname != "")
				{
?>
    name="<?php echo $this->fname ?>"
   <?php
   } if ($this->class!=""){?> class="<?php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php echo $this->style ?>"<?php } ?> /><?php



				}

	function createResizedJPGVersion($bez,$kante,$y=0,$quality=0)
	{
		$myMB = new PhenotypeMediabase();
		$dateiname_complete = $this->url;
		$size = GetImageSize($dateiname_complete);
		if($size[2] != 2){return false;} // Kein JPEG
		$pfad = TEMPPATH."/media/";
		if (!file_exists($pfad))
		{
			mkdir($pfad,UMASK);
		}
		$dateiname_thumb = $pfad.uniqid("pt").".tmp";
		$myMB->createThumbnailFromJpeg($dateiname_complete, $dateiname_thumb, $kante , $y,$quality);
		$myMB->importImageVersionFromUrl($dateiname_thumb, $bez, $this->id);
		@unlink ($dateiname_thumb);
	}				
	
	/**
	 * Select version of an image or create it (if not existing) 
	 *
	 * @param string name of the version to be selected/created
	 * @param integer width $x
	 * @param integer heigth $y
	 * @param integer 4 = fixed target size, 5 fit into traget size and maintain ratio (default)
	 * @param integer JPEG quality
	 * @param integer sharpen 0->no 1-4->yes
	 * @return boolean success
	 */
	function selectVersionOrCreate($name,$x,$y,$method = 5, $quality = 85,$sharpening = 1)
	{
		
		$rc = parent::selectVersion($name);
		if (!$rc)
		{
			$size = GetImageSize($this->file);

			if ($size!=false)
			{
				$skip =false;
				switch ($size["mime"])
				{
					case "image/gif":
						$sourceImage = imagecreatefromgif($this->file);
						break;
					case "image/jpeg":
						$sourceImage = imagecreatefromjpeg($this->file);
						break;
					case "image/png":
						$sourceImage = imagecreatefrompng($this->file);
						break;
					default:
						return false;
						break;
				}

			}
			else 
			{
				return false;
			}
			
			
			$myMB = new PhenotypeMediabase();
			$sx = 0;
			$sy = 0;
			$sw = $this->x;
			$sh = $this->y;
			$tx = 0;
			$ty = 0;
			$tw = $x;
			$th = $y;

			if ($method==4) // feste Zielgröße
			{
				// Anpassen des Ausschnitts

				$ratio = $tw/$th;

				if ($ratio<($sw/$sh))
				{
					// Bild zu breit, d.h. volle Höhe
					$breite = $sh*$ratio;
					$sx = (int)(($sw-$breite)/2);
					$sw = (int)$breite;
				}
				else
				{
					// Bild zu hoch, d.h. volle Breite
					$hoehe = $sw/$ratio;
					$sy = (int)(($sh-$hoehe)/2);
					$sh = (int)$hoehe;
				}
			}
			else // Zielrahmen
			{
				$sx = 0;
				$sy = 0;
				$sw = $this->x;
				$sh = $this->y;
				$tx = 0;
				$ty = 0;
				$tw = $this->x;
				$th = $this->y;

				$r = $x/$sw;

				if ($sh*$r<=$x) // Breite passt
				{
					$tw = $x;
					$th = (int)($sh*$r);

				}
				else // Höhe
				{
					$r = $y/$sh;
					$th = $y;
					$tw = (int)($sw*$r);
				}

			}

			$targetImage = imagecreatetruecolor($tw, $th);

			if (function_exists(imagecopyresampled))
			{
				imagecopyresampled($targetImage, $sourceImage, $tx, $ty, $sx, $sy, $tw, $th, $sw, $sh);
			} else
			{
				imagecopyresized($targetImage, $sourceImage, $tx, $ty, $sx, $sy, $tw, $th, $sw, $sh);
			}

			// Nachschärfen
			switch ($sharpening)
			{
				case 1:
					$targetImage = $myMB->unsharpMask($targetImage,40,0.5,3);
					break;
				case 2:
					$targetImage = $myMB->unsharpMask($targetImage,80,0.5,3);
					break;
				case 3:
					$targetImage = $myMB->unsharpMask($targetImage,140,0.5,3);
					break;
				case 4:
					$targetImage = $myMB->unsharpMask($targetImage,180,0.5,3);
					break;

			}


			$targetfile = TEMPPATH ."media/~build_".$this->id .".jpg";


			ImageJPEG($targetImage, $targetfile,$quality);
			@ chmod($targetfile, UMASK);

			$url = $targetfile;

			$rc = $myMB->importImageVersionFromUrl($url,$name, $this->id,0);

			@unlink($url);
			return parent::selectVersion($name);
		}
		return false;
	}	
	
	/**
	 * Get recording date and time of the image, if exif data is present
	 *
	 * @return integer timestamp
	 */
	public function getExifRecordDateTime()
	{
		$_exif = exif_read_data($this->file);
		if ($_exif["DateTime"]!="")
		{
			$datumzeit=explode(" ",$_exif["DateTime"]);
			$aufnahmezeit=$datumzeit[1];
			$aufnahmedatum=explode(":",$datumzeit[0]);
			$aufnahmedatumzeit=$aufnahmedatum[1].".".$aufnahmedatum[1].".".$aufnahmedatum[0]." ".$aufnahmezeit."<br>";


			$t = Phenotype::germanDT2Timestamp($aufnahmedatumzeit);
			return $t;

		}
		else 
		{
			return false;		
		}
	}
}
?>
