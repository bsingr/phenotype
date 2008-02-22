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
* constants (except config) should be in this file
* also some helper functions that are always needed
*/

/*
* Constants that identify types of phenotype objects
*/
define("PT_CFG_PAGEGROUP", "PG");
define("PT_CFG_LAYOUT", "LY");
define("PT_CFG_COMPONENT", "CM");
define("PT_CFG_INCLUDE", "IN");
define("PT_CFG_CONTENTCLASS", "CC");
define("PT_CFG_MEDIAGROUP", "MG");
define("PT_CFG_EXTRA", "EX");
define("PT_CFG_ACTION", "AC");
define("PT_CFG_TICKETSUBJECT", "SG");
define("PT_CFG_ROLE", "RO");
define("PT_CFG_PAGE", "PA");
define("PT_CFG_CONTENTOBJECT", "CO");
define("PT_CFG_MEDIAOBJECT", "MO");
define("PT_CFG_TICKET", "TI");
define("PT_CFG_USER", "US");


// constants used in forms
define ("PT_CON_FORM_NEWLINE", 7);
define ("PT_CON_FORM_SEQUENCE", 10);
define ("PT_CON_FORM_EXLIST", 21);


// constants for logging levels, facilities and options

// the log levels:
// ERROR, WARNING, INFO, DEBUG
define("PT_LOGFACILITY_SYS", "SYSTEM");
define("PT_LOGFACILITY_APP", "APPLICATION");

define("PT_LOGLVL_ERROR", "ERROR");
define("PT_LOGLVL_WARNING", "WARNING");
define("PT_LOGLVL_INFO", "INFO");
define("PT_LOGLVL_DEBUG", "DEBUG");

define("PT_LOGMTH_FILE", "FILE");

if (PT_DEBUG==1)
{
	error_reporting(E_ALL ^ E_NOTICE); // DEVELOPMENT
	//set_error_handler("onError");

}
else
{
	error_reporting(0); // LIVE
}

ini_set("log_errors",true);
ini_set("error_log",TEMPPATH."logs/phperror.log");

$_PT_HTTP_CONTENTTYPES = Array(1=>"text/html;charset=iso-8859-1",2=>"text/css;charset=iso-8859-1",3=>"text/javascript;charset=iso-8859-1",4=>"text/xml;charset=iso-8859-1",5=>"application/vnd.wap.xhtml+xml;charset=iso-8859-1",21=>"text/html;charset=iso-8859-15",22=>"text/css;charset=iso-8859-15",23=>"text/javascript;charset=iso-8859-15",24=>"text/xml;charset=iso-8859-15",25=>"application/vnd.wap.xhtml+xml;charset=iso-8859-15",101=>"text/html;charset=utf-8",102=>"text/css;charset=utf-8",103=>"text/javascript;charset=utf-8",104=>"text/xml;charset=utf-8",200=>"kein Header");

// This function is used for xmlencode preg_replace_callback

function match2Entity($matches)
{
	$c = $matches[0];
	return "&#".ord($c).";";
}
