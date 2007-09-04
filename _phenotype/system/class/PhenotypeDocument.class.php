<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael 
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
class PhenotypeDocument extends PhenotypeMediaObject
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