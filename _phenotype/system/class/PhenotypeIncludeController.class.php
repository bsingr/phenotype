<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2008 Nils Hagemann, Paul Sellinger,
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
// Version 2.7 vom 17.11.2008
// -------------------------------------------------------

/**
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeIncludeControllerStandard extends PhenotypeInclude
{
  const naming_convention_phenotype = 0;
  const naming_convention_symfony = 1;
  const naming_convention_zend = 2;

  public $naming_convention = self::naming_convention_phenotype;


  const view_success = "Success";
  const view_error = "Error";
  const view_none = false;


  public $enable_actions = true;	
  
  public $props = Array ();
  
  /**
   * If set to true page layout rendering is canceled, when executing this include
   * 
   * Attention: That also means, that the whole rendering process is stopped after execution of this Include, so be sure, to place
   * it in the right order
   *
   * @var boolean
   */
  public $disableLayout = false;
  
  public function execute ()
  {
    global $myRequest;
    global $myPT;

    $action = "";
    
    if ($myRequest->check("action"))
    {
    	$action =$myRequest->getA("action",PT_ALPHA,"index");
    }
    else 
    {
    	$action =$myRequest->getA("smartParam1",PT_ALPHA);
    	if ($action!="")
    	{
    		$myRequest->shiftParams4Action($action);
    	}
    	else 
    	{
    		$action=index;
    	}
    }


    switch ($this->naming_convention)
    {
      case self::naming_convention_phenotype:
      	 $methodname = "execute" . strtoupper($action[0]) . substr ($action,1)."Action";
        break;
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
      if ($$template=="")
      {
      	throw new Exception("Missing template ".$template.". (return false, if no template should be selected!)");
      }
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
    throw new Exception("Undefined action method ".$methodname.' ($myRequest)');
  }

  public function __set($k,$v)
  {
    $this->props[$k] = $v;
  }
  
  public function __get($k)
  {
    return $this->props[$k];
  }
  
  public function redirect($action=index,$_params)
  {
  	global $myRequest;
  	if ($myRequest->check("smartPATH")) // We won't have smartPATH on POST requests
  	{
  		$url = $myRequest->get("smartPATH");
  	}
  	else 
  	{
  		$url = $myRequest->get("smartURL");
  	}
  	$url .="/".$action;
  	foreach ($_params AS $k=>$v)
  	{
  		$url .="/".$k."/".$v;
  	}
  	Header ("Location:" .$url);
  	exit();
  }

}