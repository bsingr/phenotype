<?php


// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Krämer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------
?>
<?php


/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeImage extends PhenotypeMediaObject
{
	public $type = MB_IMAGE;
	public $x;
	public $y;
	public $align = "";
	public $class = "";
	public $style = "";
	public $thumburl;

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

	function getUrl ()
	{
		return (MEDIABASEURL . $this->physical_folder . "/" . $this->filename);
	}
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
	
	function render($alt = Null)
	{
		global $myPT;
		$myPT->startbuffer();
		$this->display($alt);
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

	function display($alt = NULL)
	{
		if ($alt == NULL)
		{
			$alt = $this->alt;
		}
?><img src="<?php php echo MEDIABASEURL . $this->physical_folder . "/" . $this->filename ?>" width="<?php php echo $this->x ?>" height="<?php php echo $this->y ?>" alt="<?php php echo $alt ?>" title="<?php php echo $alt ?>" border="0"<?php if ($this->align!=""){ ?> align="<?php php echo $this->align ?>"<?php } ?><?php if ($this->class!=""){ ?> class="<?php php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php php echo $this->style ?>"<?php } ?> /><?php



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
?><img src="<?php php echo MEDIABASEURL . $this->physical_folder . "/" . $this->filename ?>" width="<?php php echo $x ?>" height="<?php php echo $y ?>" alt="<?php php echo $alt ?>" title="<?php php echo $alt ?>" border="0" <?php if ($this->class!=""){ ?> class="<?php php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php php echo $this->style ?>"<?php } ?> /><?php



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
   } if ($this->class!=""){?> class="<?php php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php php echo $this->style ?>"<?php } ?> /><?php



		}

		function display_XY($x, $y, $alt = NULL)
		{
?><img src="<?php echo MEDIABASEURL . $this->physical_folder . "/" . $this->filename ?>" width="<?php echo $x ?>" height="<?php echo $y ?>" alt="<?php echo $alt ?>" title="<?php echo $alt ?>" border="0" <?php



			if ($this->fname != "")
			{
?>
    name="<?php echo $this->fname ?>"
   <?php
   } if ($this->class!=""){?> class="<?php php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php php echo $this->style ?>"<?php } ?> /><?php



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
   } if ($this->class!=""){?> class="<?php php echo $this->class ?>"<?php } ?><?php if ($this->style!=""){ ?> style="<?php php echo $this->style ?>"<?php } ?> /><?php



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
}
?>
