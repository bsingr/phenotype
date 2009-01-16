{literal}<?php
/**
 * Name of your include
 *
 * @package phenotype
 * @subpackage application
 */
class PhenotypeInclude_{/literal}{$id}{literal} extends PhenotypeInclude
{
  
  public $id = {/literal}{$id}{literal};

  public function display()
  {
    global $myDB, $myPage, $myRequest;
	
	// Initialize template access (=>$mySmarty) 
	// eval ($this->initRendering());	
  }

}
?>{/literal}