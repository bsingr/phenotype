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
class PhenotypeTree
{
  var $nav_id_root=0;
  var $counter=0;
  var $_flat = Array();
  var $_topnodes = Array();
  var $_tree = Array();
  var $_flattree = Array();
  
  function addNode($bez,$url,$nav_id_top=0,$ext_id="")
  {
    $this->counter++;
	$nav_id = $this->counter;
    $_node = Array("nav_id"=>$nav_id,"nav_id_top"=>$nav_id_top,"bez"=>$bez,"url"=>$url,"ext_id"=>$ext_id=$ext_id);
	$this->_flat[$nav_id] = $_node;
	$this->_topnodes[$nav_id_top][]=$nav_id;
	return $nav_id;
  }
  
  function rewriteNode($nav_id,$bez,$url,$ext_id="")
  {
  	$this->_flat[$nav_id]["bez"]=$bez;
  	$this->_flat[$nav_id]["url"]=$url;
  	$this->_flat[$nav_id]["ext_id"]=$ext_id;
  }
 
  function setRoot($nav_id)
  {
    $this->nav_id_root =$nav_id;
  }
  
  function buildtree()
  {
    $this->_tree=Array();
	$this->_flattree=Array();
    //print_r ($this->top_nodes);
	if (!array_key_exists($this->nav_id_root,$this->_topnodes))
	{
	  if (PT_DEBUG==1)
	  {
		echo localeH("Inconclusive tree structure");
	  }
	  return;
	}
	$this->_tree = $this->rbuildtree(0,1);
	
  }
  
  function rbuildtree($nav_id_top,$ebene)
  {
    $_tree=Array();
	$_nodes = $this->_topnodes[$nav_id_top];
	for ($i=0;$i<count($_nodes);$i++)
	{
	  $nav_id = $_nodes[$i];
	  $_tree[$i]["nav_id"]= $nav_id;
	  $_tree[$i]["nav_id_top"]= $nav_id_top;
	  $_tree[$i]["ebene"]=$ebene;
	  $_tree[$i]["bez"]=$this->_flat[$nav_id]["bez"];
	  $_tree[$i]["url"]=$this->_flat[$nav_id]["url"];
	  $_tree[$i]["ext_id"]=$this->_flat[$nav_id]["ext_id"];
	  if (array_key_exists($nav_id,$this->_topnodes))
	  {
	  	$_tree[$i]["next"]=1;
	    $this->_flattree[] = $_tree[$i];	  
	    $_tree[$i]["next"]= $this->rbuildtree($nav_id,($ebene+1));
	  }
	  else
	  {
	  	$_tree[$i]["next"]=0;
	    $this->_flattree[] = $_tree[$i];
	  }
	}
	return $_tree;
  }
  
  function displayTree()
  {
    $this->buildtree(); 
    echo "<pre>";
	print_r($this->_tree);
	echo"</pre>";
  }

  function displayFlat()
  {
    $this->buildtree(); 
    echo "<pre>";
	print_r($this->_flattree);
	echo"</pre>";
  }  
  
}
?>
