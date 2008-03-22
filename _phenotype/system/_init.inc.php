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


/* **********************
* init.inc.php
*
* some helper and initialization functions that are always needed
*/

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
