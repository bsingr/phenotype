<?
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Kr�mer, Annemarie Komor, Jochen Rieger, Alexander
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
class PhenotypeSmartyStandard extends Smarty
{

  //var $default_modifiers        = array("phenotype");

  public function __construct()
  {
    parent::Smarty();
    $this->register_function("url_for_page", array($this,"url_for_page"));
    $this->register_function("title_of_page", array($this,"title_of_page"));
    
    // one day we will activate output escaping as default ;) currently it's too big
    //$this->register_modifier("phenotype", array($this,"default_modifier"));
    
  }

  public function default_modifier($mixed)
  {
    if (is_string($mixed))
    {
      return htmlentities($mixed, ENT_QUOTES, PT_CHARSET);
    }
    else {return $mixed;}
  }
  

  public function url_for_page($_params)
  {
    
    if (isset($_params["pag_id"]))
    {
      $pag_id = $_params["pag_id"];
      return url_for_page($pag_id);
    }
    else
    {
      trigger_error("Missing mandatory parameter pag_id in smarty function url_for_page",E_USER_ERROR);
    }
  }

    public function title_of_page($_params)
  {
    
    if (isset($_params["pag_id"]))
    {
      $pag_id = $_params["pag_id"];
      return title_of_page($pag_id);
    }
    else
    {
      trigger_error("Missing mandatory parameter pag_id in smarty function title_of_page",E_USER_ERROR);
    }
  }
}