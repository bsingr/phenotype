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


define ("DB_NUMBER", 1); // INTEGER !!
define ("DB_STRING", 2);	

/**
 * @package phenotype
 * @subpackage system
 *
 */
class SqlBuilder
{
var $felder;
var $feldtypen;
var $values;

  function SqlBuilder()
  {
    $felder = array ();
	$feldtypen = array();
  }
  
  function addField($name,$value,$typ=DB_STRING)
  {

	//if ($value==="" AND $typ==DB_NUMBER)
	//{
	//  $value="NULL";
	//}
    if (!isset($value)AND $typ==DB_NUMBER){$value="NULL";}
	
    $this->felder[] = $name;
	$this->values[] = $value;
	$this->feldtypen[] = $typ;
  }
  
  function update($tabelle,$where)
  {
    $sql = "UPDATE `" . $tabelle . "` SET ";
	$c=count($this->felder);
	for ($i=0;$i<$c;$i++)
	{
      if ($i!=0){$sql.=", ";}
	  $sql.= "`".$this->felder[$i]."` = ";
	  if ($this->feldtypen[$i]==DB_NUMBER)
	  {
	    $sql.= (int)$this->values[$i];
	  }
	  else
	  {
	    $s = $this->values[$i];
		$sql.= "'" . mysql_real_escape_string($s) . "'";
	  }
	}
	
	$sql .=" WHERE " . $where;
	return $sql;
  }
  
function insert($tabelle)
  {
    if (isset($this->felder))
	{
      $sql = "INSERT INTO `" . $tabelle . "` (";
	  $c=count($this->felder);
	  for ($i=0;$i<$c;$i++)
	  {
	    if ($i!=0){$sql.=", ";}
	    $sql .= "`".$this->felder[$i]."`";
	  }
	  $sql .= ") VALUES (";
	  for ($i=0;$i<$c;$i++)
	  {
	    if ($i!=0){$sql.=", ";}
	    if ($this->feldtypen[$i]==DB_NUMBER)
	    {
	      $sql.= (int)$this->values[$i];
	    }
	    else
	    {
	      $sql.= "'" . mysql_real_escape_string($this->values[$i]) . "'";
	    }
	  }
	  $sql .=")";
	  return $sql;
	}
	else
	{
	  // kein einziges Feld angegeben
	  $sql = "INSERT INTO " . $tabelle;
	  return $sql;
	}
  }  
  
  
}
?>