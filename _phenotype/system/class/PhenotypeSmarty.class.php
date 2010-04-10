<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Kr�mer.
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

	/**
	   * Do not allow PHP Code within smarty templates
	   *
	   * use SMARTY_PHP_ALLOW, if you want the PHP code to be executed (right after Phenotype caching)
	   *
	   * Attention!:
	   * Caused by Phenotypes file caching mechanism the option SMARTY_PHP_PASSTHRU is
	   * identical to SMARTY_PHP_ALLOW
	   *
	   * Side-Effect!:
	   * Every "<?" will get escaped, also XML headers like <?xml version="1.0" encoding="ISO-8859-1"?>
	   * Use the smarty function pt_doctype instead, e.g.
	   * {pt_doctype dtd="XHTML1.0-Transitional" charset="ISO-8859-1"}
	   *
	   * @see: pt_doctype
	   * @var int
	   */
	var $php_handling    = SMARTY_PHP_QUOTE;

	//var $default_modifiers        = array("phenotype");


	/**
     * This forces templates to compile every time.
     *
     * If experiment with $php_handling set $force_compile to true. Otherwise you get 'strange' results.
     *
     * @var boolean
     */
    var $force_compile   =  true;

	public function __construct()
	{
		parent::Smarty();
		$this->register_function("url_for_page", array($this,"url_for_page"));
		$this->register_function("title_of_page", array($this,"title_of_page"));
		$this->register_function("description_of_page", array($this,"description_of_page")); // added 2008/05/19 by Dominique B�s

		$this->register_function("pt_constant",array($this,"pt_constant"));
		$this->register_function("url_for_content", array($this,"url_for_content"));
		$this->register_function("url_for_co", array($this,"url_for_co"));


		$this->register_function("pt_doctype", array($this,"pt_doctype"));

		// one day we might activate output escaping as default ;)
		//$this->register_modifier("phenotype", array($this,"default_modifier"));

		$this->register_modifier("escape", array($this,"smarty_modifier_escape"));
		$this->register_modifier("lower", array($this,"smarty_modifier_lower"));
		$this->register_modifier("upper", array($this,"smarty_modifier_upper"));
	}

	/*
	public function default_modifier($mixed)
	{
	if (is_string($mixed))
	{
	return htmlentities($mixed, ENT_QUOTES, PT_CHARSET);
	}
	else {return $mixed;}
	}
	*/


	public function url_for_page($_params)
	{

		if (isset($_params["pag_id"]))
		{
			$pag_id = $_params["pag_id"];
			if (isset($_params["fullUrl"])){$fullUrl = $_params["fullUrl"];}else{$fullUrl=false;}
			$lng_id = isset($_params["lng_id"])? $_params["lng_id"] : null;
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
			$lng_id = isset($_params["lng_id"])? $_params["lng_id"] : null;
			return title_of_page($pag_id, $lng_id);
		}
		else
		{
			trigger_error("Missing mandatory parameter pag_id in smarty function title_of_page",E_USER_ERROR);
		}
	}


	/**
   * Returns the page description field ("page_bez" in DB page)
   *
	 * added 2008/05/19 by Dominique B�s
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

	public function pt_doctype($_params)
	{
		$charset= PT_CHARSET;
		if (isset($_params["charset"]))
		{
			$charset = $_params["charset"];
		}

		switch($_params["dtd"])
		{
			case "HTML2.0":
				return '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">';
				break;
			case "HTML3.2":
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">';
				break;
			case "HTML4.01-Strict":
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
				break;
			case "HTML4.01-Transitional":
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
				break;
			case "HTML4.01-Frameset":
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
				break;
			case "XHTML1.0-Strict":
				return '<?xml version="1.0" encoding="'.$charset.'"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
				break;
			case "XHTML1.0-Frameset":
				return '<?xml version="1.0" encoding="'.$charset.'"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
				break;
			case "XHTML1.0-Transitional":
			default:
				return '<?xml version="1.0" encoding="'.$charset.'"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
				break;
			case "XHTML1.1":
				return '<?xml version="1.0" encoding="'.$charset.'"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
				break;
		}
	}

	public function url_for_content($_params)
	{
		if (isset($_params["dat_id"]))
		{
			$dat_id = $_params["dat_id"];
			$action="show";
			$lng_id=null;
			if (isset($_params["action"])){$action = $_params["action"];}
			if (isset($_params["lng_id"])){$lng_id = $_params["lng_id"];}
			return url_for_content($dat_id,$action,$lng_id);
		}
		else
		{
			trigger_error("Missing mandatory parameter dat_id in smarty function url_for_content",E_USER_ERROR);
		}
	}
	public function url_for_co($_params)
	{
		if (isset($_params["object"]))
		{
			$object = $_params["object"];
			$action="show";
			$lng_id=null;
			if (isset($_params["action"])){$action = $_params["action"];}
			if (isset($_params["lng_id"])){$lng_id = $_params["lng_id"];}
			return url_for_co($object,$action,$lng_id);
		}
		else
		{
			trigger_error("Missing mandatory parameter object in smarty function url_for_co",E_USER_ERROR);
		}
	}

	/**
	* PT CHARSET aware escape modifier
	*/
	function smarty_modifier_escape($string, $esc_type = 'html', $char_set = PT_CHARSET)
	{
		$path = ($this->_get_plugin_filepath("modifier", "escape"));
		require_once($path);
		return smarty_modifier_escape($string, $esc_type, $char_set);
	}

	/**
	 * multibyte save lower modifier
	 *
	 */
	function smarty_modifier_lower($string)
	{
		return mb_strtolower($string);
	}

	/**
	 * multibyte save upper modifier
	 *
	 */
	function smarty_modifier_upper($string)
	{
		return mb_strtoupper($string);
	}
}