{literal}<?php
/**
 * Name of your component
 *
 * @package phenotype
 * @subpackage application
 */
class PhenotypeComponent_{/literal}{$id}{literal} extends PhenotypeComponent
{
  public $com_id = {/literal}{$id}{literal};

  public $name = "New component"; // is shown as label in the editing area

  public function setDefaultProperties()
  {
	$this->set("_revision",1);
  }
  
  public function initForm($context)
  {
 	// Customize input form with form_xy-methods 
    $this->form_textfield("Headline","headline",300);
  }

  public function render($context)
  {
	// Example:
		
	// Initialize template access (=>$mySmarty)  
    eval ($this->initRendering());

    $mySmarty->assign("headline",$this->getH("headline"));
    $html = $mySmarty->fetch($TPL_1);

    return $html;
  }
  
}{/literal}