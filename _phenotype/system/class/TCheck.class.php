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
class TCheck
{
  var $time_start;
  var $time_end;
  
  function TCheck()
  {
  // Konstruktor
  } 
  
  function start()
  {
    $this->time_start=$this->getmicrotime();
  }
  
  function stop()
  {
     $this->time_end=$this->getmicrotime();
  }
  
  function result($step = "")
  {
    if ($step !="")
	{
	  echo "Duration [" . $step . "]: ";
	}
	else
	{
      echo "Duration: ";
	}  
	echo ($this->time_end - $this->time_start) . " seconds <br>";
	flush();
  }
  
  function getMicrotime()
  { 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
  } 
	
  function getSeconds()
  {
    return ($this->time_end - $this->time_start);
  }	
}
?>