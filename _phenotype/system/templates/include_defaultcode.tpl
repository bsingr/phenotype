{literal}<?php
class PhenotypeInclude_{/literal}{$id}{literal} extends PhenotypeInclude
{
  // Bezeichnung des Includes
  
  public $id = {/literal}{$id}{literal};

  function display()
  {
    global $myDB;
	
	// Initialisieren des Smartyzugriffs
	eval ($this->initRendering());	
  }
  
  //function displayXML()
}
?>{/literal}