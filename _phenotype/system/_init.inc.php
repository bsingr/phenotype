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
if (!defined('PT_VERBOSE_UNTIL'))
{
	define ("PT_VERBOSE_UNTIL",0);
}
if (PT_DEBUG==1 OR PT_VERBOSE_UNTIL>time())
{
  error_reporting(E_ALL ^ E_NOTICE); // DEVELOPMENT
  set_error_handler(array("Phenotype","handleError"));
}
else
{
  error_reporting(0); // LIVE
}

ini_set("log_errors",true);
ini_set("error_log",TEMPPATH."logs/phperror.log");

set_exception_handler(array("Phenotype","handleException"));

if (!defined('PT_CHARSET'))
{
	define ("PT_CHARSET","UTF-8"); // or iso-8859-1
}
if (mb_strtoupper(PT_CHARSET)!=PT_CHARSET)
{
	throw new Exception('PT_CHARSET definition in _config.inc.php must be uppercase to be compatible with the htmlentities php function.');
}
mb_internal_encoding(PT_CHARSET);
mb_regex_encoding(PT_CHARSET);

// This function is used for xmlencode preg_replace_callback
function match2Entity($matches)
{
  $c = $matches[0];
  return "&#".ord($c).";";
}

// we don't want to have Cookies in our request arrays
ini_set("gpc_order","GP");


switch (PT_LOCALE)
{
	case "de":
		define ("PT_ALPHAINT","ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
		define ("PT_ALPHA","ABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜßabcdefghijklmnopqrstuvwxyzöäü");
		break;
	default:
		define ("PT_ALPHAINT","ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
		define ("PT_ALPHA","ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
		break;
}
define ("PT_ALPHANUMERIC",PT_ALPHA."0123456789");
define ("PT_ALPHANUMERICINT",PT_ALPHAINT."0123456789");
define ("PT_ALPHAPLUS",PT_ALPHANUMERIC.".,:;-_*+!§$%&()[]=?^#~?@");
define ("PT_ALPHAPLUSQUOTES",PT_ALPHAPLUS."'\"'");

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}

if (!defined('PT_PHPIDS'))
{
	if (phpversion()<"5.1.6")
	{
		define ('PT_PHPIDS',0);
	}
	else 
	{
		define ('PT_PHPIDS',1);
	}
}
if (!defined('PT_PHPIDS_MAXIMPACT'))
{
	define ('PT_PHPIDS_MAXIMPACT',10);
}

if (!defined('PT_PHPIDS_EXCLUDES'))
{
	define ('PT_PHPIDS_EXCLUDES','');
}


if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
{
@date_default_timezone_set(@date_default_timezone_get());
}


if (!defined("UMASK"))
{
	define ("UMASK",0775);
}