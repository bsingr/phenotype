{literal}<?php
/**
 * Name of your component
 *
 * @package phenotype
 * @subpackage application
 */
class PhenotypeComponent_{/literal}{$id}{literal} extends PhenotypeComponent
{
  public $tool_type = {/literal}{$id}{literal};

  public $bez = "New component"; // is shown as label in the editing area


  public function setDefaultProperties()
  {
	  //$this->set("property","value");
  }
  
  public function edit($context)
  {
 	// Customize input form with form_xy-methods 
 	
    $this->form_textfield("Headline","headline",$this->get("headline"));
  }

  /*
  public function update()
  {
    $this->set("property","value"); 
    $this->fset("property","inputfield"); 
  }
  */
   
  public function render($context)
  {
	// Example:
		
	// Initialize template access (=>$mySmarty)  
    eval ($this->initRendering());

    $mySmarty->assign("bez",$this->getH("bez"));
    $html = $mySmarty->fetch($TPL_1);

    return $html;
  }
  
}
?>{/literal}