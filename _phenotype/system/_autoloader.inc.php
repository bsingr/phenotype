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
function __autoload($class_name) {

  // create inheritage of standard classes, if not inherited by application

  $_classes = Array("Phenotype","PhenotypeRequest","PhenotypeAdmin","PhenotypeComponent","PhenotypeContent","PhenotypeExtra","PhenotypeInclude","PhenotypePage","PhenotypeAction","PhenotypeTicket","PhenotypeBackend","PhenotypeUser","PhenotypeDataObject","PhenotypeMediabase","PhenotypeMediaObject","PhenotypeImage","PhenotypeDocument","PhenotypeLayout","PhenotypePackage","PhenotypeSystemDataObject","PhenotypeNavigationHelper","PhenotypeSmarty","PhenotypeLocaleManager","PhenotypeSoapServer","PhenotypePeer","PhenotypeDebugger");

  if (in_array($class_name,$_classes))
  {
    $php = "class " . $class_name . " extends " .$class_name. "Standard {}";
    eval ($php);
    return;
  }

  // all standard classes are most likely in the classpath

  if (mb_substr ($class_name,-8)=="Standard")
  {
    $file = CLASSPATH . mb_substr($class_name,0,-8). ".class.php";
    if (file_exists($file))
    {
      require_once ($file);
      return;
    }
  }


  // deprecated, but still needed and inheritable

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

  if (mb_substr($class_name,0,19)=="PhenotypeComponent_")
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
  if (mb_substr($class_name,0,17)=="PhenotypeInclude_")
  {
    require_once  APPPATH . "includes/". $class_name . '.class.php';
    return;
  }
  if (mb_substr($class_name,0,17)=="PhenotypeContent_")
  {
    require_once  APPPATH . "content/". $class_name . '.class.php';
    return;
  }

  if (mb_substr($class_name,0,15)=="PhenotypeExtra_")
  {
    require_once  APPPATH . "extras/". $class_name . '.class.php';
    return;
  }

  if (mb_substr($class_name,0,16)=="PhenotypeAction_")
  {
    require_once  APPPATH . "actions/". $class_name . '.class.php';
    return;
  }

  if (mb_substr($class_name,0,17)=="PhenotypeBackend_")
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
  
    // Evtl. eine freie Applikations-Klasse?
    $file =  APPPATH . "class/". $class_name . ".class.php";
    if (file_exists ( $file ))
    {
      require_once ($file);
      return;
    }
     // Am Ende ein erneuter Check im class-Ordner (ohne Beschränkung auf Klassen, die Standard im Namen tragen)
    $file = CLASSPATH . $class_name . ".class.php";
    if (file_exists ( $file ))
    {
      require_once ($file);
      return;
    }
  throw new Exception("Class autoloading failure. Unknown class " .$class_name);
}

