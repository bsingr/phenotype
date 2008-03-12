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
  
  public function execute ()
  {
    global $myRequest;

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
      echo $template;
      $mySmarty->display ($$template);
    }

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