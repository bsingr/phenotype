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


/* **********************
* _constants.inc.php
*
* constants (except config) should be in this file
*
* this file should NEVER EVER have ANY DEPENDENCIES
* it is included very early to be accessible everywhere and because of this, it must not depend on any other file
* @package phenotype
* @subpackage system
*
*/

define ("PT_CHARSET","iso-8859-1");

define("MB_IMAGE", 1);
define("MB_DOCUMENT", 2);

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
define ("PT_CON_FORM_HEADLINE", 1);
define ("PT_CON_FORM_TEXTFIELD", 2);
define ("PT_CON_FORM_TEXTAREA", 3);
define ("PT_CON_FORM_DATE", 4);
define ("PT_CON_FORM_HTML", 5);
define ("PT_CON_FORM_SELECTBOX", 6);
define ("PT_CON_FORM_NEWLINE", 7);
define ("PT_CON_FORM_DATETIME", 8);
define ("PT_CON_FORM_IMAGEEXTERN", 9);
define ("PT_CON_FORM_SEQUENCE", 10);
define ("PT_CON_FORM_IMAGESELECTOR", 11);
define ("PT_CON_FORM_RICHTEXT", 12);
define ("PT_CON_FORM_DOUBLETEXTFIELD", 13);
define ("PT_CON_FORM_CHECKBOX", 14);
define ("PT_CON_FORM_LINK", 15);
define ("PT_CON_FORM_DOCUMENT", 16);
define ("PT_CON_FORM_SCRIPT", 17);
define ("PT_CON_FORM_COMMENT", 18);
define ("PT_CON_FORM_TEXTFIELDCLUSTER", 19);
define ("PT_CON_FORM_MULTISELECTBOX", 20);
define ("PT_CON_FORM_EXLIST", 21);
define ("PT_CON_FORM_BUTTON", 22);
define ("PT_CON_FORM_WRAP", 23);
define ("PT_CON_FORM_DOUBLESELECTBOX", 24);
define ("PT_CON_FORM_NUMBER", 25);
define ("PT_CON_FORM_DOCUMENTSELECTOR", 26);
define ("PT_CON_FORM_MEDIASELECTOR", 27);
define ("PT_CON_FORM_EDITLINK", 28);
define ("PT_CON_FORM_DDUPLOAD", 29);
define ("PT_CON_FORM_DDPOSITIONER", 30);
define ("PT_CON_FORM_DDTEXTFIELDCLUSTER", 31);
define ("PT_CON_FORM_TABLE", 32);
define ("PT_CON_FORM_HIDDEN", 33);
define ("PT_CON_FORM_CONTENTSELECTBOX", 34);
define ("PT_CON_FORM_CONTENTMULTISELECTBOX", 35);
define ("PT_CON_FORM_PAGER", 36);
define ("PT_CON_FORM_JAVASCRIPTONLOAD", 37);
define ("PT_CON_FORM_AJAX", 38);
define ("PT_CON_FORM_UPLOAD", 39);
define ("PT_CON_FORM_PASSWORD", 40);






// constants for logging levels, facilities and options

// the log levels:
// ERROR, WARNING, INFO, DEBUG
define("PT_LOGFACILITY_SYS", "SYSTEM");
define("PT_LOGFACILITY_APP", "APPLICATION");

define("PT_LOGLVL_ERROR", 1);
define("PT_LOGLVL_WARNING", 2);
define("PT_LOGLVL_INFO", 3);
define("PT_LOGLVL_DEBUG", 4);

define("PT_LOGMTH_FILE", "FILE");

define("PT_RTF_EDITOR_FCKEDITOR", "fckEditor");
define("PT_RTF_EDITOR_TINYMCE", "tinyMCE");
define("PT_EDITOR_RTF", 1);
define("PT_EDITOR_CODE", 2);


$_PT_HTTP_CONTENTTYPES = Array(1=>"text/html;charset=iso-8859-1",2=>"text/css;charset=iso-8859-1",3=>"text/javascript;charset=iso-8859-1",4=>"text/xml;charset=iso-8859-1",21=>"text/html;charset=iso-8859-15",22=>"text/css;charset=iso-8859-15",23=>"text/javascript;charset=iso-8859-15",24=>"text/xml;charset=iso-8859-15",101=>"text/html;charset=utf-8",102=>"text/css;charset=utf-8",103=>"text/javascript;charset=utf-8",104=>"text/xml;charset=utf-8",200=>"no header");
