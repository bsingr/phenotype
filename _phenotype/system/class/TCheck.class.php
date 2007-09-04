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