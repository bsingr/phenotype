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

if (PT_DEBUG==1)
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

// This function is used for xmlencode preg_replace_callback
function match2Entity($matches)
{
  $c = $matches[0];
  return "&#".ord($c).";";
}

// we don't want to have Cookies in our request arrays
ini_set("gpc_order","GP");