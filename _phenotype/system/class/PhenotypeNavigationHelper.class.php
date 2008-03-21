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
class PhenotypeNavigationHelperStandard
{
  /**
   * Holds the URLHelper dataobject during on request
   *
   * @var unknown_type
   */
  private $myDAO = false;


  private $_path = Array();
  private $_pages = Array();

  public function __construct()
  {
    $this->myDAO= new PhenotypeSystemDataObject("NavigationHelper",array(),false,true);
  }

  public function __destruct()
  {
    if ($this->myDAO->changed)
    {
      $this->myDAO->store(0,true);
    }

  }

  public function getSubPages($pag_id,$status=true)
  {
    global $myDB;

    $token = "subpages_".$pag_id."_status_".(int)$status;

    if ($this->hasToken($token))
    {
      return ($this->getDaoValue($token));
    }

    //$sql = "SELECT pag_id, (SELECT COUNT(*) AS C FROM page AS B WHERE B.pag_id_top=A.pag_id) AS C FROM page AS A WHERE pag_id_top = ". $pag_id;
    $sql = "SELECT pag_id FROM page AS A WHERE pag_id_top = ". $pag_id;

    if ($status)
    {
      $sql .=" AND pag_status=1";
    }

    $sql .= " ORDER BY pag_pos";

    $rs = $myDB->query($sql);
    $_pages = Array();
    while ($row=mysql_fetch_array($rs))
    {
      //$_pages[$row["pag_id"]]=$row["C"];
      $_pages[] = $row["pag_id"];
    }
    return $this->storeDaoValue($token,$_pages);
  }

  public function getPagesWithinPath($pag_id)
  {
    $token = "path_".$pag_id;
    if ($this->hasToken($token))
    {
      return ($this->getDaoValue($token));
    }
    $pag_id_parent = $this->getParentPage($pag_id);
    $_pages = Array();
    $_pages[]=$pag_id;
    while ($pag_id_parent !=0)
    {
      $_pages[] = $pag_id_parent;
      $pag_id_parent = $this->getParentPage($pag_id_parent);
    }
    $_pages = array_reverse($_pages);
    return $this->storeDaoValue($token,$_pages);
  }

  public function getParentPage($pag_id)
  {
    global $myDB;

    $token = "parent_".$pag_id;

    if ($this->hasToken($token))
    {
      return ($this->getDaoValue($token));
    }
    $sql = "SELECT pag_id_top FROM page WHERE pag_id = ". $pag_id;
    $rs = $myDB->query($sql);
    $row = mysql_fetch_array($rs);

    return $this->storeDaoValue($token,(int)$row["pag_id_top"]);
  }


  public function getTree($pag_id_top,$_expand=Array(),$maxdepth=999)
  {
    $expand = implode("#",$_expand);
    $token = "tree_".$pag_id_top."_".$expand."_depth_".(int)$maxdepth;
 
    if ($this->hasToken($token))
    {
      return ($this->getDaoValue($token));
    }

    $level=1;
    $this->_pages = Array();
    $this->_path = $_expand;
    foreach ($this->getSubPages($pag_id_top) AS $pag_id)
    {
      $this->_pages[$pag_id]= $level;
      if (in_array($pag_id,$_expand))
      {
        if ($level<$maxdepth)
        {
          $this->appendPages($pag_id,$level,$maxdepth);
        }
      }
    }
    return $this->storeDaoValue($token,$this->_pages);
  }

  private function appendPages($pag_id,$level,$maxdepth)
  {
    $level++;
    foreach ($this->getSubPages($pag_id) AS $pag_id)
    {
      $this->_pages[$pag_id]= $level;

      if (in_array($pag_id,$this->_path))
      {
        if ($level<$maxdepth)
        {
          $this->appendPages($pag_id,$level);
        }
      }
    }
  }


  public function insertPagesBefore($_currentpages,$_newpages)
  {
    $_pages = array();
    foreach ($_newpages AS $pag_id=>$level)
    {
      $_pages[$pag_id]=$level;
    }
    foreach ($_currentpages AS $pag_id=>$level)
    {
      $_pages[$pag_id]=$level;
    }
    return $_pages;
  }

  public function insertPagesAfter($_currentpages,$_newpages)
  {
    $_pages = array();
    foreach ($_currentpages AS $pag_id=>$level)
    {
      $_pages[$pag_id]=$level;
    }
    foreach ($_newpages AS $pag_id=>$level)
    {
      $_pages[$pag_id]=$level;
    }
    return $_pages;
  }

  private function hasToken($token)
  {
    return $this->myDAO->check($token);
  }

  private function getDaoValue($token)
  {
    return $this->myDAO->get($token);
  }

  private function storeDaoValue($token,$value)
  {
    $this->myDAO->set($token,$value);
    return $value;
  }

}