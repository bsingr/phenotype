{literal}<?php
/**
 * Name of your content object class
 *
 * @package phenotype
 * @subpackage application
 */
class PhenotypeContent_{/literal}{$id}{literal} extends PhenotypeContent
{
  public $content_type = {/literal}{$id}{literal};
  
  // Remove comment slashes for multiple tabs
  // public $_blocks = Array("Config","Items");
  // public $_icons = Array("b_konfig.gif","b_items.gif");
  
  public function setDefaultProperties()
  {
	  $this->set("bez","New Record");	
  }
  
  public function init($row,$block_nr=0) 
  { 
    parent::init($row,$block_nr); 
	
    // Customize your form with form_xyz methods
    
    $this->form_textfield("Name","bez",200);
        
	// If you have multiple tabs ... 
		
	/*switch ($block_nr)
	{ 
	  case 0:
	  break;
	}*/
  }
  
  public function attachKeyFields()
  {
  	// define keys here
    // $this->setKey1($this->get("propertyxy"));
  }
  

}
?>{/literal}