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
class PhenotypeMediabaseStandard
{

	var $grp_id = 2; // Standard Mediagroup

	function setMediaGroup($grp_id)
	{
		$grp_id = (int) $grp_id;
		$this->grp_id = $grp_id;
	}

	function getMediaobjectName($med_id)
	{
		global $myDB;
		$med_id = (int) $med_id;
		$sql = "SELECT med_bez FROM media WHERE med_id = ".$med_id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs) != 0)
		{
			$row = mysql_fetch_array($rs);
			return $med_id." ".$row["med_bez"];
		} else
		{
			return false;
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param integer ID of mediagroup | array Array of IDs of mediagroup
	 */
	function getLogicalFolder($grp_id=0)
	{
		global $myDB;

		if (is_array($grp_id))
		{
			$sql2= "";
			foreach ($grp_id AS $k=>$v)
			{
				$sql2 .= $k.",";
			}
			$sql2 ="WHERE grp_id IN(". substr($sql2,0,-1) . ")";
			
		}
		else 
		{
			if ($grp_id!=0){$sql2 =" WHERE grp_id=".$grp_id;}
		}

		//$sql = "SELECT DISTINCT BINARY med_logical_folder1 AS med_logical_folder1 FROM media ".$sql2;		
		$sql = "SELECT DISTINCT med_logical_folder1 FROM media ".$sql2;
		$rs = $myDB->query($sql);
		$_folder = Array ();
		while ($row = mysql_fetch_array($rs))
		{
			$folder = $row["med_logical_folder1"];
			if (!in_array($folder, $_folder) AND $folder != "")
			{
				$_folder[] = $folder;
			}
		}
		
		//$sql = "SELECT DISTINCT BINARY med_logical_folder2 AS med_logical_folder2 FROM media ".$sql2;
		$sql = "SELECT DISTINCT med_logical_folder2 FROM media ".$sql2;
		

		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$folder = $row["med_logical_folder2"];
			if (!in_array($folder, $_folder) AND $folder != "")
			{
				$_folder[] = $folder;
			}
		}
		//$sql = "SELECT DISTINCT BINARY med_logical_folder3 AS med_logical_folder3 FROM media ".$sql2;
		$sql = "SELECT DISTINCT med_logical_folder3 FROM media ".$sql2;
	
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$folder = $row["med_logical_folder3"];
			if (!in_array($folder, $_folder) AND $folder != "")
			{
				$_folder[] = $folder;
			}
		}

		asort($_folder);
		return ($_folder);
	}

	// Diese Methode fügt in den Folderbaum auch leere Folder ein, um den
	// Baum logisch zu komplettieren
	function getFullLogicalFolder($grp_id=0)
	{

		$_folder = $this->getLogicalFolder($grp_id);
		$_folder2 = Array ();
		foreach ($_folder AS $v)
		{
			$navtree = explode("/", $v); #
			$s = "";
			foreach ($navtree AS $branch)
			{
				$branch = trim($branch);
				if ($s == "")
				{
					$s = $branch;
				} else
				{
					$s .= " / ".$branch;
				}
				if (!in_array($s, $_folder2) AND $s != "")
				{
					$_folder2[] = $s;
				}
			}
		}
		asort($_folder2);
		return ($_folder2);
	}

	function getLogicalRootFolder()
	{
		$_folder = $this->getLogicalFolder();
		$_folder2 = Array ();
		foreach ($_folder AS $k)
		{
			if (substr_count($k, "/") == 0)
			{
				$_folder2[] = $k;
			}
		}
		return $_folder2;
	}

	function rewriteFolder($s)
	{
		$s = str_replace('\\', '/', $s);
		$navtree = explode("/", $s);
		$s = "";
		foreach ($navtree AS $branch)
		{
			$branch = trim($branch);
			if ($branch !="")
			{
				if ($s == "")
				{
					$s = $branch;
				} else
				{
					$s .= " / ".$branch;
				}
			}
		}
		return ($s);
	}

	function uploadImage($fname, $logical_folder = "_upload")
	{
		return $this->upload($fname, $logical_folder, MB_IMAGE);
	}

	function uploadDocument($fname, $logical_folder = "_upload")
	{
		return $this->upload($fname, $logical_folder, MB_DOCUMENT);
	}

	function uploadImageOldId($fname, $id)
	{
		$myImg = new PhenotypeImage($id);
		$this->upload($fname, $myImg->logical_folder1, MB_IMAGE, $id);
	}

	function uploadDocumentOldId($fname, $id)
	{
		$myImg = new PhenotypeDocument($id);
		$this->upload($fname, $myImg->logical_folder1, MB_DOCUMENT, $id);
	}

	function uploadImageVersion($fname, $bez, $id)
	{
		return $this->uploadVersion($fname, $bez, $id, MB_IMAGE);
	}

	function uploadDocumentVersion($fname, $bez, $id)
	{
		return $this->uploadVersion($fname, $bez, $id, MB_DOCUMENT);
	}

	function importImageVersionFromUrl($url, $bez, $id,$ver_id=0)
	{
		return $this->uploadVersion($url, $bez, $id, MB_IMAGE, 1,$ver_id);
	}

	function importDocumentVersionFromUrl($url, $bez, $id)
	{
		return $this->uploadVersion($url, $bez, $id, MB_DOCUMENT, 1);
	}

	function upload($fname, $logical_folder, $type, $id = -1)
	{
		global $myDB;

		$size = $_FILES[$fname]["size"];
		if ($size != 0)
		{
			$dateiname_original = $_FILES[$fname]["name"];

			$mySQL = new SQLBuilder();
			if ($id == -1)
			{
				$mySQL->addField("med_bez", $dateiname_original);
				$mySQL->addField("med_bez_original", $dateiname_original);
				$mySQL->addField("med_creationdate", time(), DB_NUMBER);
				$mySQL->addField("usr_id_creator", $_SESSION["usr_id"], DB_NUMBER);
				$sql = $mySQL->insert("media");
				$myDB->query($sql);
				$med_id = mysql_insert_id();
				$folder = $this->createFolder($med_id, $type);
			} else
			{
				if ($type == MB_DOCUMENT)
				{
					// Dateinamen beibehalten
					$myDoc = new PhenotypeDocument($id);
					$dateiname_alt = $myDoc->filename;
					$dateiname_original = $dateiname_alt;
				}

				$mySQL->addField("med_bez_original", $dateiname_original);
				$sql = $mySQL->update("media", "med_id=".$id);
				$myDB->query($sql);
				$med_id = $id;
				$sql = "SELECT med_physical_folder FROM media WHERE med_id=".$med_id;
				$rs = $myDB->query($sql);
				$row = mysql_fetch_array($rs);
				$folder = $row["med_physical_folder"];
			}

			$dateiname_temp = $_FILES[$fname]["tmp_name"];

			$suffix = substr($dateiname_original, strrpos($dateiname_original, ".") + 1);
			$mimetype = $_FILES[$fname]["type"];

			// Zusammenbauen des Dateinamens

			if ($type == MB_IMAGE)
			{
				$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".sprintf("%06.0f", $med_id).".".$suffix;
				$dateiname_thumb = MEDIABASEPATH.$this->grp_id."/".$folder."/".sprintf("%06.0f", $med_id)."_t.".$suffix;
			} else
			{
				if ($id == -1)
				{
					$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".$dateiname_original;
				} else
				{
					// alten Dateinamen behalten
					$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".$dateiname_alt;
					//echo $dateiname_fix;
				}
			}

			//echo $type;
			// Kopieren bzw. Umbenennen der Datei
			if (file_exists($dateiname_fix))
			{
				unlink($dateiname_fix);
			}
			copy($dateiname_temp, $dateiname_fix);
			@ chmod($dateiname_fix, UMASK);

			$mySQL = new SQLBuilder();
			$mySQL->addField("med_physical_folder", $folder);
			$mySQL->addField("grp_id", $this->grp_id);
			$mySQL->addField("med_type", $type, DB_NUMBER);
			$mySQL->addField("med_subtype", $suffix);

			$mySQL->addField("med_mimetype", $mimetype);

			$mySQL->addField("med_logical_folder1", $logical_folder);

			$mySQL->addField("med_date", time(), DB_NUMBER);
			$mySQL->addField("usr_id", $_SESSION["usr_id"], DB_NUMBER);

			//echo $dateiname_fix;
			// Bildeigenschaften

			if ($type == MB_IMAGE)
			{
				$size = GetImageSize($dateiname_fix, $info);

				$mySQL->addField("med_x", $size[0], DB_NUMBER);
				$mySQL->addField("med_y", $size[1], DB_NUMBER);

				// Thumbnail
				//if (strtolower($suffix) == "jpg" OR strtolower($suffix) == "jpeg")
				if ($size[2] == 2)
				{
					$this->createThumbnailFromJPEG($dateiname_fix, $dateiname_thumb);
					$mySQL->addField("med_thumb", 1, DB_NUMBER);
				} else
				{
					$mySQL->addField("med_thumb", 0, DB_NUMBER);
				}
				// IPTC auslesen
				if ($id == -1) // Nur als Erstkommentar moeglich
				{
					$s = $this->iptc($info);
					$mySQL->addField("med_comment", $s);
				}
			}
			$sql = $mySQL->update("media", "med_id=".$med_id);
			$myDB->query($sql);

			return ($med_id);

		} else
		{
			return false;
		}
	}

	function uploadVersion($fname, $bez, $med_id, $type, $mode = 0,$ver_id=0)
	{
		// $mode = 0 = Formupload
		// $mode = 1 = URL-Import
		global $myDB;
		global $myPT;

		$overwrite = false;
		
		switch ($mode)
		{
			case 0 :
				$dateiname_temp = $_FILES[$fname]["tmp_name"];
				$dateiname_original = $_FILES[$fname]["name"];
				$mimetype = $_FILES[$fname]["type"];
				$filesize = $_FILES[$fname]["size"];
				break;
			case 1 :
				$pfad = TEMPPATH."media/";
				if (!file_exists($pfad))
				{
					mkdir($pfad,UMASK);
				}
				$dateiname_temp = $pfad.uniqid("pt").".tmp";
				copy($fname, $dateiname_temp);
				$mimetype = "";
				$filesize = filesize($dateiname_temp);
				$dateiname_original = substr($fname, strrpos($fname, "/") + 1);
				break;
		}
		// Vorab Check, ob ein Bild auch ein Bild ist ...

		if ($type == MB_IMAGE)
		{

			$size = GetImageSize($dateiname_temp);
			if (!$size)
			{
				// Kein Bild
				return (false);
			}
			if ($size[2] < 1 OR $size[2] > 3)
			{
				// Kein akzeptiertes Format
				return (false);
			}
		}

		if ($filesize != 0)
		{
			
			if ($ver_id==0)
			{
				$mySQL = new SQLBuilder();
				$mySQL->addField("med_id", $med_id, DB_NUMBER);
				$sql = $mySQL->insert("mediaversion");
				$myDB->query($sql);
				$ver_id = mysql_insert_id();
			}
			else 
			{
				$overwrite = true;
				// overwrite existing version
				$sql = "SELECT * FROM media WHERE med_id=".$med_id;
				$rs = $myDB->query($sql);
				if (mysql_num_rows($rs) == 0)
				{
					return false;
				}
				$row = mysql_fetch_array($rs);
				$sql = "SELECT * FROM mediaversion WHERE ver_id=".$ver_id;
				$rs = $myDB->query($sql);
				if (mysql_num_rows($rs) == 0)
				{
					return false;
				}
				$row_version = mysql_fetch_array($rs);
				$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".sprintf("%06.0f", $med_id).",".$row_version["ver_id"].".".$row_version["ver_subtype"];
				@ unlink($dateiname);
			}

			$suffix = substr($dateiname_original, strrpos($dateiname_original, ".") + 1);

			// Zusammenbauen des Dateinamens
			if ($type == MB_IMAGE)
			{
				$myImg = new PhenotypeImage($med_id);
				$dateiname_fix = MEDIABASEPATH.$myImg->physical_folder."/".sprintf("%06.0f", $med_id).",".$ver_id.".".$suffix;
				$mySQL = new SQLBuilder();
				$mySQL->addField("ver_subtype", $suffix);
				$mySQL->addField("ver_mimetype", $mimetype);
				$size = GetImageSize($dateiname_temp);
				$mySQL->addField("ver_x", $size[0], DB_NUMBER);
				$mySQL->addField("ver_y", $size[1], DB_NUMBER);
				if ($overwrite==false)
				{
					if ($bez == "")
					{
						switch ($size[2])
						{
							case 1 :
								$bez = "g";
								break;
							case 2 :
								$bez = "j";
								break;
							case 3 :
								$bez = "p";
								break;
						}
						$bez .= $size[0]."x".$size[1];
					}
					
					// check version name for duplicates
				
				
					$sql = "SELECT COUNT(*) AS C FROM mediaversion WHERE ver_bez='" . $myPT->codeSQL($bez)."' AND med_id=".$med_id;
					$rs = $myDB->query($sql);
					$row = mysql_fetch_array($rs);
					if ($row["C"]!=0){$bez .= "_v".$ver_id;}
				}
				
				$mySQL->addField("ver_bez", $bez);
				$sql = $mySQL->update("mediaversion", "ver_id=".$ver_id);
				$myDB->query($sql);
			} else // TYPE = MB_DOCUMENT
			{
				$myDoc = new PhenotypeDocument($med_id);
				$pfad = MEDIABASEPATH.$myDoc->physical_folder."/".$ver_id;
				if (!file_exists($pfad))
				{
					mkdir($pfad, UMASK);
				}
				$dateiname_fix = $pfad."/".$myDoc->filename;
				$mySQL = new SQLBuilder();
				$mySQL->addField("ver_subtype", $suffix);
				$mySQL->addField("ver_mimetype", $mimetype);
				if ($overwrite==false)
				{
					if ($bez == "")
					{
						$bez = date("Y/m/d H:i");
					}
					
					// check version name for duplicates
				
					$sql = "SELECT COUNT(*) AS C FROM mediaversion WHERE ver_bez='" . $myPT->codeSQL($bez)."' AND med_id=".$med_id;
					$rs = $myDB->query($sql);
					$row = mysql_fetch_array($rs);
					if ($row["C"]!=0){$bez .= "_v".$ver_id;}
				}
				
				$mySQL->addField("ver_bez", $bez);
				$sql = $mySQL->update("mediaversion", "ver_id=".$ver_id);
				$myDB->query($sql);
			}

			// Kopieren bzw. Umbenennen der Datei
			if (file_exists($dateiname_fix))
			{
				unlink($dateiname_fix);
			}
			copy($dateiname_temp, $dateiname_fix);
			unlink($dateiname_temp);
			@ chmod($dateiname_fix, UMASK);

			$sql = "SELECT COUNT(*) AS C FROM mediaversion WHERE med_id=".$med_id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$mySQL = new SQLBuilder();
			$mySQL->addField("med_versioncount", $row["C"]);
			$sql = $mySQL->update("media", "med_id=".$med_id);
			$myDB->query($sql);

			return ($ver_id);
		} else
		{
			return false;
		}
	}

	function iptc($info)
	{
		if (isset ($info["APP13"]))
		{
			$iptc = iptcparse($info["APP13"]);

			$titel = @ $iptc["2#105"][0];
			$beschreibung = @ $iptc["2#120"][0];
			$quelle1 = @ $iptc["2#110"][0];
			$quelle2 = @ $iptc["2#115"][0];
			$stadt = @ $iptc["2#090"][0];
			$region = @ $iptc["2#095"][0];
			$land = @ $iptc["2#101"][0];
			$keywords = @ $iptc["2#020"][0];
			$s = "";
			$trenner = "#--------------------------------------------------------------------------------------\n";
			if ($titel != "")
			{
				$s .= $trenner."# Titel : ".$titel."\n";
			}
			if ($beschreibung != "")
			{
				$s .= $trenner."# Text : ".$beschreibung."\n";
			}
			if ($quelle1 != "")
			{
				$s .= $trenner."# Quelle : ".$quelle1."\n";
			}
			if ($quelle2 != "")
			{
				$s .= $trenner."# Quelle : ".$quelle2."\n";
			}
			if ($stadt != "")
			{
				$s .= $trenner."# Stadt : ".$stadt."\n";
			}
			if ($region != "")
			{
				$s .= $trenner."# Region : ".$region."\n";
			}
			if ($land != "")
			{
				$s .= $trenner."# Land : ".$land."\n";
			}
			if ($keywords != "")
			{
				$s .= $trenner."# Keywords : ".$keywords."\n";
			}
			if ($s != "")
			{
				$s = $trenner."# IPTC-Info ausgelesen am: ".date("d.m.Y")."\n".$s.$trenner;
			}
			return ($s);
		} else
		{
			return "";
		}
	}

	/**
	 * import image file into the mediabase
	 *
	 * @param unknown_type $logical_folder
	 * @param unknown_type $url
	 * @param unknown_type $mimetype
	 * @param unknown_type $img_id
	 * @param unknown_type $title
	 * @param unknown_type $physical_folder
	 * @return integer|boolean ID of the importer media object or false if something went wrong
	 */
	function importImageFromUrl($logical_folder, $url, $mimetype = "", $img_id = -1,$title="", $physical_folder)
	{
		global $myDB;

		
		if (!GetImageSize($url))
		{
			return false;
		}

		
		$action = "append";

		if ($img_id != -1)
		{
			$sql = "SELECT 1 FROM media WHERE med_id = ".$img_id;
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs) == 0)
			{
				$action = "insert";
			}
		}
		else
		{
			$action = "insert";
		}
		$logical_folder = $this->rewriteFolder($logical_folder);
		$p = strrpos((str_replace('\\','/',$url)), "/");
		if ($p)
		{
			$dateiname_original = substr($url, $p +1);
			$suffix = substr($dateiname_original, strrpos($dateiname_original, ".") + 1);
		} else
		{
			return false;
		}
		
		if ($mimetype!="")
		{
			switch ($mimetype)
			{
				case "image/jpeg":
					$suffix="jpg";
					break;
				case "image/png":
					$suffix="png";
					break;
				case "image/gif":
					$suffix="gif";
					break;
			}
		}

		
		
		if ($title=="")
		{
			$title = $dateiname_original;
		}
		
		$mySQL = new SQLBuilder();
		$mySQL->addField("med_bez", $title);
		$mySQL->addField("grp_id", $this->grp_id);
		$mySQL->addField("med_bez_original", $dateiname_original);
		$mySQL->addField("med_logical_folder1", $logical_folder);
		$mySQL->addField("med_date", time(), DB_NUMBER);
		$mySQL->addField("med_type", MB_IMAGE, DB_NUMBER);
		$mySQL->addField("med_mimetype", $mimetype);
		$mySQL->addField("med_subtype", $suffix);

		$mySQL->addField("usr_id", 1, DB_NUMBER); // Systemuser



		$inserted = 0;
		if ($action=="insert")
		{
			$inserted = 1;
			$mySQL->addField("med_creationdate", time(), DB_NUMBER);
			$mySQL->addField("usr_id_creator", $_SESSION["usr_id"], DB_NUMBER);
			if ($img_id != -1)
			{
				// force given ID
				$inserted = 0;
				$mySQL->addField("med_id",$img_id,DB_NUMBER);
			}
			$sql = $mySQL->insert("media");
			$myDB->query($sql);
			$med_id = mysql_insert_id();
			if ($physical_folder=="")
			{
				$folder = $this->createFolder($med_id, MB_IMAGE);
			}
			else
			{
				$folder = $physical_folder;
			}
		} else
		{
			$sql = "SELECT med_physical_folder FROM media WHERE med_id=".$img_id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$folder = $row["med_physical_folder"];
			$sql = $mySQL->update("media", "med_id=".$img_id);
			$myDB->query($sql);
			$med_id = $img_id;
		}

		// Zusammenbauen des Dateinamens

		$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".sprintf("%06.0f", $med_id).".".$suffix;
		$dateiname_thumb = MEDIABASEPATH.$this->grp_id."/".$folder."/".sprintf("%06.0f", $med_id)."_t.".$suffix;

		if (file_exists($dateiname_fix))
		{
			unlink($dateiname_fix);
		}

		// check, if the mediabasefolder exists
		$med_group_folder = MEDIABASEPATH.$this->grp_id;

		if (!file_exists($med_group_folder))
		{
			mkdir($med_group_folder,UMASK);
		}

		/* Das doch an der Stelle Blödsinn, oder?
		// Check auf den zweiten Ordner (nur bei Dokumenten)
		$p = strpos($folder,"/");
		if ($p!==false)
		{
		$topfolder = substr($folder,0,$p);
		$top_folder = $med_group_folder ."/".$topfolder;
		if (!file_exists($top_folder))
		{
		mkdir($top_folder,UMASK);
		}
		}
		*/

		// der eigentliche ordner
		$med_folder = $med_group_folder ."/".$folder;
		if (!file_exists($med_folder))
		{
			mkdir($med_folder,UMASK);
		}


		if (!@ copy($url, $dateiname_fix))
		{
			if ($inserted == 1) // to avoid emtpy entrys after broken uploads
			{
				$sql = "DELETE FROM media WHERE med_id=".$med_id;
				$myDB->query($sql);
			}
			return false;
		}
		@ chmod($dateiname_fix, UMASK);

		$mySQL = new SQLBuilder();
		$mySQL->addField("med_physical_folder", $folder);

		$size = GetImageSize($dateiname_fix, $info);

		$mySQL->addField("med_x", $size[0], DB_NUMBER);
		$mySQL->addField("med_y", $size[1], DB_NUMBER);

		// Thumbnail
		//if (strtolower($suffix) == "jpg" OR strtolower($suffix) == "jpeg")
		if ($size[2] == 2)
		{
			$this->createThumbnailFromJPEG($dateiname_fix, $dateiname_thumb);
			$mySQL->addField("med_thumb", 1, DB_NUMBER);
		} else
		{
			$mySQL->addField("med_thumb", 0, DB_NUMBER);
		}

		$s = $this->iptc($info);
		if ($s != "")
		{
			$mySQL->addField("med_comment", $s);
		}

		$sql = $mySQL->update("media", "med_id=".$med_id);
		$myDB->query($sql);

		return $med_id;
	}

	function importDocumentFromUrl($logical_folder, $url, $mimetype = "", $med_id = -1,$title="",$physical_folder="")
	{
		global $myDB;


		$action = "append";


		if ($med_id != -1)
		{
			$sql = "SELECT 1 FROM media WHERE med_id = ".$med_id;
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs) == 0)
			{
				$action = "insert";
			}
		}
		else
		{
			$action = "insert";
		}

		$logical_folder = $this->rewriteFolder($logical_folder);
		$p = strrpos((str_replace('\\','/',$url)), "/");
		if ($p)
		{
			$dateiname_original = substr($url, $p +1);
			$suffix = substr($dateiname_original, strrpos($dateiname_original, ".") + 1);
		} else
		{
			return false;
		}
		

		if ($title=="")
		{
			$title = $dateiname_original;
		}

		$mySQL = new SQLBuilder();
		$mySQL->addField("med_bez", $title);
		$mySQL->addField("grp_id", $this->grp_id);
		$mySQL->addField("med_logical_folder1", $logical_folder);
		$mySQL->addField("med_date", time(), DB_NUMBER);
		$mySQL->addField("med_type", MB_DOCUMENT, DB_NUMBER);
		$mySQL->addField("med_mimetype", $mimetype);
		$mySQL->addField("med_subtype", $suffix);
		$mySQL->addField("usr_id", 1, DB_NUMBER); // Systemuser

		$inserted = 0;
		if ($action == "insert")
		{
			$mySQL->addField("med_creationdate", time(), DB_NUMBER);
			$mySQL->addField("usr_id_creator", $_SESSION["usr_id"], DB_NUMBER);
			if ($med_id != -1)
			{
				// force given ID
				$inserted = 0;
				$mySQL->addField("med_id",$med_id,DB_NUMBER);
			}
			$sql = $mySQL->insert("media");
			$myDB->query($sql);
			$med_id = mysql_insert_id();
			if ($physical_folder=="")
			{
				$folder = $this->createFolder($med_id, MB_DOCUMENT);
			}
			else
			{
				$folder = $physical_folder;
			}
			$mySQL = new SQLBuilder();
			$mySQL->addField("med_physical_folder", $folder);
			$mySQL->addField("med_bez_original", $dateiname_original);
			$sql = $mySQL->update("media", "med_id=".$med_id);
			$myDB->query($sql);
			// Zusammenbauen des Dateinamens
			$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".$dateiname_original;
		} else
		{
			$sql = "SELECT med_physical_folder, med_bez_original FROM media WHERE med_id=".$med_id;
			$rs = $myDB->query($sql);
			$row = mysql_fetch_array($rs);
			$folder = $row["med_physical_folder"];
			$sql = $mySQL->update("media", "med_id=".$med_id);
			$myDB->query($sql);
			// Beibehalten des Dateinamens
			$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".$row["med_bez_original"];
			;
		}
		


		if (file_exists($dateiname_fix))
		{
			unlink($dateiname_fix);
		}
		else
		{
			// Maybe also the folder doesn't exist, so create it
			$testfolder = MEDIABASEPATH.$this->grp_id;
			if (!file_exists($testfolder))
			{
				mkdir($testfolder,UMASK);
			}
			$p = strpos($folder,"/");
			$testfolder .= "/".substr($folder,0,$p);
			if (!file_exists($testfolder))
			{
				mkdir($testfolder,UMASK);
			}
			$testfolder .= "/".substr($folder,$p+1);
			if (!file_exists($testfolder))
			{
				mkdir($testfolder,UMASK);
			}
		}
		if (! @copy($url, $dateiname_fix))
		{
			if ($inserted == 1)
			{
				$sql = "DELETE FROM media WHERE med_id=".$med_id;
				$myDB->query($sql);
			}
			return false;
		}
		@ chmod($dateiname_fix, UMASK);

		return ($med_id);
	}

	function import($dateiname_original, $documentonly = 0, $mediabasefolder = "", $importfolder = "",$usr_id=0)
	{
		if ($mediabasefolder == "")
		{
			$mediabasefolder = "_import";
		} else
		{
			$mediabasefolder = $this->rewriteFolder($mediabasefolder);
		}
		if ($importfolder == "")
		{
			$importfolder = MEDIABASEPATH."import/";
			if ($usr_id==0)
			{
				global $mySUser;
				$usr_id = $mySUser->id;		
			}
			$importfolder .= $usr_id ."/";
		}

		global $myDB;
		$suffix = substr($dateiname_original, strrpos($dateiname_original, ".") + 1);
		$type = MB_DOCUMENT;

		if ($documentonly == 0)
		{
			// Bilder als Bilder handhaben
			if (strtolower($suffix) == "jpg" OR strtolower($suffix) == "jpeg" OR strtolower($suffix) == "gif" OR strtolower($suffix) == "png")
			{
				$type = MB_IMAGE;
			}
		}

		$mySQL = new SQLBuilder();
		$mySQL->addField("med_bez", $dateiname_original);
		$mySQL->addField("grp_id", $this->grp_id);
		$mySQL->addField("med_bez_original", $dateiname_original);
		$mySQL->addField("med_logical_folder1", $mediabasefolder);
		$mySQL->addField("med_creationdate", time(), DB_NUMBER);
		$mySQL->addField("usr_id_creator", $_SESSION["usr_id"], DB_NUMBER);
		$mySQL->addField("med_date", time(), DB_NUMBER);
		$mySQL->addField("med_type", $type, DB_NUMBER);
		$mySQL->addField("med_subtype", $suffix);
		$mySQL->addField("usr_id", $_SESSION["usr_id"], DB_NUMBER);
		$sql = $mySQL->insert("media");
		$myDB->query($sql);
		$med_id = mysql_insert_id();

		$folder = $this->createFolder($med_id, $type);

		// Zusammenbauen des Dateinamens

		if ($type == MB_IMAGE)
		{
			$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".sprintf("%06.0f", $med_id).".".$suffix;
			$dateiname_thumb = MEDIABASEPATH.$this->grp_id."/".$folder."/".sprintf("%06.0f", $med_id)."_t.".$suffix;
		} else
		{
			$dateiname_fix = MEDIABASEPATH.$this->grp_id."/".$folder."/".$dateiname_original;
		}

		if (file_exists($dateiname_fix))
		{
			unlink($dateiname_fix);
		}
		copy($importfolder.$dateiname_original, $dateiname_fix);
		@ chmod($dateiname_fix, UMASK);

		$mySQL = new SQLBuilder();
		$mySQL->addField("med_physical_folder", $folder);
		if ($type == MB_IMAGE)
		{
			$size = GetImageSize($dateiname_fix, $info);

			$mySQL->addField("med_x", $size[0], DB_NUMBER);
			$mySQL->addField("med_y", $size[1], DB_NUMBER);

			// Thumbnail
			//if (strtolower($suffix) == "jpg" OR strtolower($suffix) == "jpeg")
			if ($size[2] == 2)
			{
				$this->createThumbnailFromJPEG($dateiname_fix, $dateiname_thumb);
				$mySQL->addField("med_thumb", 1, DB_NUMBER);
			} else
			{
				$mySQL->addField("med_thumb", 0, DB_NUMBER);
			}

			$s = $this->iptc($info);
			if ($s != "")
			{
				$mySQL->addField("med_comment", $s);
			}
		}

		$sql = $mySQL->update("media", "med_id=".$med_id);
		$myDB->query($sql);

		@ unlink($importfolder.$dateiname_original);
		return ($med_id);
	}

	function createThumbnailFromJpeg($dateiname_complete, $dateiname_thumb, $kante = 90, $y = 0,$quality=0)
	{
		// wenn $y gesetzt ist, ist $kante = Breite
		$size = GetImageSize($dateiname_complete);
		$sourceImage = imagecreatefromjpeg($dateiname_complete);
		$sx = $size[0];
		$sy = $size[1];
		if ($y == 0)
		{
			if ($sx > $sy)
			{
				$tx = $kante;
				$ty = round($kante * ($sy / $sx));
			} else
			{
				$tx = round($kante * ($sx / $sy));
				$ty = $kante;
			}
		} else
		{
			$tx=$kante;
			$ty=$y;
		}
		$targetImage = imagecreatetruecolor($tx, $ty);
		//imageantialias($targetImage,1);
		if (function_exists(imagecopyresampled))
		{
			imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $tx, $ty, $sx, $sy);
		} else
		{
			imagecopyresized($targetImage, $sourceImage, 0, 0, 0, 0, $tx, $ty, $sx, $sy);
		}

		
		if ($dateiname_thumb==""){$dateiname_thumb=null;}
		if ($quality !=0)
		{
			ImageJPEG($targetImage, $dateiname_thumb,$quality);
		}
		else
		{
			if ($dateiname_thumb==null)
			{
				ImageJPEG($targetImage);
			}
			else 
			{
				ImageJPEG($targetImage, $dateiname_thumb);	
			}
		}
		@ chmod($dateiname_thumb, UMASK);
		return ($targetImage);
	}

	function createFolder($med_id, $type)
	{

		$med_group_folder = MEDIABASEPATH.$this->grp_id;

		//echo $med_group_folder;
		if (!file_exists($med_group_folder))
		{
			mkdir($med_group_folder,UMASK);
		}

		if ($type == MB_IMAGE)
		{
			$folder = "I".date("ymW") . strtoupper(dechex(floor($med_id/128)));
		} else
		{
			$folder = "D".date("ymW") . strtoupper(dechex(floor($med_id/128)));
		}

		$med_physical_folder = MEDIABASEPATH.$this->grp_id."/".$folder;

		//echo $med_physical_folder;
		if (!file_exists($med_physical_folder))
		{
			mkdir($med_physical_folder,UMASK);
		}

		// Bei Dokumenten noch ein Folder dazu
		if ($type == MB_DOCUMENT)
		{
			$subfolder = sprintf("%04.0f", $med_id).date("hid");
			$med_physical_folder .= "/".$subfolder;
			if (!file_exists($med_physical_folder))
			{
				mkdir($med_physical_folder,UMASK);
			}
			$folder .= "/".$subfolder;
		}
		return $folder;
	}

	function deleteMediaObject($id)
	{
		if ($id == 0)
		{
			return false;
		}

		global $myDB;
		$sql = "SELECT * FROM media WHERE med_id = ".$id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);

		if ($row["med_type"] == MB_DOCUMENT)
		{
			// Zunächst die Versionen
			$sql = "SELECT * FROM mediaversion WHERE med_id=".$id;
			$rs = $myDB->query($sql);
			while ($row_version = mysql_fetch_array($rs))
			{
				$pfad = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".$row_version["ver_id"];
				$dateiname = $pfad."/".$row["med_bez_original"];
				@ unlink($dateiname);
				@ unlink($pfad);
			}

			$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".$row["med_bez_original"];
			@ unlink($dateiname);
			$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"];
			@ rmdir($dateiname);
		} else
		{
			// Zunächst die Versionen
			$sql = "SELECT * FROM mediaversion WHERE med_id=".$id;
			$rs = $myDB->query($sql);
			while ($row_version = mysql_fetch_array($rs))
			{
				$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".sprintf("%06.0f", $id).",".$row_version["ver_id"].".".$row["med_subtype"];
				@ unlink($dateiname);
			}

			$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".sprintf("%06.0f", $id).".".$row["med_subtype"];
			@ unlink($dateiname);
			$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".sprintf("%06.0f", $id)."_t.".$row["med_subtype"];
			@ unlink($dateiname);

			// Jetzt den ganzen Ordner, falls er leer ist
			$fp = opendir(MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]);
			$empty = true;
			while ($file = readdir($fp))
			{
				if ($file != "." && $file != "..")
				{
					$empty = false;
					break;
				}
			}
			if ($empty)
			{
				$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"];
				@ rmdir($dateiname);
			}
		}

		$sql = "DELETE FROM mediaversion WHERE med_id=".$id;
		$myDB->query($sql);

		$sql = "DELETE FROM media WHERE med_id = ".$id;
		$myDB->query($sql);
	}

	function deleteMediaObjectVersion($med_id, $ver_id)
	{
		global $myDB;
		$sql = "SELECT * FROM media WHERE med_id = ".$med_id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs) == 0)
		{
			return false;
		}
		$row = mysql_fetch_array($rs);
		if ($row["med_type"] == MB_IMAGE)
		{
			$sql = "SELECT * FROM mediaversion WHERE ver_id=".$ver_id;
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs) == 0)
			{
				return false;
			}
			$row_version = mysql_fetch_array($rs);
			$dateiname = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".sprintf("%06.0f", $med_id).",".$row_version["ver_id"].".".$row_version["ver_subtype"];
			@ unlink($dateiname);
		} else
		{
			$sql = "SELECT * FROM mediaversion WHERE ver_id=".$ver_id;
			$rs = $myDB->query($sql);
			if (mysql_num_rows($rs) == 0)
			{
				return false;
			}
			$row_version = mysql_fetch_array($rs);

			$pfad = MEDIABASEPATH.$row["grp_id"]."/".$row["med_physical_folder"]."/".$row_version["ver_id"];
			$dateiname = $pfad."/".$row["med_bez_original"];
			@ unlink($dateiname);
			@ rmdir($pfad);
		}

		$sql = "DELETE FROM mediaversion WHERE ver_id=".$ver_id;
		$rs = $myDB->query($sql);

		$sql = "SELECT COUNT(*) AS C FROM mediaversion WHERE med_id=".$med_id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		$mySQL = new SQLBuilder();
		$mySQL->addField("med_versioncount", $row["C"]);
		$sql = $mySQL->update("media", "med_id=".$med_id);
		$myDB->query($sql);

	}

	function rawXMLExport()
	{
		global $myPT;
		global $myDB;

		$xml ='<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<ptversion>'.$myPT->version.'</ptversion>
		<ptsubversion>'.$myPT->subversion.'</ptsubversion>
	</meta>
	<mediagroups>';
		$sql = "SELECT * FROM mediagroup ORDER BY grp_id";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			$xml .='
		<group>
			<grp_id>'.$row["grp_id"].'</grp_id>
			<grp_bez>'.$myPT->codeX($row["grp_bez"]).'</grp_bez>
			<grp_description>'.$myPT->codeX($row["grp_description"]).'</grp_description>
			<grp_type>'.$myPT->codeX($row["grp_type"]).'</grp_type>
		 </group>';
		}
		$xml.='
	</mediagroups>
</phenotype>';
		return $xml;
	}

	function rawXMLImport($buffer)
	{
		global $myDB;

		$_xml = @simplexml_load_string($buffer);
		if ($_xml)
		{
			foreach ($_xml->mediagroups->group AS $_xml_group)
			{
				$grp_id = (int)utf8_decode($_xml_group->grp_id);
				$grp_bez = (string)utf8_decode($_xml_group->grp_bez);
				$grp_description = (string)utf8_decode($_xml_group->description);
				$grp_type = (int)utf8_decode($_xml_group->grp_type);

				$sql  ="DELETE FROM mediagroup WHERE grp_id=".$grp_id;
				$myDB->query($sql);

				$mySQL = new SQLBuilder();
				$mySQL->addField("grp_id",$grp_id,DB_NUMBER);
				$mySQL->addField("grp_type",$grp_type,DB_NUMBER);
				$mySQL->addField("grp_bez",$grp_bez);
				$mySQL->addField("grp_description",$grp_description);
				$sql = $mySQL->insert("mediagroup");
				$myDB->query($sql);

			}
		}
	}





	function unsharpMask($img, $amount, $radius, $threshold)    {

		////////////////////////////////////////////////////////////////////////////////////////////////
		////
		////                  Unsharp Mask for PHP - version 2.0
		////
		////    Unsharp mask algorithm by Torstein Hønsi 2003-06.
		////             thoensi_at_netcom_dot_no.
		////               Please leave this notice.
		////
		///////////////////////////////////////////////////////////////////////////////////////////////


		// $img is an image that is already created within php using
		// imgcreatetruecolor. No url! $img must be a truecolor image.

		/*

		WARNING! Due to a known bug in PHP 4.3.2 this script is not working well in this version. The sharpened images get too dark. The bug is fixed in version 4.3.3.

		From version 2 (July 17 2006) the script uses the imageconvolution function in PHP version >= 5.1, which improves the performance considerably.

		Unsharp masking is a traditional darkroom technique that has proven very suitable for
		digital imaging. The principle of unsharp masking is to create a blurred copy of the image
		and compare it to the underlying original. The difference in colour values
		between the two images is greatest for the pixels near sharp edges. When this
		difference is subtracted from the original image, the edges will be
		accentuated.

		The Amount parameter simply says how much of the effect you want. 100 is 'normal'.
		Radius is the radius of the blurring circle of the mask. 'Threshold' is the least
		difference in colour values that is allowed between the original and the mask. In practice
		this means that low-contrast areas of the picture are left unrendered whereas edges
		are treated normally. This is good for pictures of e.g. skin or blue skies.

		Any suggenstions for improvement of the algorithm, expecially regarding the speed
		and the roundoff errors in the Gaussian blur process, are welcome.

		*/


		// Attempt to calibrate the parameters to Photoshop:
		if ($amount > 500)    $amount = 500;
		$amount = $amount * 0.016;
		if ($radius > 50)    $radius = 50;
		$radius = $radius * 2;
		if ($threshold > 255)    $threshold = 255;

		$radius = abs(round($radius));     // Only integers make sense.
		if ($radius == 0) return $img;
		$w = imagesx($img); $h = imagesy($img);
		$imgCanvas = imagecreatetruecolor($w, $h);
		$imgCanvas2 = imagecreatetruecolor($w, $h);
		$imgBlur = imagecreatetruecolor($w, $h);
		$imgBlur2 = imagecreatetruecolor($w, $h);
		imagecopy ($imgCanvas, $img, 0, 0, 0, 0, $w, $h);
		imagecopy ($imgCanvas2, $img, 0, 0, 0, 0, $w, $h);


		// Gaussian blur matrix:
		//
		//    1    2    1
		//    2    4    2
		//    1    2    1
		//
		//////////////////////////////////////////////////

		imagecopy      ($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h); // background

		for ($i = 0; $i < $radius; $i++)    {

			if (function_exists('imageconvolution')) { // PHP >= 5.1
				$matrix = array(
				array( 1, 2, 1 ),
				array( 2, 4, 2 ),
				array( 1, 2, 1 )
				);
				imageconvolution($imgCanvas, $matrix, 16, 0);

			} else {

				// Move copies of the image around one pixel at the time and merge them with weight
				// according to the matrix. The same matrix is simply repeated for higher radii.

				imagecopy      ($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1); // up left
				imagecopymerge ($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50); // down right
				imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333); // down left
				imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25); // up right

				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333); // left
				imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25); // right
				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 ); // up
				imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // down

				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50); // center
				imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);

				// During the loop above the blurred copy darkens, possibly due to a roundoff
				// error. Therefore the sharp picture has to go through the same loop to
				// produce a similar image for comparison. This is not a good thing, as processing
				// time increases heavily.
				imagecopy ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h);
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 20 );
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 16.666667);
				imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
				imagecopy ($imgCanvas2, $imgBlur2, 0, 0, 0, 0, $w, $h);

			}
		}
		//return $imgBlur;

		// Calculate the difference between the blurred pixels and the original
		// and set the pixels
		for ($x = 0; $x < $w; $x++)    { // each row
			for ($y = 0; $y < $h; $y++)    { // each pixel

				$rgbOrig = ImageColorAt($imgCanvas2, $x, $y);
				$rOrig = (($rgbOrig >> 16) & 0xFF);
				$gOrig = (($rgbOrig >> 8) & 0xFF);
				$bOrig = ($rgbOrig & 0xFF);

				$rgbBlur = ImageColorAt($imgCanvas, $x, $y);

				$rBlur = (($rgbBlur >> 16) & 0xFF);
				$gBlur = (($rgbBlur >> 8) & 0xFF);
				$bBlur = ($rgbBlur & 0xFF);

				// When the masked pixels differ less from the original
				// than the threshold specifies, they are set to their original value.
				$rNew = (abs($rOrig - $rBlur) >= $threshold)
				? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))
				: $rOrig;
				$gNew = (abs($gOrig - $gBlur) >= $threshold)
				? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))
				: $gOrig;
				$bNew = (abs($bOrig - $bBlur) >= $threshold)
				? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))
				: $bOrig;



				if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
					$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
					ImageSetPixel($img, $x, $y, $pixCol);
				}
			}
		}

		return $img;

	}

}
?>