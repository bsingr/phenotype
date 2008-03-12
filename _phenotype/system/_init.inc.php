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


function __autoload($class_name) {

  // create inheritage of standard classes, if not inherited by application

  $_classes = Array("Phenotype","PhenotypeRequest","PhenotypeAdmin","PhenotypeComponent","PhenotypeContent","PhenotypeExtra","PhenotypeInclude","PhenotypePage","PhenotypeAction","PhenotypeTicket","PhenotypeBackend","PhenotypeUser","PhenotypeDataObject","PhenotypeDataObject2","PhenotypeMediabase","PhenotypeMediaObject","PhenotypeImage","PhenotypeDocument","PhenotypeLayout","PhenotypePackage","PhenotypeIncludeController");

  if (in_array($class_name,$_classes))
  {
    $php = "class " . $class_name . " extends " .$class_name. "Standard {}";
    eval ($php);
    return;
  }

  // all standard classes are most likely in the classpath

  if (substr ($class_name,-8)=="Standard")
  {
    $file = CLASSPATH . substr($class_name,0,-8). ".class.php";
    if (file_exists($file))
    {
      require_once ($file);
      return;
    }
  }


  // deprecated, but still needed and ineritable

  if ($class_name=="PhenotypeAdminLayout")
  {
    eval ("class PhenotypeAdminLayout extends PhenotypeLayout {}");
    return;
  }

  // specific classes without standard inheritage

  $_classes = Array ("Smarty"=>SMARTYPATH . "Smarty.class.php","PhenotypeTree"=>CLASSPATH."PhenotypeTree.class.php");
  if (array_key_exists($class_name,$_classes))
  {
    $file = $_classes[$class_name];
    if (file_exists($file))
    {
      require_once ($file);
      return;
    }
  }

  if (substr($class_name,0,19)=="PhenotypeComponent_")
  {
    $file =  APPPATH . "components/". $class_name . '.class.php';
    if (file_exists($file))
    {
      require_once ($file);
      return;
    }
    else
    {
      // Even if a component is unknow, editing will continue !

      $php = "class " . $class_name . " extends PhenotypeComponent {}";
      eval ($php);
      return;
    }

  }
  if (substr($class_name,0,17)=="PhenotypeInclude_")
  {
    require_once  APPPATH . "includes/". $class_name . '.class.php';
    return;
  }
  if (substr($class_name,0,17)=="PhenotypeContent_")
  {
    require_once  APPPATH . "content/". $class_name . '.class.php';
    return;
  }

  if (substr($class_name,0,15)=="PhenotypeExtra_")
  {
    require_once  APPPATH . "extras/". $class_name . '.class.php';
    return;
  }

  if (substr($class_name,0,16)=="PhenotypeAction_")
  {
    require_once  APPPATH . "actions/". $class_name . '.class.php';
    return;
  }

  if (substr($class_name,0,17)=="PhenotypeBackend_")
  {
    $file = SYSTEMPATH . "backend/". $class_name . '.class.php';
    if (file_exists($file))
    {
      require_once($file);
      $file =  APPPATH . "backend/". $class_name . '.class.php';
      if (file_exists($file))
      {
        require_once($file);
      }
      else
      {
        $php = "class " . $class_name . " extends " . $class_name ."_Standard {}";
        eval ($php);
      }

      return;
    }
    else // keine Systembackendklasse, aber vielleich in der Applikation
    {
      $file =  APPPATH . "backend/". $class_name . '.class.php';
      if (file_exists($file))
      {
        require_once($file);
        return;
      }

    }



  }
  throw new Exception("Class autoloading failure. unkonw class " .$class_name);
}

/* **********************
* init.inc.php
*
* constants (except config) should be in this file
* also some helper functions that are always needed
*/


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
  set_error_handler(array("Phenotype","handleError"));
}
else
{
  error_reporting(0); // LIVE
}

ini_set("log_errors",true);
ini_set("error_log",TEMPPATH."logs/phperror.log");

set_exception_handler(array("Phenotype","handleException"));

$_PT_HTTP_CONTENTTYPES = Array(1=>"text/html;charset=iso-8859-1",2=>"text/css;charset=iso-8859-1",3=>"text/javascript;charset=iso-8859-1",4=>"text/xml;charset=iso-8859-1",5=>"application/vnd.wap.xhtml+xml;charset=iso-8859-1",21=>"text/html;charset=iso-8859-15",22=>"text/css;charset=iso-8859-15",23=>"text/javascript;charset=iso-8859-15",24=>"text/xml;charset=iso-8859-15",25=>"application/vnd.wap.xhtml+xml;charset=iso-8859-15",101=>"text/html;charset=utf-8",102=>"text/css;charset=utf-8",103=>"text/javascript;charset=utf-8",104=>"text/xml;charset=utf-8",200=>"kein Header");

// This function is used for xmlencode preg_replace_callback

function match2Entity($matches)
{
  $c = $matches[0];
  return "&#".ord($c).";";
}
