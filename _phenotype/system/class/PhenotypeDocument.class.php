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
class PhenotypeDocumentStandard extends PhenotypeMediaObject
{
  public $type = MB_DOCUMENT;
  
  function __construct($img_id)
  {
    global $myDB;
	$img_id = (int)$img_id;
	
	$sql = "SELECT * FROM media WHERE med_id = " . $img_id . " AND med_type = " . MB_DOCUMENT;
    $rs = $myDB->query($sql);
	if (mysql_num_rows($rs)!=0)
	{
	  $row = mysql_fetch_array($rs);
	  $this->id = $row["med_id"];
	  $this->grp_id = $row["grp_id"];
	  $this->versioncount = $row["med_versioncount"];
	  $this->bez = $row["med_bez"];
      $this->bez_original = $row["med_bez_original"];	  
	  $this->keywords = $row["med_keywords"];
	  $this->comment = $row["med_comment"];	  	  
	  $this->usr_id = $row["usr_id"];
	  $this->cdate = $row["med_date"];	  
	  $this->usr_id_creator = $row["usr_id_creator"];
	  $this->creationdate = $row["med_creationdate"];
	  $this->physical_folder = $this->grp_id . "/" . $row["med_physical_folder"];
	  $this->logical_folder1 = $row["med_logical_folder1"];
	  $this->logical_folder2 = $row["med_logical_folder3"];
	  $this->logical_folder2 = $row["med_logical_folder3"];
	  $this->filename = $row["med_bez_original"];
	  $this->url = MEDIABASEURL . $this->physical_folder . "/" . $this->filename;
	  $this->suffix = $row["med_subtype"];
	  $this->mimetype = $row["med_mimetype"];
	  $this->file = MEDIABASEPATH . $this->physical_folder . "/" . $this->filename;
	  
	  $this->loaded=1;
	}
	else
	{
	$this->id=0;
	}
  }
}
?>