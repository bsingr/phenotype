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
// www.phenotype-cms.com - offical homepage
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

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
    $this->register_function("description_of_page", array($this,"description_of_page")); // added 2008/05/19 by Dominique Bös

    $this->register_function("pt_constant",array($this,"pt_constant"));
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
      if (isset($_params["fullUrl"])){$fullUrl = $_params["fullUrl"];}else{$fullUrl=false;}
      return url_for_page($pag_id, null, null, "", $fullUrl);
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
  
  
  /**
   * Returns the page description field ("page_bez" in DB page)
   * 
	 * added 2008/05/19 by Dominique Bös
   * @return string page description
   */
  public function description_of_page($_params)
  {

    if (isset($_params["pag_id"]))
    {
      $pag_id = $_params["pag_id"];
      return description_of_page($pag_id);
    }
    else
    {
      trigger_error("Missing mandatory parameter pag_id in smarty function description_of_page",E_USER_ERROR);
    }
  }
  
  
  public function pt_constant($_params)
  {
    if (isset($_params["value"]))
    {
        return (constant($_params["value"]));     
    }
    else
    {
      trigger_error("Missing mandatory parameter value in smarty function pt_path",E_USER_ERROR);
    }
  }
}