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
	
	/**
	 * finds all direct subPages of a page
	 * 
	 * @param int $pag_id	id of the page of which the subpages should be found
	 * @param boolean $status	defines if all pages are shown or only those with status online
	 *
	 * @return Array	contains ids of all child pages ordered by pag_pos
	 */
  public function getSubPages($pag_id, $status=true, $grp_id=0)
  {
    global $myDB;

    $token = "subpages_".$pag_id."_status_".(int)$status;
    if ($grp_id)
    {
    	$token .= "_". (int)$grp_id;
		}

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

    if ($grp_id)
    {
      $sql .=" AND grp_id=$grp_id";
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

	/**
	 * shows the path from the given page to the top of the tree
	 *
	 * gets recursively all parent pages of a given page until the top node
	 *
	 * @param int	$pag_id	id of the page to find the path for
	 *
	 * @return Array	contains ids of all pages from top to the given page. element 0 is the top page.
	 */
  public function getPagesWithinPath($pag_id)
  {
    $token = "path_".$pag_id;
    if ($this->hasToken($token))
    {
      return ($this->getDaoValue($token));
    }
    $pag_id_parent = $this->getParentPage($pag_id);
    $_pages = Array();
    $_pages[]=(int)$pag_id;
    while ($pag_id_parent !=0)
    {
      $_pages[] = $pag_id_parent;
      $pag_id_parent = $this->getParentPage($pag_id_parent);
    }
    $_pages = array_reverse($_pages);
    return $this->storeDaoValue($token,$_pages);
  }

	/**
	 * gets the parent of a given page
	 *
	 * @param int $pag_id	id of the page to find the parent for
	 *
	 * @return int	id of the parent page. 0 if there is no parent
	 */
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

	/**
	 * finds recursively all pages below a given page
	 *
	 * @param int $pag_id_top	pag_id of start node for the tree
	 * @param Array $_expand	:TODO: what is this?
	 * @param int $maxdepth	maximum depths of the tree, recursion stops at this level
	 *
	 * @return Array	flat array. keys are the pag_ids of the pages, associated values show the level of the page
	 */
  public function getTree($pag_id_top, $_expand=Array(), $maxdepth=999, $grp_id=0)
  {
    $expand = implode("#",$_expand);
    $token = "tree_".$pag_id_top."_".$expand."_depth_".(int)$maxdepth;
    if ($grp_id)
    {
    	$token .= "_". (int)$grp_id;
		}
 
    if ($this->hasToken($token))
    {
      return ($this->getDaoValue($token));
    }

    $level=1;
    $this->_pages = Array();
    $this->_path = $_expand;
    foreach ($this->getSubPages($pag_id_top, true, $grp_id) AS $pag_id)
    {
      $this->_pages[$pag_id]= $level;
      if (in_array($pag_id,$_expand))
      {
        if ($level<$maxdepth)
        {
          $this->appendPages($pag_id, $level, $maxdepth, $grp_id);
        }
      }
    }
    return $this->storeDaoValue($token, $this->_pages);
  }

  private function appendPages($pag_id, $level, $maxdepth, $grp_id)
  {
    $level++;
    foreach ($this->getSubPages($pag_id, true, $grp_id) AS $pag_id)
    {
      $this->_pages[$pag_id]= $level;

      if (in_array($pag_id,$this->_path))
      {
        if ($level<$maxdepth)
        {
          $this->appendPages($pag_id,$level, $maxdepth, $grp_id);
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