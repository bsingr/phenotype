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

// in this file you will find usefull functions, which are always availabe, e.g. for url management, encoding and so on
// You'll see, that must of the functions simply call an equal named method of the Phenotpye class. That's on purpose, so
// you still can overwrite this functions through ineritage.
//
// Please use this functions frequently. Most of them uses data objects for caching and can significantly speed up your
// application


/**
 * @package phenotype
 * @subpackage system
 *
 */

function codeH($value)
{
  global $myPT;
  return $myPT->codeH($value);
}

function codeI($value)
{
  global $myPT;
  return $myPT->codeI($value);
}

function codeHBR($value)
{
  global $myPT;
  return $myPT->codeHBR($value);
}

function codeX($value,$utf8=false)
{
  global $myPT;
  return $myPT->codeX($value,$utf8);
}

function codeA($value,$allowedchars=PT_ALPHANUMERIC)
{
  global $myPT;
  return $myPT->codeA($value,$allowedchars);
}

function codeAH($value,$allowedchars=PT_ALPHANUMERIC)
{
  global $myPT;
  return $myPT->codeAH($value,$allowedchars);
}

function codeWSL($value)
{
  global $myPT;
  return $myPT->codeWSL($value);
}
function codeHWSL($value)
{
  global $myPT;
  return $myPT->codeHWSL($value);
}

function url_for_page($pag_id, $_params=null, $lng_id=null, $smartUID="", $fullUrl=false)
{
  global $myPT;
  return $myPT->url_for_page($pag_id, $_params, $lng_id, $smartUID, $fullUrl);
}

function title_of_page($pag_id,$lng_id=null)
{
  global $myPT;
  return $myPT->title_of_page($pag_id,$lng_id);
}


/**
 * page description ("page_bez" in DB page)
 * added 2008/05/19 by Dominique Bös
 */
function description_of_page($pag_id,$lng_id=null)
{
  global $myPT;
  return $myPT->description_of_page($pag_id,$lng_id);
}


function get_image($img_id,$alt=null,$style="",$class="")
{
  global $myPT;
  return $myPT->get_image($img_id,$alt,$style,$class);
}

function url_for_content($dat_id,$action,$lng_id=null,$_params,$smartUID="",$fullUrl=false)
{
  global $myPT;
  return $myPT->url_for_content($dat_id,$action,$lng_id,$_params,$smartUID,$fullUrl);
}

function url_for_co($myCO,$action,$lng_id=null,$_params,$smartUID="",$fullUrl=false)
{
  global $myPT;
   return $myPT->url_for_co($myCO,$action,$lng_id,$_params,$smartUID,$fullUrl);
}

function locale($token,$_params=Array())
{
	global $myPT;
	return $myPT->locale($token,$_params);
}

function localeH($token,$_params=Array())
{
	global $myPT;
	return $myPT->localeH($token,$_params);
}

function localeHBR($token,$_params=Array())
{
	global $myPT;
	return $myPT->localeHBR($token,$_params);
}

function localeFullTime($timestamp)
{
	global $myPT;
	return $myPT->localeFullTime($timestamp);
}

function localeDate($timestamp)
{
	global $myPT;
	return $myPT->localeDate($timestamp);
}

function localeShortDate($timestamp)
{
	global $myPT;
	return $myPT->localeShortDate($timestamp);
}

function urlstrip($s,$lowercase=false)
{
	$s = trim($s);
	$s = str_replace(array(" ","/","_","&","?","---","--"),"-",$s);
	$s = str_replace("ä","ae",$s);
	$s = str_replace("ö","oe",$s);
	$s = str_replace("ü","ue",$s);
	$s = str_replace("Ä","Ae",$s);
	$s = str_replace("Ö","Oe",$s);
	$s = str_replace("Ü","Ue",$s);
	$s = str_replace("ß","ss",$s);
	$s = trim($s);


	// Alle Sonderzeichen, die nicht URL-typisch sind rausfiltern
	$patterns = "/[^-a-z0-9A-Z_,.\/]*/";
	$s = preg_replace($patterns,"",$s);
	if($lowercase)
	{
		$s=strtolower($s);
	}
	return $s;
}