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

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeIncludeControllerStandard extends PhenotypeInclude
{
  const naming_convention_symfony = 1;
  const naming_convention_zend = 2;

  public $default_naming_convention = self::naming_convention_symfony;
  //public $default_naming_convention = self::naming_convention_zend;

  const view_succes = "Success";
  const view_error = "Error";
  const view_none = false;

  public $props = Array ();
  
  public $disableLayout = false;
  
  public function execute ()
  {
    global $myRequest;
    global $myPT;

    $action = $myRequest->get("action");
    if ($action=="")
    {
      $action="index";
    }

    switch ($this->default_naming_convention)
    {
      case self::naming_convention_symfony:
        $methodname = "execute" . strtoupper($action[0]) . substr ($action,1);
        break;
      case self::naming_convention_zend:
        $methodname = strtolower($action[0]) . substr ($action,1)."Action";
        break;

    }
    $buffer_preexecution = $myPT->stopBuffer();

    $myPT->startBuffer();
	

	
    $view = call_user_method($methodname,$this,$myRequest);
    if (is_null($view)){$view="Success";}
    if ($view!=false)
    {
      eval ($this->initRendering());
      foreach ($this->props AS $k => $v)
      {
        $mySmarty->assign($k,$v);
      }
      $template = $action . $view;
      $mySmarty->display ($$template);
    }
    if ($this->disableLayout)
    {
    	echo $myPT->stopBuffer();
    	die();
    }
	$buffer_execution =$myPT->stopBuffer();
	echo $buffer_preexecution;
	echo $buffer_execution;
	
  }

  public function disableLayout()
  {
  	$this->disableLayout = true;
  }
  
  public function __call($methodname,$params)
  {
    throw new Exception("Undefined action method ".$methodname.".");
  }

  public function __set($k,$v)
  {
    $this->props[$k] = $v;
  }
  
  public function __get($k)
  {
    return $this->props[$k];
  }

}