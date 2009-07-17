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
class PhenotypeDataObjectStandard extends PhenotypeBase
{
  const dao_application = 0;
  const dao_system = 1;

  public $dao_type = self::dao_application;

  public $bez="";
  public $paramhash="";
  public $clearOnEdit = 0;

  public $stored = false;
  public $loaded = false;
  public $changed= false;

  function __construct($bez,$params=array(), $forceBuild=false,$clearOnEdit = false)
  {
    global $myDB;

    if ($bez=="")
    {
      throw new Exception ("Cannot create unnamed dataobject");
    }
    $this->bez = $bez;

    $paramhash="";
    foreach ($params AS $k => $v)
    {
      $paramhash  .="#".$k."#".$v;
    }
    $this->paramhash = $paramhash;


    if ($clearOnEdit)
    {
      // this information is stored in the database and utilized elsewhere
      $this->clearOnEdit = 1;
    }

    if (!$forceBuild)
    {
      $sql = "SELECT * FROM dataobject WHERE dao_bez='". mysql_escape_string($bez)."' AND dao_params ='".mysql_escape_string($paramhash)."' AND dao_type=".
      $this->dao_type;
      $rs = $myDB->query($sql,"DAO \"".$bez."\": initialization");

      if (mysql_num_rows($rs)==0)
      {
        $forceBuild = true;
      }
      else
      {
        $row = mysql_fetch_array($rs);
        $this->_props = unserialize($row["dao_props"]);
        $this->loaded = true;
      }
      $this->bez = $bez;
    }

    if ($forceBuild)
    {
      $method = "buildData" .$bez;
      if (method_exists($this,$method))
      {
        call_user_func(array($this,$method),$params);
      }
    }

  }


  function isLoaded()
  {
    return $this->loaded;
  }

  function __destruct()
  {
    if ($this->changed == true AND $this->stored==false)
    {
      trigger_error("Dataobject " . $this->bez. " (".$this->paramhash.")  changed, but not stored.",E_USER_NOTICE);
    }
  }


  function store($seconds=0,$clearOnEdit=null)
  {
    global $myDB;
    
    /**
     * Since the Phenotype destructor can access data objects, it is possible that the global database object already has been destroyed
     * If so, the database must be reconnected.
     */
    if (!is_object($myDB))
    {
      $myDB = new PhenotypeDatabase();
      $myDB->connect();
    }

    $context="DAO \"".$this->bez."\": stored.";
        
    if ($clearOnEdit!==null)
    {
      $this->clearOnEdit = 1;
    }
    
    if ($seconds!=0)
    {
      $seconds = time()+$seconds;
      $context = $context ." Valid until ". (date('d.m.Y H:i',$seconds));
    }
        
    $sql = "DELETE FROM dataobject WHERE dao_bez='". mysql_escape_string($this->bez)."' AND dao_params ='".$this->paramhash."'";
    $myDB->query($sql,$context);

    $mySQL = new SqlBuilder();
    $mySQL->addField("dao_bez",$this->bez);
    $mySQL->addField("dao_params",$this->paramhash);
    $mySQL->addField("dao_type",$this->dao_type);
    $mySQL->addField("dao_props",serialize($this->_props));

    $mySQL->addField("dao_ttl",$seconds,DB_NUMBER);
    $mySQL->addField("dao_date",time(),DB_NUMBER);
    $mySQL->addField("dao_clearonedit",$this->clearOnEdit,DB_NUMBER);
    $sql = $mySQL->insert("dataobject");
    $myDB->query($sql);
    $this->stored = true;
  }


  /*

  function buildSystemData($bez)
  {
  global $myDB;

  if (substr($bez,0,22)=="lightbox_media_usr_id_")
  {
  $usr_id = substr($bez,22);
  $bez="lightbox_media";
  }

  if (substr($bez,0,22)=="export_content_con_id_")
  {
  $con_id = substr($bez,22);
  $bez="export_content";
  }
  if (substr($bez,0,20)=="export_media_grp_id_")
  {
  $grp_id = substr($bez,20);
  $bez="export_media";
  }

  if (substr($bez,0,20)=="export_pages_grp_id_")
  {
  $grp_id = substr($bez,20);
  $bez="export_pages";
  }

  if (substr($bez,0,22)=="export_tickets_sbj_id_")
  {
  $sbj_id = substr($bez,22);
  $bez="export_tickets";
  }

  switch ($bez)
  {
  case "lightbox_media":
  $bez="lightbox_media_usr_id_".$usr_id;
  $props = Array("objects"=>Array());
  break;
  case "export_pages":
  $bez="export_pages_grp_id_".$grp_id;
  $_ids = Array();
  $sql = "SELECT pag_id FROM page WHERE grp_id = " . $grp_id;
  $rs = $myDB->query($sql);
  while ($row=mysql_fetch_array($rs))
  {
  $_ids[] = $row["pag_id"];
  }
  $props = Array("objects"=>$_ids);
  break;
  case "export_content":
  $bez="export_content_con_id_".$con_id;
  $_ids = Array();
  $sql = "SELECT dat_id FROM content_data WHERE con_id = " . $con_id;
  $rs = $myDB->query($sql);
  while ($row=mysql_fetch_array($rs))
  {
  $_ids[] = $row["dat_id"];
  }
  $props = Array("objects"=>$_ids);
  break;
  case "export_media":
  $bez="export_media_grp_id_".$grp_id;
  $_ids = Array();
  $sql = "SELECT med_id FROM media WHERE grp_id = " . $grp_id;
  $rs = $myDB->query($sql);
  while ($row=mysql_fetch_array($rs))
  {
  $_ids[] = $row["med_id"];
  }
  $props = Array("objects"=>$_ids);
  break;
  case "export_tickets":
  $bez="export_tickets_sbj_id_".$sbj_id;
  $_ids = Array();
  $sql = "SELECT tik_id FROM ticket WHERE sbj_id = " . $sbj_id;
  $rs = $myDB->query($sql);
  while ($row=mysql_fetch_array($rs))
  {
  $_ids[] = $row["tik_id"];
  }
  $props = Array("objects"=>$_ids);
  break;
  default:
  return false;
  break;
  }

  $this->props = $props;
  $this->storeData("system.".$bez);
  return true;
  }
  */


}
