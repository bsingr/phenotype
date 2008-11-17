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
// www.phenotype.de - offical homepage
// www.phenotype-cms.de - documentation
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeMediaObjectStandard
{
	public $id = 0;
	public $ver_id =0;
	public $type = 0;
	public $bez;
	public $ver_bez;
	public $alt;
	public $suffix;
	public $mimetype;
	public $physical_folder;
	public $logical_folder1;
	public $logical_folder2;
	public $logical_folder3;
	public $url;

	public $loaded = 0;


	function getUrl ()
	{
		return (MEDIABASEURL . $this->physical_folder . "/" . $this->filename);
	}

	function hasVersions()
	{
		global $myDB;
		$sql = "SELECT COUNT(*) AS C FROM mediaversion WHERE med_id = " . $this->id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);
		if ($row["C"]>0){return true;}
		return false;

	}

	function selectVersionID($ver_id)
	{
		global $myDB;

		if ($ver_id==0)
		{
			if (get_class($this)!="PhenotypeMediaObject") // nur in abgeleiteten Klassen
			{
				$this->__construct($this->id);
				return true;
			}
			return (false);
		}

		$sql = "SELECT * FROM mediaversion WHERE med_id = " . $this->id . " AND ver_id=" . $ver_id;
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)!=0)
		{
			$row = mysql_fetch_array($rs);
			$this->initVersion($row);
			return true;
		}
		else
		{
			return false;
		}
	}

	function selectVersion($name)
	{
		global $myDB;
		$sql = "SELECT * FROM mediaversion WHERE med_id = " . $this->id . " AND ver_bez='" .addslashes($name)."'"; //:TODO: is addslashes apropriate here?
		$rs = $myDB->query($sql);
		if (mysql_num_rows($rs)!=0)
		{
			$row = mysql_fetch_array($rs);
			$this->initVersion($row);
			return true;
		}
		else
		{
			return false;
		}
	}

	function initVersion($row)
	{

		$this->ver_id = $row["ver_id"];
		$this->ver_bez = $row["ver_bez"];
		if ($this->type == MB_IMAGE)
		{
			$this->filename = sprintf("%06.0f", $this->id) .",".$this->ver_id."." . $row["ver_subtype"];
			$this->filename_thumb = $this->filename;
			$this->url = MEDIABASEURL . $this->physical_folder . "/" . $this->filename;
			$this->thumburl = MEDIABASEURL . $this->physical_folder . "/" . $this->filename_thumb;
			$this->x = $row["ver_x"];
			$this->y = $row["ver_y"];
			$this->file = MEDIABASEPATH . $this->physical_folder . "/" . $this->filename;
		}
		else
		{
			$this->url = MEDIABASEURL . $this->physical_folder . "/".$this->ver_id."/" . $this->filename;
			$this->file = MEDIABASEPATH . $this->physical_folder . "/" .$this->ver_id."/". $this->filename;
		}
	}

	function rawXMLExport($importmethod="overwrite")
	{
		global $myDB;
		global $myPT;


		$sql = 'SELECT * FROM media WHERE med_id='. $this->id;
		$rs = $myDB->query($sql);
		$row = mysql_fetch_array($rs);


		$buffer = @file_get_contents($this->file);

		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<phenotype>
	<meta>
		<med_id>'.$myPT->codeX($row['med_id']).'</med_id>
		<med_type>'.$myPT->codeX($row['med_type']).'</med_type>
		<med_mimetype>'.$myPT->codeX($row['med_mimetype']).'</med_mimetype>
		<med_id_local>'.$myPT->codeX($row['med_id']).'</med_id_local>
		<importmethod>'.$myPT->codeX($importmethod).'</importmethod>
		<grp_id>'.$myPT->codeX($row['grp_id']).'</grp_id>		
	</meta>
	<content>
		<med_bez>'.$myPT->codeX($row['med_bez']).'</med_bez>
		<med_bez_original>'.$myPT->codeX($row['med_bez_original']).'</med_bez_original>
		<med_keywords>'.$myPT->codeX($row['med_keywords']).'</med_keywords>
		<med_comment>'.$myPT->codeX($row['med_comment']).'</med_comment>		
		<med_physical_folder>'.$myPT->codeX($row['med_physical_folder']).'</med_physical_folder>   	 
		<med_logical_folder1>'.$myPT->codeX($row['med_logical_folder1']).'</med_logical_folder1>
		<med_logical_folder2>'.$myPT->codeX($row['med_logical_folder2']).'</med_logical_folder2>
		<med_logical_folder3>'.$myPT->codeX($row['med_logical_folder3']).'</med_logical_folder3>
		<usr_id_creator>'.$myPT->codeX($row['usr_id_creator']).'</usr_id_creator>
		<med_creationdate>'.$myPT->codeX($row['med_creationdate']).'</med_creationdate>
		<usr_id>'.$myPT->codeX($row['usr_id']).'</usr_id>
		<med_date>'.$myPT->codeX($row['med_date']).'</med_date>
		<binary>'.base64_encode($buffer).'</binary>
		<versions>';

		$sql = "SELECT * FROM mediaversion WHERE med_id = " . $this->id;
		$rs = $myDB->query($sql);
		while ($row = mysql_fetch_array($rs))
		{
			$this->initVersion($row);
			$buffer = file_get_contents($this->file);
			$xml .='<version>
	  	<ver_bez>'.$myPT->codeX($row['ver_bez']).'</ver_bez>
	  	<ver_subtype>'.$myPT->codeX($row['ver_subtype']).'</ver_subtype>
	  	<ver_mimetype>'.$myPT->codeX($row['ver_mimetype']).'</ver_mimetype>
	  	<binary>'.base64_encode($buffer).'</binary>
	  	</version>
	  	';
		}
		$xml.='</versions>
	</content>
</phenotype>';


		return $xml;
	}

	function rawXMLImport($buffer)
	{
		global $myDB;

		$_xml = @simplexml_load_string($buffer);

		if ($_xml)
		{
	
			$med_id = (int)utf8_decode($_xml->meta->med_id);
			$med_type = (int)utf8_decode($_xml->meta->med_type);
			$importmethod = (string)utf8_decode($_xml->meta->importmethod);
			$keepid = (int)utf8_decode($_xml->meta->keepid);
			$physical_folder = (string)utf8_decode($_xml->content->med_physical_folder);


			$grp_id = (int)utf8_decode($_xml->meta->grp_id);

			if ($importmethod=="append")
			{
				$med_id=-1;
				$physical_folder="";
			}

			$myMB = new PhenotypeMediabase();
			$myMB->setMediaGroup($grp_id);

			$file = (string)utf8_decode($_xml->content->med_bez_original);

			$path = TEMPPATH . "media/";

			if (!file_exists($path))
			{
				mkdir($path);
				umask($path,UMASK);
			}

			$buffer = (string)utf8_decode($_xml->content->binary);
			$buffer = base64_decode($buffer);

			$dateiname = $path .$file;
			$fp = fopen ($dateiname,"w");
			fputs ($fp,$buffer);
			fclose ($fp);
			@chmod ($dateiname,UMASK);


			if ($med_type == MB_IMAGE)
			{
				$med_id = $myMB->importImageFromUrl("_import",$dateiname,"",$med_id,$physical_folder);
			}
			else
			{
				$med_id = $myMB->importDocumentFromUrl("_import",$dateiname,"",$med_id,"",$physical_folder);
			}


			unlink ($dateiname);


			if ($med_id)
			{
				$mySQL = new SqlBuilder();
				$_fields = Array("med_bez","med_keywords","med_comment","med_logical_folder1","med_logical_folder2","med_logical_folder3","usr_id_creator","med_creationdate","usr_id","med_date");
				foreach ($_fields AS $k)
				{
					$data = (string)utf8_decode($_xml->content->$k);
					$mySQL->addField($k,$data);
				}
				$sql = $mySQL->update("media","med_id=".$med_id);
				$myDB->query($sql);



				// Alte Versionen löschen

				$sql = "SELECT * FROM mediaversion WHERE med_id = ".$med_id;
				$rs = $myDB->query($sql);
				while ($row=mysql_fetch_array($rs))
				{
					$myMB->deleteMediaObjectVersion($med_id,$row["ver_id"]);
				}



				// Neue Versionen importieren
				foreach ($_xml->content->versions->version AS $_xml_version)
				{
					$buffer = (string)utf8_decode($_xml_version->binary);
					$buffer = base64_decode($buffer);

					$ver_subtype =(string)utf8_decode($_xml_version->ver_subtype);
					$dateiname = $path ."versionbinary." .$ver_subtype;
					$fp = fopen ($dateiname,"w");
					fputs ($fp,$buffer);
					fclose ($fp);
					@chmod ($dateiname,UMASK);

					$bez = (string)utf8_decode($_xml_version->ver_bez);
					if ($med_type == MB_IMAGE)
					{
						$ver_id = $myMB->importImageVersionFromUrl($dateiname,$bez,$med_id);
					}
					else
					{
						$ver_id = $myMB->importDocumentVersionFromUrl($dateiname,$bez,$med_id);
					}

					unlink ($dateiname);

					if ($ver_id)
					{
						$mySQL = new SqlBuilder();
						$ver_mimetype =(string)utf8_decode($_xml_version->ver_mimetype);
						$mySQL->addField("ver_mimetype",$ver_mimetype);
						$sql = $mySQL->update("mediaversion","ver_id=".$ver_id);
						$myDB->query($sql);
					}
				}
			}

			if ($med_id==-1){$med_id=0;}

			return ($med_id);

		}
		else
		{
			return (false);
		}

	}


	/**
	 * writes an actual snapshot of the mediaobject into the snapshot table and
	 * returns the snapshot-xml
	 *
	 * @param int $usr_id
	 * @return string
	 */

	function snapshotData($usr_id)
	{
		global $myDB;
		global $myLog;
		$xml = $this->rawXMLExport();

		$mySQL = new SQLBuilder();
		$mySQL->addField("sna_type","MO");
		$mySQL->addField("key_id",$this->id,DB_NUMBER);
		$mySQL->addField("sec_id",$this->grp_id,DB_NUMBER);
		$mySQL->addField("sna_date",time(),DB_NUMBER);
		$mySQL->addField("usr_id",$usr_id,DB_NUMBER);
		$mySQL->addField("sna_zip",0,DB_NUMBER);
		// too many problems with gzcompress, maybe in a later version
		//$mySQL->addField("sna_xml",gzcompress($xml));
		$mySQL->addField("sna_xml",$xml);
		$mySQL->addField("sna_sync",0,DB_NUMBER);
		$sql = $mySQL->insert("snapshot");
		$myDB->query($sql);

		$myLog->log("Snapshot " . mysql_insert_id() . " zu Mediaobjekt " . $this->id . " erstellt.",PT_LOGFACILITY_SYS);
		
		return $xml;
	}

	/**
	 * alias of function snapshotData
	 *
	 * @param integer $usr_id
	 * @param string $mode meaningless ... may change sometime
	 * @return string
	 */

	function snapshot($usr_id=1,$mode="data")
	{
		return $this->snapshotData($usr_id);
	}

}
?>