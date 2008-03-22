<?php
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

// in this file you will find usefull functions, which are always availabe, e.g. for url management, encoding and so on
// You'll see, that must of the functions simply call an equal named method of the Phenotpye class. That's on purpose, so
// you still can overwrite this functions through ineritage.
//
// Please use this functions frequently. Most of them uses data objects for caching and can significantly speed up your
// application


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

function url_for_page($pag_id,$_params=array(),$lng_id=null,$smartUID="")
{
  global $myPT;
  return $myPT->url_for_page($pag_id,$_params,$lng_id,$smartUID);
}

function title_of_page($pag_id,$lng_id=null)
{
  global $myPT;
  return $myPT->title_of_page($pag_id,$lng_id);
}


function get_image($img_id,$alt=null,$style="",$class="")
{
  global $myPT;
  return $myPT->get_image($img_id,$alt,$style,$class);
}

/*
function url_for_po($myPage,$params=array())
{
return PhenotypeHelper::url_for_po($myPage,$params);
}

function url_for_content($con_id,$dat_id,$action="show")
{
return PhenotypeHelper::url_for_content($con_id,$dat_id,$action);
}

function url_for_co($myCO,$action="show")
{
return PhenotypeHelper::url_for_co($myCO,$action);
}

function url_for_symbol($symbol,$params = Array())
{
return PhenotypeHelper::url_for_symbol($symbol,$params);
}
*/